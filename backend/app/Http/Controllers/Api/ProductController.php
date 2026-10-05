<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::active()->with(['shop']);

        // Hyper-local shop location filtering
        $hasLocationFilter = $request->filled('upazila') || $request->filled('district') || $request->filled('division') || $request->filled('pourashava') || $request->filled('ward');

        $allDistricts = districtView();
        $allUpazilas = upazilasView();
        $divisions = [
            ['id' => '1', 'name' => 'Chattagram'],
            ['id' => '2', 'name' => 'Rajshahi'],
            ['id' => '3', 'name' => 'Khulna'],
            ['id' => '4', 'name' => 'Barisal'],
            ['id' => '5', 'name' => 'Sylhet'],
            ['id' => '6', 'name' => 'Dhaka'],
            ['id' => '7', 'name' => 'Rangpur'],
            ['id' => '8', 'name' => 'Mymensingh']
        ];

        $dstId = null;
        $dstName = $request->district;
        if ($request->filled('district')) {
            foreach ($allDistricts as $d) {
                if (strcasecmp($d['name'], $request->district) === 0 || $d['id'] == $request->district) {
                    $dstId = $d['id'];
                    $dstName = $d['name'];
                    break;
                }
            }
        }

        $upzId = null;
        $upzName = $request->upazila;
        if ($request->filled('upazila')) {
            foreach ($allUpazilas as $u) {
                if (strcasecmp($u['name'], $request->upazila) === 0 || $u['id'] == $request->upazila) {
                    $upzId = $u['id'];
                    $upzName = $u['name'];
                    break;
                }
            }
        }

        $divId = null;
        $divName = $request->division;
        if ($request->filled('division')) {
            foreach ($divisions as $div) {
                if (strcasecmp($div['name'], $request->division) === 0 || $div['id'] == $request->division) {
                    $divId = $div['id'];
                    $divName = $div['name'];
                    break;
                }
            }
        }

        $applyProductFilters = function ($query) use ($request) {
            if ($request->filled('shop_id')) {
                $query->where('sch_id', $request->shop_id);
            }

            // 2-Hour Express Delivery Products
            if ($request->filled('express') && ($request->express == '1' || $request->express == 'true')) {
                $query->whereHas('shop', function ($sq) {
                    $sq->where(function ($q) {
                        $q->where('type', 'express')
                          ->orWhere('priority_type', 'express')
                          ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%express%'")
                          ->orWhereIn('shop_cat_id', [1, 2, 4, 18, 19]);
                    });
                });
            }

            // Category Filtering (Supports Shop Category, Product Category, Demo Category, or Main Category IDs/Slugs)
            if ($request->filled('category_id') || $request->filled('cat_id') || $request->filled('shop_cat_id')) {
                $catInput = $request->input('cat_id', $request->input('category_id', $request->input('shop_cat_id')));
                
                $matchingShopCatIds = [];
                $matchingProdCatIds = [];
                $matchingDemoProductIds = [];
                $nameKeywords = [];

                // 1. Try as ShopCategory ID
                if (is_numeric($catInput)) {
                    $sc = ShopCategory::where('shop_cat_id', $catInput)->first();
                    if ($sc) {
                        $childIds = ShopCategory::where('parent_cat_id', $catInput)->pluck('shop_cat_id')->toArray();
                        $matchingShopCatIds = array_merge([(int)$catInput], $childIds);
                        if (!empty($sc->name)) $nameKeywords[] = $sc->name;
                        if (!empty($sc->title)) $nameKeywords[] = $sc->title;
                    }
                }

                // 2. Try as Category (categories table) or demo_category
                $cat = is_numeric($catInput)
                    ? Category::find($catInput)
                    : Category::where('slug', $catInput)->first();

                $allCategoryIds = [];
                if ($cat) {
                    $allCategoryIds[] = $cat->id;
                    $childCatIds = Category::where('parent_id', $cat->id)->pluck('id')->toArray();
                    $allCategoryIds = array_merge($allCategoryIds, $childCatIds);
                    $nameKeywords[] = $cat->name;

                    $relatedShopCats = ShopCategory::where('name', 'like', "%{$cat->name}%")
                        ->orWhere('title', 'like', "%{$cat->name}%")
                        ->pluck('shop_cat_id')->toArray();
                    $matchingShopCatIds = array_merge($matchingShopCatIds, $relatedShopCats);
                } elseif (is_numeric($catInput)) {
                    $allCategoryIds[] = (int)$catInput;
                }

                // Check demo_category table for this ID or parent
                if (is_numeric($catInput)) {
                    $demoCat = DB::table('demo_category')->where('cat_id', $catInput)->first();
                    if ($demoCat) {
                        $nameKeywords[] = $demoCat->product_category;
                        $demoChildIds = DB::table('demo_category')->where('parent_pro_cat', $catInput)->pluck('cat_id')->toArray();
                        $allCategoryIds = array_merge($allCategoryIds, $demoChildIds);
                    }
                }

                $allCategoryIds = array_unique(array_filter($allCategoryIds));

                // A) Fetch demo product IDs belonging to these category IDs
                if (!empty($allCategoryIds)) {
                    $demoIds = DB::table('demo_products')->whereIn('prod_cat_id', $allCategoryIds)->pluck('id')->toArray();
                    if (!empty($demoIds)) {
                        $matchingDemoProductIds = array_merge($matchingDemoProductIds, $demoIds);
                    }
                    $matchingProdCatIds = array_merge($matchingProdCatIds, $allCategoryIds);
                }

                // Stop words to avoid false positive cross-category matching
                $stopWords = ['fresh', 'pure', 'local', 'best', 'super', 'mini', 'standard', 'special', 'food', 'item', 'daily', 'new'];

                // B) Find matching product_category rows by name keywords
                if (!empty($nameKeywords)) {
                    $pcQuery = DB::table('product_category');
                    $pcQuery->where(function($pq) use ($nameKeywords, $stopWords) {
                        foreach ($nameKeywords as $kw) {
                            $cleanKw = trim(explode('(', $kw)[0]);
                            $pq->orWhere('product_category', 'like', "%{$cleanKw}%");
                            $words = array_filter(explode(' ', str_replace(['(', ')', '/', '-', ','], ' ', $cleanKw)), function($w) use ($stopWords) {
                                return mb_strlen($w) >= 3 && !in_array(strtolower($w), $stopWords);
                            });
                            foreach ($words as $w) {
                                $pq->orWhere('product_category', 'like', "%{$w}%");
                            }
                        }
                    });
                    $shopProdCatIds = $pcQuery->pluck('prod_cat_id')->toArray();
                    if (!empty($shopProdCatIds)) {
                        $matchingProdCatIds = array_merge($matchingProdCatIds, $shopProdCatIds);
                    }
                }

                $matchingDemoProductIds = array_unique($matchingDemoProductIds);
                $matchingProdCatIds = array_unique($matchingProdCatIds);
                $matchingShopCatIds = array_unique($matchingShopCatIds);

                $query->where(function ($q) use ($matchingShopCatIds, $matchingProdCatIds, $matchingDemoProductIds, $nameKeywords) {
                    $hasCondition = false;
                    if (!empty($matchingShopCatIds)) {
                        $q->whereHas('shop', function ($sq) use ($matchingShopCatIds) {
                            $sq->whereIn('shops.shop_cat_id', $matchingShopCatIds);
                        });
                        $hasCondition = true;
                    }
                    if (!empty($matchingProdCatIds)) {
                        if ($hasCondition) {
                            $q->orWhereIn('products.prod_cat_id', $matchingProdCatIds);
                        } else {
                            $q->whereIn('products.prod_cat_id', $matchingProdCatIds);
                            $hasCondition = true;
                        }
                    }
                    if (!empty($matchingDemoProductIds)) {
                        if ($hasCondition) {
                            $q->orWhereIn('products.demo_id', $matchingDemoProductIds);
                        } else {
                            $q->whereIn('products.demo_id', $matchingDemoProductIds);
                            $hasCondition = true;
                        }
                    }
                    if (!empty($nameKeywords)) {
                        foreach ($nameKeywords as $kw) {
                            $cleanKw = trim(explode('(', $kw)[0]);
                            if (mb_strlen($cleanKw) >= 3) {
                                if ($hasCondition) {
                                    $q->orWhere('products.name', 'like', "%{$cleanKw}%");
                                } else {
                                    $q->where('products.name', 'like', "%{$cleanKw}%");
                                    $hasCondition = true;
                                }
                            }
                        }
                    }
                });
            }

            if ($request->filled('search')) {
                \App\Services\SearchService::applyProductSearch($query, $request->search);
            }

            // Sorting (respect search relevance if no explicit sort provided)
            $sort = $request->get('sort', $request->filled('search') ? 'relevance' : 'latest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('products.selling_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('products.selling_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('products.name', 'asc');
                    break;
                case 'relevance':
                    $query->orderByDesc('products.prod_id');
                    break;
                default:
                    $query->orderByDesc('products.prod_id');
                    break;
            }
        };

        $buildQueryWithLocation = function (string $level) use ($applyProductFilters, $upzName, $upzId, $dstName, $dstId, $divName, $divId, $request) {
            $query = Product::active()->with(['shop']);

            if ($level === 'exact') {
                $query->whereHas('shop', function ($sq) use ($upzName, $upzId, $dstName, $dstId, $divName, $divId, $request) {
                    if ($upzName || $upzId) {
                        $sq->where(function ($q) use ($upzName, $upzId) {
                            if ($upzName) $q->where('shops.upazila', $upzName);
                            if ($upzId) {
                                $q->orWhere('shops.upazila', (string)$upzId);
                                $q->orWhereExists(function ($sub) use ($upzId) {
                                    $sub->select(DB::raw(1))
                                        ->from('global_address')
                                        ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                                        ->where('global_address.upazila', $upzId);
                                });
                            }
                        });
                    } elseif ($dstName || $dstId) {
                        $sq->where(function ($q) use ($dstName, $dstId) {
                            if ($dstName) $q->where('shops.district', $dstName);
                            if ($dstId) {
                                $q->orWhere('shops.district', (string)$dstId);
                                $q->orWhereExists(function ($sub) use ($dstId) {
                                    $sub->select(DB::raw(1))
                                        ->from('global_address')
                                        ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                                        ->where('global_address.zila', $dstId);
                                });
                            }
                        });
                    } elseif ($divName || $divId) {
                        $sq->where(function ($q) use ($divName, $divId) {
                            if ($divName) $q->where('shops.division', $divName);
                            if ($divId) {
                                $q->orWhere('shops.division', (string)$divId);
                                $q->orWhereExists(function ($sub) use ($divId) {
                                    $sub->select(DB::raw(1))
                                        ->from('global_address')
                                        ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                                        ->where('global_address.division', $divId);
                                });
                            }
                        });
                    }

                    if ($request->filled('pourashava')) {
                        $pVal = trim($request->pourashava);
                        $pAliases = match ($pVal) {
                            '1', 'Pourashava' => ['1', 'Pourashava'],
                            '2', 'Union' => ['2', 'Union'],
                            '0', 'City Corporation' => ['0', 'City Corporation'],
                            default => [$pVal],
                        };
                        $sq->whereIn('shops.pourashava', $pAliases);
                    }

                    if ($request->filled('ward')) {
                        $wVal = trim($request->ward);
                        $wNum = preg_replace('/[^0-9]/', '', $wVal);
                        $wOptions = array_values(array_filter([$wVal, $wNum, $wNum ? "Ward $wNum" : null]));
                        $sq->whereIn('shops.ward', $wOptions);
                    }
                });
            } elseif ($level === 'district' && ($dstName || $dstId)) {
                $query->whereHas('shop', function ($sq) use ($dstName, $dstId) {
                    $sq->where(function ($q) use ($dstName, $dstId) {
                        if ($dstName) $q->where('shops.district', $dstName);
                        if ($dstId) {
                            $q->orWhere('shops.district', (string)$dstId);
                            $q->orWhereExists(function ($sub) use ($dstId) {
                                $sub->select(DB::raw(1))
                                    ->from('global_address')
                                    ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                                    ->where('global_address.zila', $dstId);
                            });
                        }
                    });
                });
            } elseif ($level === 'division' && ($divName || $divId)) {
                $query->whereHas('shop', function ($sq) use ($divName, $divId) {
                    $sq->where(function ($q) use ($divName, $divId) {
                        if ($divName) $q->where('shops.division', $divName);
                        if ($divId) {
                            $q->orWhere('shops.division', (string)$divId);
                            $q->orWhereExists(function ($sub) use ($divId) {
                                $sub->select(DB::raw(1))
                                    ->from('global_address')
                                    ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                                    ->where('global_address.division', $divId);
                            });
                        }
                    });
                });
            }

            $applyProductFilters($query);
            return $query;
        };

        $fallbackApplied = false;
        $fallbackLevel = 'exact';
        $fallbackMessage = null;

        if ($hasLocationFilter) {
            $activeQuery = $buildQueryWithLocation('exact');
            $exactCount = (clone $activeQuery)->count();

            if ($exactCount === 0) {
                // Try fallback to District
                if ($dstName || $dstId) {
                    $distQuery = $buildQueryWithLocation('district');
                    if ((clone $distQuery)->count() > 0) {
                        $activeQuery = $distQuery;
                        $fallbackApplied = true;
                        $fallbackLevel = 'district';
                        $fallbackMessage = "আপনার নির্দিষ্ট এলাকায় কোনো পণ্য না থাকায় {$dstName} জেলার পণ্যসমূহ দেখানো হচ্ছে।";
                    }
                }

                // If still 0, try fallback to Division
                if ($fallbackLevel === 'exact' && ($divName || $divId)) {
                    $divQuery = $buildQueryWithLocation('division');
                    if ((clone $divQuery)->count() > 0) {
                        $activeQuery = $divQuery;
                        $fallbackApplied = true;
                        $fallbackLevel = 'division';
                        $fallbackMessage = "আপনার এলাকায় পণ্য না থাকায় {$divName} বিভাগের পণ্যসমূহ দেখানো হচ্ছে।";
                    }
                }

                // If still 0, try Nationwide
                if ($fallbackLevel === 'exact') {
                    $allQuery = $buildQueryWithLocation('all');
                    if ((clone $allQuery)->count() > 0) {
                        $activeQuery = $allQuery;
                        $fallbackApplied = true;
                        $fallbackLevel = 'all';
                        $fallbackMessage = "আপনার এলাকায় এই ক্যাটাগরির পণ্য পাওয়া না যাওয়ায় সারাদেশের পণ্যসমূহ দেখানো হচ্ছে।";
                    }
                }
            }
        } else {
            $activeQuery = $buildQueryWithLocation('all');
        }

        $perPage = min(60, max(6, (int) $request->get('per_page', 16)));
        $products = $activeQuery->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'meta' => [
                'fallback_applied' => $fallbackApplied,
                'fallback_level'   => $fallbackLevel,
                'fallback_message' => $fallbackMessage,
            ],
            'data' => $products,
        ]);
    }

    public function show(Request $request, string $idOrSlug): JsonResponse
    {
        $product = null;
        $shopParam = $request->query('shop', $request->query('shopSlug', $request->query('shop_id')));
        $targetShopId = null;

        if ($shopParam) {
            if (is_numeric($shopParam)) {
                $targetShopId = (int) $shopParam;
            } else {
                $shopObj = Shop::where('slug', $shopParam)->orWhere('name', 'like', '%' . str_replace('-', ' ', $shopParam) . '%')->first();
                if ($shopObj) {
                    $targetShopId = $shopObj->sch_id;
                }
            }
        }

        // 1. Direct Numeric ID
        if (is_numeric($idOrSlug)) {
            $product = Product::active()->where('prod_id', $idOrSlug)->with(['shop'])->first();
        }

        // 2. Exact match on indexed slug column (with Shop preference if shop provided)
        if (!$product) {
            if ($targetShopId) {
                $product = Product::active()->where('slug', $idOrSlug)->where('sch_id', $targetShopId)->with(['shop'])->first();
            }
            if (!$product) {
                $product = Product::active()->where('slug', $idOrSlug)->with(['shop'])->first();
            }
        }

        // 3. Clean string match on name
        if (!$product) {
            $clean = str_replace('-', ' ', $idOrSlug);
            $query = Product::active();
            if ($targetShopId) {
                $product = (clone $query)->where('sch_id', $targetShopId)->where('name', 'like', "%{$clean}%")->with(['shop'])->first();
            }
            if (!$product) {
                $product = $query->where('name', 'like', "%{$clean}%")->with(['shop'])->first();
            }
        }

        // 4. Wildcard match on name
        if (!$product) {
            $wildcard = '%' . str_replace('-', '%', $idOrSlug) . '%';
            $query = Product::active();
            if ($targetShopId) {
                $product = (clone $query)->where('sch_id', $targetShopId)->where('name', 'like', $wildcard)->with(['shop'])->first();
            }
            if (!$product) {
                $product = $query->where('name', 'like', $wildcard)->with(['shop'])->first();
            }
        }

        // 5. Inactive / deleted fallback check
        if (!$product && is_numeric($idOrSlug)) {
            $product = Product::where('prod_id', $idOrSlug)->with(['shop'])->first();
        }

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $relatedProducts = Product::active()
            ->where('prod_cat_id', $product->prod_cat_id)
            ->where('prod_id', '!=', $product->prod_id)
            ->with(['shop'])
            ->take(6)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::active()
                ->where('prod_id', '!=', $product->prod_id)
                ->with(['shop'])
                ->take(6)
                ->get();
        }

        // Multi-Shop duplicate product differentiation across districts/upazilas
        $otherShops = Product::active()
            ->where('name', 'like', "%{$product->name}%")
            ->where('prod_id', '!=', $product->prod_id)
            ->where('sch_id', '!=', $product->sch_id)
            ->with(['shop'])
            ->take(8)
            ->get()
            ->map(function ($p) {
                $d = \Illuminate\Support\Str::slug($p->shop?->district ?: 'khulna');
                $u = \Illuminate\Support\Str::slug($p->shop?->upazila ?: 'sonadanga');
                $s = $p->shop?->slug ?: ('shop-' . ($p->sch_id ?? 1));
                return [
                    'prod_id' => $p->prod_id,
                    'name' => $p->name,
                    'price' => $p->effective_price,
                    'shop_name' => $p->shop?->name,
                    'district' => $p->shop?->district,
                    'upazila' => $p->shop?->upazila,
                    'url' => "/{$d}/{$u}/{$s}/product/{$p->slug}",
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $product,
            'related' => $relatedProducts,
            'other_shops' => $otherShops,
        ]);
    }

    /**
     * Ultra-fast character-by-character instant product search with images and prices.
     */
    public function quickSearch(Request $request): JsonResponse
    {
        $term = trim($request->get('q', $request->get('search', '')));
        if (mb_strlen($term) < 1) {
            return response()->json([
                'status' => 'success',
                'data' => [],
                'count' => 0
            ]);
        }

        $limit = min(20, max(1, (int) $request->get('limit', 8)));

        $division = $request->query('division');
        $district = $request->query('district');
        $upazila = $request->query('upazila');

        $buildSearchQuery = function(bool $applyLocation) use ($term, $limit, $upazila, $district, $division) {
            $q = Product::active()
                ->select('products.prod_id', 'products.name', 'products.slug', 'products.selling_price', 'products.picture', 'products.demo_id', 'products.sch_id')
                ->with(['shop:sch_id,name,slug,division,district,upazila']);

            \App\Services\SearchService::applyProductSearch($q, $term);

            if ($applyLocation) {
                if ($upazila) {
                    $q->whereHas('shop', fn($sq) => $sq->where('shops.upazila', $upazila));
                } elseif ($district) {
                    $q->whereHas('shop', fn($sq) => $sq->where('shops.district', $district));
                } elseif ($division) {
                    $q->whereHas('shop', fn($sq) => $sq->where('shops.division', $division));
                }
            }

            return $q->take($limit)->get();
        };

        $products = $buildSearchQuery(true);

        // Location fallback if 0 products found locally
        if ($products->isEmpty() && ($division || $district || $upazila)) {
            $products = $buildSearchQuery(false);
        }

        // If still 0 products and multiple words were entered, try relaxed search
        if ($products->isEmpty() && count(explode(' ', $term)) > 1) {
            $relaxedQ = Product::active()
                ->select('products.prod_id', 'products.name', 'products.slug', 'products.selling_price', 'products.picture', 'products.demo_id', 'products.sch_id')
                ->with(['shop:sch_id,name,slug,division,district,upazila']);

            \App\Services\SearchService::applyRelaxedSearch($relaxedQ, $term);
            $products = $relaxedQ->take($limit)->get();
        }

        $results = [];
        foreach ($products as $p) {
            $images = $p->images_list;
            $img = !empty($images) ? $images[0] : asset('uploads/no_image.jpg');
            $results[] = [
                'id' => $p->prod_id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => (float)$p->selling_price,
                'formatted_price' => '৳' . number_format($p->selling_price, 0),
                'image' => $img,
                'shop_id' => $p->sch_id,
                'shop_name' => $p->shop?->name ?? 'Local Verified Store',
                'shop_slug' => $p->shop?->slug ?? '',
                'shop_location' => trim(($p->shop?->upazila ?? '') . ($p->shop?->district ? ', ' . $p->shop->district : ''), ', ')
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
}
