<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    private function getBaseMeta(): array
    {
        $siteName = Setting::get('site_name', 'Amar Bangla Bazar');
        $tagline = Setting::get('site_tagline', "Bangladesh's Premier Local Marketplace & Shop Network");

        return [
            'site_name' => $siteName,
            'title' => "{$siteName} - {$tagline}",
            'description' => "Discover and shop from over 3,100+ verified local neighborhood shops and stores across all 64 districts in Bangladesh. Fast 2-hour express delivery, cash on delivery, and authentic products.",
            'keywords' => "Amar Bangla, Amar Bangla Bazar, local shop Bangladesh, online shopping BD, local bazar, Dhaka shops, Mirpur, Dhanmondi, Khulna, Chittagong, Sylhet, cash on delivery",
            'image' => 'https://amarbangla.com.bd/uploads/website_image/slider_1_1643173852.jpg',
            'url' => url()->current(),
            'type' => 'website',
            'json_ld' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebSite',
                        '@id' => url('/') . '/#website',
                        'url' => url('/'),
                        'name' => $siteName,
                        'description' => $tagline,
                        'potentialAction' => [
                            '@type' => 'SearchAction',
                            'target' => url('/products?search={search_term_string}'),
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                    [
                        '@type' => 'Organization',
                        '@id' => url('/') . '/#organization',
                        'name' => $siteName,
                        'url' => url('/'),
                        'logo' => 'https://amarbangla.com.bd/uploads/website_image/logo_header.png',
                        'contactPoint' => [
                            '@type' => 'ContactPoint',
                            'telephone' => Setting::get('support_phone', '+880 1714070770'),
                            'contactType' => 'customer service',
                            'areaServed' => 'BD',
                            'availableLanguage' => ['Bengali', 'English'],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function index()
    {
        $meta = $this->getBaseMeta();
        return view('frontend.app', compact('meta'));
    }

    public function shops(Request $request)
    {
        $meta = $this->getBaseMeta();
        $division = $request->query('division');
        $district = $request->query('district');
        $location = $district ? "{$district}, {$division}" : ($division ?: "Bangladesh");

        $meta['title'] = "Explore Local Shops in {$location} - Amar Bangla";
        $meta['description'] = "Find verified neighborhood stores, grocery shops, electronics dealers and local fashion outlets in {$location}. Direct call, fast delivery & cash on delivery.";
        $meta['keywords'] = "shops in {$location}, local bazar, verified merchant {$location}, local shopping BD";

        return view('frontend.app', compact('meta'));
    }

    protected function findShop(string $idOrSlug): ?Shop
    {
        if (is_numeric($idOrSlug)) {
            $shop = Shop::where('sch_id', $idOrSlug)->first();
            if ($shop) return $shop;
        }
        $clean = str_replace('-', ' ', $idOrSlug);
        return Shop::where('name', 'LIKE', '%' . $clean . '%')->first()
            ?? Shop::where('name', 'LIKE', '%' . str_replace('-', '%', $idOrSlug) . '%')->first();
    }

    protected function findProduct(string $idOrSlug, $shopId = null): ?Product
    {
        $query = Product::query();
        if ($shopId) {
            $query->where('sch_id', $shopId);
        }

        if (is_numeric($idOrSlug)) {
            $prod = (clone $query)->where('prod_id', $idOrSlug)->first();
            if ($prod) return $prod;
        }

        // 1. Direct indexed slug match (scoped to shop first, then global)
        $prod = (clone $query)->where('slug', $idOrSlug)->first();
        if ($prod) return $prod;

        if ($shopId) {
            $prod = Product::where('slug', $idOrSlug)->first();
            if ($prod) return $prod;
        }

        // 2. Name search
        $clean = str_replace('-', ' ', $idOrSlug);
        $prod = (clone $query)->where('name', 'LIKE', '%' . $clean . '%')->first();
        if ($prod) return $prod;

        return (clone $query)->where('name', 'LIKE', '%' . str_replace('-', '%', $idOrSlug) . '%')->first();
    }

    public function shop(string $idOrSlug)
    {
        $shop = $this->findShop($idOrSlug);

        $meta = $this->getBaseMeta();

        if ($shop) {
            $shopTitle = $shop->seo_title ?: "{$shop->name} - Local Store in {$shop->upazila}, {$shop->district} | Amar Bangla";
            $shopDesc = $shop->seo_description ?: "Buy directly from {$shop->name} at {$shop->address}, {$shop->upazila}, {$shop->district}. 100% genuine local products with cash on delivery.";
            $shopKeywords = $shop->seo_keywords ?: "{$shop->name}, {$shop->upazila}, {$shop->district}, {$shop->division}, local shop BD";

            $meta['title'] = $shopTitle;
            $meta['description'] = $shopDesc;
            $meta['keywords'] = $shopKeywords;
            $meta['image'] = $shop->banner ?: ($shop->logo ?: $meta['image']);
            $meta['type'] = 'profile';

            // Add Store / LocalBusiness Schema
            $meta['json_ld']['@graph'][] = [
                '@type' => 'Store',
                '@id' => url("/shops/{$shop->slug}") . '#store',
                'name' => $shop->name,
                'image' => $shop->banner ?: $shop->logo,
                'telephone' => $shop->phone,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $shop->address,
                    'addressLocality' => $shop->upazila ?: $shop->district,
                    'addressRegion' => $shop->division,
                    'addressCountry' => 'BD',
                ],
                'priceRange' => '৳৳',
                'url' => url("/shops/{$shop->slug}"),
            ];
        }

        return view('frontend.app', compact('meta'));
    }

    public function products(Request $request)
    {
        $meta = $this->getBaseMeta();
        $search = $request->query('search');

        if ($search) {
            $meta['title'] = "Search results for '{$search}' - Amar Bangla";
            $meta['description'] = "Buy {$search} from local shops in Bangladesh at best prices. Fast express delivery with cash on delivery.";
        } else {
            $meta['title'] = "Browse Products from Local Stores - Amar Bangla";
            $meta['description'] = "Browse groceries, fashion, electronics, organic food and handicrafts directly from verified local shops across Bangladesh.";
        }

        return view('frontend.app', compact('meta'));
    }

    public function product(string $idOrSlug)
    {
        $product = $this->findProduct($idOrSlug);

        if ($product) {
            $districtName = $product->shop?->district ?: 'Khulna';
            $upazilaName = $product->shop?->upazila ?: 'Sonadanga';
            $districtSlug = Str::slug($districtName);
            $upazilaSlug = Str::slug($upazilaName);
            $shopSlug = $product->shop?->slug ?: ('shop-' . ($product->shop?->sch_id ?? 1));
            
            // Redirect 301 permanently to the 2nd format (District / Upazila / Shop / Product)
            $canonicalUrl = url("/{$districtSlug}/{$upazilaSlug}/{$shopSlug}/product/{$product->slug}");
            return redirect()->to($canonicalUrl, 301);
        }

        abort(404, 'Product not found');
    }

    /**
     * Hyper-local duplicate product SEO handler with District and Upazila:
     * /{district}/{upazila}/{shopSlug}/product/{productSlug}
     */
    public function hierarchicalProductDistrictUpazila(string $district, string $upazila, string $shopSlug, string $productSlug)
    {
        $shop = $this->findShop($shopSlug);
        $product = $this->findProduct($productSlug, $shop?->sch_id);

        $division = $shop?->division ?? 'Bangladesh';
        $actualDistrict = $shop?->district ?? str_replace('-', ' ', ucwords($district));
        $actualUpazila = $shop?->upazila ?? str_replace('-', ' ', ucwords($upazila));

        return $this->renderSeoProductView($product, $shop, $division, $actualDistrict, $actualUpazila);
    }

    /**
     * Hyper-local duplicate product SEO handler with Division, District & Upazila:
     * /{division}/{district}/{upazila}/{shopSlug}/product/{productSlug}
     */
    public function hierarchicalProductFull(string $division, string $district, string $upazila, string $shopSlug, string $productSlug)
    {
        $shop = $this->findShop($shopSlug);
        $product = $this->findProduct($productSlug, $shop?->sch_id);

        $actualDivision = $shop?->division ?? str_replace('-', ' ', ucwords($division));
        $actualDistrict = $shop?->district ?? str_replace('-', ' ', ucwords($district));
        $actualUpazila = $shop?->upazila ?? str_replace('-', ' ', ucwords($upazila));

        return $this->renderSeoProductView($product, $shop, $actualDivision, $actualDistrict, $actualUpazila);
    }

    /**
     * Hyper-local duplicate product SEO handler:
     * /{division}/{district}/{shopSlug}/product/{productSlug}
     */
    public function hierarchicalProduct(string $division, string $district, string $shopSlug, string $productSlug)
    {
        $shop = $this->findShop($shopSlug);
        $product = $this->findProduct($productSlug, $shop?->sch_id);

        $actualDivision = $shop?->division ?? $division;
        $actualDistrict = $shop?->district ?? $district;
        $actualUpazila = $shop?->upazila ?? null;

        return $this->renderSeoProductView($product, $shop, $actualDivision, $actualDistrict, $actualUpazila);
    }

    /**
     * Hyper-local Shop SEO handler with District & Upazila:
     * /{district}/{upazila}/{shopSlug}
     */
    public function hierarchicalShopDistrictUpazila(string $district, string $upazila, string $shopSlug)
    {
        $shop = $this->findShop($shopSlug);
        $meta = $this->getBaseMeta();

        if ($shop) {
            $actualDistrict = $shop->district ?? str_replace('-', ' ', ucwords($district));
            $actualUpazila = $shop->upazila ?? str_replace('-', ' ', ucwords($upazila));
            $dLower = strtolower($actualDistrict);
            $uLower = strtolower($actualUpazila);

            $meta['title'] = "{$shop->name} - {$actualDistrict} - {$actualUpazila} - First Delivery Service | Amar Bangla";
            $meta['description'] = "{$shop->name} - {$dLower} - {$uLower} - first delivery service . Buy genuine local products directly from {$shop->name} with express delivery and cash on delivery in {$actualUpazila}, {$actualDistrict}.";
            $meta['keywords'] = "{$shop->name}, {$dLower}, {$uLower}, first delivery service, verified merchant, local shop BD";
            $meta['image'] = $shop->banner ?: ($shop->logo ?: $meta['image']);
            $meta['type'] = 'profile';

            $meta['json_ld']['@graph'][] = [
                '@type' => 'Store',
                '@id' => url()->current() . '#store',
                'name' => $shop->name,
                'image' => $shop->banner ?: $shop->logo,
                'telephone' => $shop->phone,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $shop->address,
                    'addressLocality' => $actualUpazila,
                    'addressRegion' => $actualDistrict,
                    'addressCountry' => 'BD',
                ],
                'priceRange' => '৳৳',
                'url' => url()->current(),
            ];
        }

        return view('frontend.app', compact('meta'));
    }

    /**
     * Shop-scoped SEO product handler:
     * /shop/{shopSlug}/{productSlug}
     */
    public function shopScopedProduct(string $shopSlug, string $productSlug)
    {
        $shop = $this->findShop($shopSlug);
        $product = $this->findProduct($productSlug, $shop?->sch_id);

        $division = $shop?->division ?? 'Bangladesh';
        $district = $shop?->district ?? 'Khulna';
        $upazila = $shop?->upazila ?? 'Sonadanga';

        return $this->renderSeoProductView($product, $shop, $division, $district, $upazila);
    }

    private function renderSeoProductView(?Product $product, ?Shop $shop, string $division, string $district, ?string $upazila = null)
    {
        $meta = $this->getBaseMeta();

        if ($product) {
            $shopName = $shop ? $shop->name : ($product->shop ? $product->shop->name : 'Amar Bangla Store');
            $locationParts = array_filter([$upazila, $district, $division]);
            $locationLabel = implode(', ', $locationParts);
            $shopAddress = $shop ? $shop->full_address : ($product->shop?->full_address ?? $locationLabel);
            $effectivePrice = $product->discount_price ?? $product->price;

            $dLower = strtolower($district ?: ($shop?->district ?: 'khulna'));
            $uLower = strtolower($upazila ?: ($shop?->upazila ?: 'sonadanga'));

            $meta['title'] = "{$product->name} - {$district} - " . ($upazila ? "{$upazila} - " : "") . "First Delivery Service | {$shopName}";
            $meta['description'] = "{$product->name} - {$dLower} - {$uLower} - first delivery service . Order genuine {$product->name} online from {$shopName} at ৳{$effectivePrice} with fast express delivery & cash on delivery in {$uLower}, {$dLower}.";
            $meta['keywords'] = "{$product->name}, {$dLower}, {$uLower}, first delivery service, {$shopName}, local shop online BD";
            $meta['image'] = $product->main_image ?: $meta['image'];
            $meta['type'] = 'product';
            $meta['url'] = url()->current();

            // Rich Structured Data graph
            $canonicalUrl = url()->current();
            $breadcrumbElements = [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $division, 'item' => url("/shops?division=" . urlencode($division))],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $district, 'item' => url("/shops?district=" . urlencode($district))],
            ];
            $pos = 4;
            if ($upazila) {
                $breadcrumbElements[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $upazila, 'item' => url("/shops?district=" . urlencode($district) . "&upazila=" . urlencode($upazila))];
            }
            $breadcrumbElements[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $shopName, 'item' => url("/shops/" . ($shop?->slug ?? 'shop'))];
            $breadcrumbElements[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $product->name, 'item' => $canonicalUrl];

            $meta['json_ld']['@graph'][] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $breadcrumbElements,
            ];

            $meta['json_ld']['@graph'][] = [
                '@type' => 'Product',
                '@id' => $canonicalUrl . '#product',
                'name' => $product->name,
                'image' => $product->main_image,
                'description' => $meta['description'],
                'sku' => $product->item_code ?? 'ABP-' . $product->id,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $product->brand ?: $shopName,
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => $canonicalUrl,
                    'price' => number_format((float)$effectivePrice, 2, '.', ''),
                    'priceCurrency' => 'BDT',
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'seller' => [
                        '@type' => 'Store',
                        'name' => $shopName,
                        'telephone' => $shop?->phone ?? '+8801714070770',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => $shop?->address ?? $district,
                            'addressLocality' => $upazila ?: $district,
                            'addressRegion' => $district,
                            'addressCountry' => 'BD',
                        ],
                        'geo' => [
                            '@type' => 'GeoCoordinates',
                            'latitude' => '23.8103',
                            'longitude' => '90.4125',
                        ],
                    ],
                ],
            ];
        }

        return view('frontend.app', compact('meta'));
    }

    public function category(string $idOrSlug)
    {
        $category = Category::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->first();

        $meta = $this->getBaseMeta();

        if ($category) {
            $meta['title'] = "{$category->name} - Buy from Local Stores | Amar Bangla";
            $meta['description'] = "Shop top quality {$category->name} products supplied by verified local neighborhood stores across Bangladesh.";
            $meta['keywords'] = "{$category->name}, {$category->name} online, buy {$category->name} Bangladesh";
            if ($category->image) {
                $meta['image'] = $category->image;
            }
        }

        return view('frontend.app', compact('meta'));
    }

    public function catchAll()
    {
        $meta = $this->getBaseMeta();
        $path = request()->path();

        if (Str::startsWith($path, 'cart')) {
            $meta['title'] = "My Shopping Cart - Amar Bangla";
        } elseif (Str::startsWith($path, 'checkout')) {
            $meta['title'] = "Secure Checkout - Amar Bangla";
        } elseif (Str::startsWith($path, 'track-order')) {
            $meta['title'] = "Track Your Order - Amar Bangla";
        } elseif (Str::startsWith($path, 'login')) {
            $meta['title'] = "Customer Login - Amar Bangla";
        } elseif (Str::startsWith($path, 'register')) {
            $meta['title'] = "Customer Registration - Amar Bangla";
        }

        return view('frontend.app', compact('meta'));
    }

    public function sitemap(): Response
    {
        $shops = Shop::whereNull('deleted')->take(500)->get();
        $products = Product::where('status', '1')->whereNull('deleted')->with('shop')->take(500)->get();
        $categories = Category::take(100)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static routes
        $staticRoutes = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => url('/shops'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('/products'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('/categories'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => url('/track-order'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $r) {
            $xml .= "<url><loc>{$r['loc']}</loc><changefreq>{$r['changefreq']}</changefreq><priority>{$r['priority']}</priority></url>";
        }

        foreach ($shops as $s) {
            $d = Str::slug($s->district ?: 'khulna');
            $u = Str::slug($s->upazila ?: 'sonadanga');
            $loc = url("/{$d}/{$u}/{$s->slug}");
            $xml .= "<url><loc>{$loc}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>";
        }

        foreach ($products as $p) {
            $d = Str::slug($p->shop?->district ?: 'khulna');
            $u = Str::slug($p->shop?->upazila ?: 'sonadanga');
            $shopSlug = $p->shop?->slug ?: ('shop-' . ($p->sch_id ?? 1));
            $loc = url("/{$d}/{$u}/{$shopSlug}/product/{$p->slug}");
            $xml .= "<url><loc>{$loc}</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>";
        }

        foreach ($categories as $c) {
            $loc = url("/categories/{$c->slug}");
            $xml .= "<url><loc>{$loc}</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $sitemapUrl = url('/sitemap.xml');
        $content = "User-agent: *\nAllow: /\nDisallow: /super_admin/\nDisallow: /api/\n\nSitemap: {$sitemapUrl}\n";
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
