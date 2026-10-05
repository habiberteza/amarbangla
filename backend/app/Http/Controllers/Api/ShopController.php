<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalAddress;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Hyper-local shop listing with 4-level smart fallback:
     * Level 1: Ward -> Level 2: Pourashava/Union -> Level 3: Upazila -> Level 4: District
     */
    public function index(Request $request): JsonResponse
    {
        $baseQuery = Shop::where('shops.status', '1')->whereNull('shops.deleted');

        if ($request->filled('type')) {
            if ($request->type === 'food') {
                $baseQuery->where(function($q) {
                    $q->whereIn('shop_cat_id', [1, 2, 3, 4, 5, 19])
                      ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%food%'")
                      ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%sweet%'")
                      ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%মিষ্টি%'")
                      ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%হোটেল%'");
                });
            } else {
                $baseQuery->where('type', $request->type);
            }
        }

        if ($request->filled('shop_type')) {
            $baseQuery->where('shop_type', $request->shop_type);
        }

        if ($request->filled('express')) {
            $baseQuery->where(function ($q) {
                $q->where('type', 'express')
                  ->orWhere('priority_type', 'express')
                  ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE '%express%'")
                  ->orWhereIn('shop_cat_id', [1, 2, 4, 18, 19]);
            });
        }

        if ($request->filled('shop_cat_id') || $request->filled('category_id')) {
            $catId = (int)$request->input('shop_cat_id', $request->input('category_id'));
            $childCatIds = \App\Models\ShopCategory::where('parent_cat_id', $catId)->pluck('shop_cat_id')->toArray();
            $allShopCatIds = array_merge([$catId], $childCatIds);

            $baseQuery->where(function ($q) use ($allShopCatIds, $catId) {
                $q->whereIn('shop_cat_id', $allShopCatIds)
                  ->orWhere('category_id', $catId);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('house_address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $fallbackLevel = null;
        $fallbackApplied = false;
        $fallbackMessage = null;

        $hasLocationFilter = $request->filled('ward') || $request->filled('pourashava') ||
                             $request->filled('upazila') || $request->filled('district') ||
                             $request->filled('division');

        if ($hasLocationFilter) {
            $allDistricts = districtView();
            $allUpazilas = upazilasView();

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
                foreach ($divisions as $div) {
                    if (strcasecmp($div['name'], $request->division) === 0 || $div['id'] == $request->division) {
                        $divId = $div['id'];
                        $divName = $div['name'];
                        break;
                    }
                }
            }

            // Upazila match (Level 3)
            if ($upzName || $upzId) {
                $baseQuery->where(function ($q) use ($upzName, $upzId) {
                    if ($upzName) {
                        $q->where('shops.upazila', $upzName);
                    }
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
                $fallbackLevel = 'upazila';
            } elseif ($dstName || $dstId) {
                // District match (Level 4)
                $baseQuery->where(function ($q) use ($dstName, $dstId) {
                    if ($dstName) {
                        $q->where('shops.district', $dstName);
                    }
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
                $fallbackLevel = 'district';
            } elseif ($divName || $divId) {
                // Division match
                $baseQuery->where(function ($q) use ($divName, $divId) {
                    if ($divName) {
                        $q->where('shops.division', $divName);
                    }
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
                $fallbackLevel = 'division';
            }

            // Pourashava match (Level 2)
            if ($request->filled('pourashava')) {
                $pVal = trim($request->pourashava);
                $pAliases = match ($pVal) {
                    '1', 'Pourashava' => ['1', 'Pourashava'],
                    '2', 'Union' => ['2', 'Union'],
                    '0', 'City Corporation' => ['0', 'City Corporation'],
                    default => [$pVal],
                };
                $baseQuery->where(function ($q) use ($pAliases) {
                    $q->whereIn('shops.pourashava', $pAliases)
                      ->orWhereExists(function ($sub) use ($pAliases) {
                          $sub->select(DB::raw(1))
                              ->from('global_address')
                              ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                              ->whereIn('global_address.pourashava', $pAliases);
                      });
                });
                $fallbackLevel = 'pourashava';
            }

            // Ward match (Level 1)
            if ($request->filled('ward')) {
                $wVal = trim($request->ward);
                $wNum = preg_replace('/[^0-9]/', '', $wVal);
                $wOptions = array_values(array_filter([$wVal, $wNum, $wNum ? "Ward $wNum" : null]));
                $baseQuery->where(function ($q) use ($wOptions) {
                    $q->whereIn('shops.ward', $wOptions)
                      ->orWhereExists(function ($sub) use ($wOptions) {
                          $sub->select(DB::raw(1))
                              ->from('global_address')
                              ->whereColumn('global_address.global_address_id', 'shops.global_address_id')
                              ->whereIn('global_address.ward', $wOptions);
                      });
                });
                $fallbackLevel = 'ward';
            }
        }

        $activeQuery = $baseQuery;

        $shops = $activeQuery->withCount('products')
            ->orderByDesc('home_feature')
            ->orderBy('name')
            ->paginate($request->get('per_page', 12));

        return response()->json([
            'status' => 'success',
            'meta' => [
                'fallback_level'    => $fallbackLevel ?? 'all',
                'fallback_applied'  => $fallbackApplied,
                'fallback_message'  => $fallbackMessage,
                'requested_ward'    => $request->ward,
                'requested_poura'   => $request->pourashava,
                'requested_upazila' => $request->upazila,
                'requested_district'=> $request->district,
            ],
            'data' => $shops,
        ]);
    }

    public function show(string $idOrSlug): JsonResponse
    {
        $shop = null;
        if (is_numeric($idOrSlug)) {
            $shop = Shop::where('sch_id', $idOrSlug)->first();
        }
        if (!$shop) {
            $shop = Shop::where('slug', $idOrSlug)->first();
        }
        if (!$shop) {
            $clean = str_replace('-', ' ', $idOrSlug);
            $shop = Shop::where('slug', 'like', "%{$idOrSlug}%")
                ->orWhereRaw("CONVERT(name USING utf8mb4) LIKE ?", ["%{$clean}%"])
                ->first();
        }
        if (!$shop) {
            $all = Shop::where('status', '1')->whereNull('deleted')->get();
            foreach ($all as $candidate) {
                if ($candidate->slug === $idOrSlug || \Illuminate\Support\Str::slug($candidate->name) === $idOrSlug) {
                    $shop = $candidate;
                    break;
                }
            }
        }

        if (!$shop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop not found'
            ], 404);
        }

        $shop->load(['products' => function ($query) {
            $query->where('status', '1')->whereNull('deleted')->orderByDesc('prod_id')->take(60);
        }]);

        return response()->json([
            'status' => 'success',
            'data' => $shop,
        ]);
    }

    /**
     * High-speed cached list of existing shop divisions, districts, and upazilas.
     * Contains ALL 8 divisions, 64 districts, and 501+ upazilas of Bangladesh,
     * plus any specific zones from shops & global addresses.
     */
    public function locations(): JsonResponse
    {
        $locations = Cache::remember('api_shop_locations_v5', 3600, function () {
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

            $allDistricts = districtView();
            $allUpazilas = upazilasView();

            $tree = [];
            $divMap = [];
            foreach ($divisions as $d) {
                $tree[$d['name']] = [];
                $divMap[$d['id']] = $d['name'];
            }

            $distMap = [];
            foreach ($allDistricts as $dst) {
                $divName = $divMap[$dst['division_id']] ?? null;
                if ($divName) {
                    $tree[$divName][$dst['name']] = [];
                    $distMap[$dst['id']] = ['div' => $divName, 'dist' => $dst['name']];
                }
            }

            foreach ($allUpazilas as $upz) {
                $distInfo = $distMap[$upz['district_id']] ?? null;
                if ($distInfo) {
                    $divName = $distInfo['div'];
                    $dstName = $distInfo['dist'];
                    $tree[$divName][$dstName][] = $upz['name'];
                }
            }

            // Also merge any custom upazilas/zones from shops
            $shopLocations = DB::table('shops')
                ->where('shops.status', '1')
                ->whereNull('shops.deleted')
                ->whereNotNull('division')
                ->where('division', '!=', '')
                ->whereNotNull('district')
                ->where('district', '!=', '')
                ->whereNotNull('upazila')
                ->where('upazila', '!=', '')
                ->select('division', 'district', 'upazila')
                ->distinct()
                ->get();

            foreach ($shopLocations as $sl) {
                $sDiv = trim($sl->division);
                $sDst = trim($sl->district);
                $sUpz = trim($sl->upazila);

                if (is_numeric($sDiv)) $sDiv = divisionname($sDiv);
                if (is_numeric($sDst)) $sDst = districtname($sDst);
                if (is_numeric($sUpz)) $sUpz = upazilaname($sUpz);

                if ($sDiv && $sDst && $sUpz) {
                    if (!isset($tree[$sDiv])) $tree[$sDiv] = [];
                    if (!isset($tree[$sDiv][$sDst])) $tree[$sDiv][$sDst] = [];
                    if (!in_array($sUpz, $tree[$sDiv][$sDst])) {
                        $tree[$sDiv][$sDst][] = $sUpz;
                    }
                }
            }

            // Sort everything cleanly
            foreach ($tree as $dName => &$districts) {
                foreach ($districts as $dstName => &$upazilas) {
                    sort($upazilas);
                }
                ksort($districts);
            }
            ksort($tree);

            return $tree;
        });

        return response()->json([
            'status' => 'success',
            'data' => $locations,
        ]);
    }

    /**
     * Dynamic Centralized Geo Cascading API from Global Addresses
     * Hierarchy: Division -> District -> Upazila -> Pourashava/Union -> Ward
     */
    public function geoCascade(Request $request): JsonResponse
    {
        $division = $request->query('division');
        $district = $request->query('district');
        $upazila  = $request->query('upazila');

        $cacheKey = 'geo_cascade_v3_' . md5("{$division}_{$district}_{$upazila}");

        $result = Cache::remember($cacheKey, 7200, function () use ($division, $district, $upazila) {
            $divisions = [
                ['id' => '1', 'name' => 'Chattagram', 'bn_name' => 'চট্টগ্রাম'],
                ['id' => '2', 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী'],
                ['id' => '3', 'name' => 'Khulna', 'bn_name' => 'খুলনা'],
                ['id' => '4', 'name' => 'Barisal', 'bn_name' => 'বরিশাল'],
                ['id' => '5', 'name' => 'Sylhet', 'bn_name' => 'সিলেট'],
                ['id' => '6', 'name' => 'Dhaka', 'bn_name' => 'ঢাকা'],
                ['id' => '7', 'name' => 'Rangpur', 'bn_name' => 'রংপুর'],
                ['id' => '8', 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ']
            ];

            $allDistricts = districtView();
            $allUpazilas = upazilasView();

            $divId = null;
            if ($division) {
                foreach ($divisions as $d) {
                    if (strcasecmp($d['name'], $division) === 0 || $d['id'] == $division) {
                        $divId = $d['id'];
                        break;
                    }
                }
            }

            $districts = [];
            $dstId = null;
            foreach ($allDistricts as $dst) {
                if (!$divId || $dst['division_id'] == $divId) {
                    $districts[] = $dst['name'];
                }
                if ($district && (strcasecmp($dst['name'], $district) === 0 || $dst['id'] == $district)) {
                    $dstId = $dst['id'];
                }
            }

            $upazilas = [];
            if ($dstId) {
                foreach ($allUpazilas as $upz) {
                    if ($upz['district_id'] == $dstId) {
                        $upazilas[] = $upz['name'];
                    }
                }
            }

            // Also check shops and global_address for any additional upazilas under this district
            if ($district) {
                $extraShopUpz = DB::table('shops')
                    ->where(function ($q) use ($district, $dstId) {
                        $q->where('district', $district);
                        if ($dstId) $q->orWhere('district', (string)$dstId);
                    })
                    ->whereNotNull('upazila')
                    ->where('upazila', '!=', '')
                    ->pluck('upazila')
                    ->toArray();

                foreach ($extraShopUpz as $eu) {
                    $name = is_numeric($eu) ? upazilaname($eu) : $eu;
                    if ($name && !in_array($name, $upazilas)) {
                        $upazilas[] = $name;
                    }
                }
            }

            sort($upazilas);

            return [
                'divisions'   => array_column($divisions, 'name'),
                'districts'   => array_values(array_unique($districts)),
                'upazilas'    => array_values(array_unique($upazilas)),
                'pourashavas' => ['City Corporation', 'Pourashava', 'Union'],
                'wards'       => array_map(fn($w) => "Ward $w", range(1, 65))
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}

