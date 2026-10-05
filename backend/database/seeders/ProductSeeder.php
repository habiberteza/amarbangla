<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/live_products.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("Live products file not found at: {$jsonPath}");
            return;
        }

        $this->command->info("Seeding all live business products from live_products.json...");
        $raw = file_get_contents($jsonPath);
        $productsData = json_decode($raw, true);

        if (empty($productsData)) {
            $this->command->error("No product records found in JSON.");
            return;
        }

        $totalRecords = count($productsData);
        $this->command->info("Found {$totalRecords} live business products to insert.");

        // Category lookup
        $categories = Category::all()->keyBy('name');
        $defaultCategory = Category::where('name', 'Grocery')->first() ?? Category::first();

        // Shop lookup
        $existingShopIds = Shop::pluck('id')->flip()->toArray();
        $fallbackShopId = Shop::first()->id ?? 1;

        // Existing product slugs to avoid collision
        $existingSlugs = [];

        $now = now();
        $productsToInsert = [];
        $imagesToInsert = [];
        $counter = 0;

        foreach ($productsData as $item) {
            $name = trim($item['name'] ?? '');
            if (empty($name)) continue;

            $origId = $item['id'] ?? null;
            $demoId = $item['demo_id'] ?? null;
            $rawPrice = (float)($item['price'] ?? 0);
            $rawOldPrice = isset($item['old_price']) ? (float)$item['old_price'] : null;

            // Normalize price
            if ($rawPrice <= 0 && $rawOldPrice > 0) {
                $price = $rawOldPrice;
                $discountPrice = null;
            } elseif ($rawPrice > 0 && $rawOldPrice > $rawPrice) {
                $price = $rawOldPrice;
                $discountPrice = $rawPrice;
            } elseif ($rawPrice > 0) {
                $price = $rawPrice;
                $discountPrice = null;
            } else {
                $price = 150.00;
                $discountPrice = null;
            }

            // Map shop
            $rawShopId = $item['shop_id'] ?? null;
            $shopId = ($rawShopId && isset($existingShopIds[$rawShopId])) ? $rawShopId : $fallbackShopId;

            // Detect category
            $catName = $this->detectCategory($name, $item['slug'] ?? '');
            $category = $categories->get($catName) ?? $defaultCategory;
            $categoryId = $category ? $category->id : null;

            // Brand detection
            $brand = $this->detectBrand($name);

            // Item code / SKU
            $itemCode = $demoId ? "DP-{$demoId}" : ($origId ? "AP-{$origId}" : 'SKU-' . rand(10000, 99999));

            // Slug
            $baseSlug = Str::slug($name);
            if (empty($baseSlug)) $baseSlug = 'product';
            $slug = $baseSlug . '-' . ($origId ?? rand(1000, 9999));
            if (isset($existingSlugs[$slug])) {
                $slug = $baseSlug . '-' . ($origId ?? rand(1000, 9999)) . '-' . rand(10, 99);
            }
            $existingSlugs[$slug] = true;

            $image = $item['image'] ?? 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg';
            $isExpress = in_array($catName, ['Grocery', 'Fast Food', 'Beverage', 'Fruits & Vegetables', 'Meat & Fish']);
            $isFeatured = ($counter % 12 === 0);

            $shortDesc = "100% authentic {$name}. Fast doorstep delivery with cash on delivery across Bangladesh.";
            $desc = "{$name}. Brand: " . ($brand ?? 'Authentic') . ". Category: {$catName}. Guaranteed original quality verified by Amar Bangla Bazar. Contact support for bulk or express order queries.";

            $productsToInsert[] = [
                'shop_id'           => $shopId,
                'category_id'       => $categoryId,
                'name'              => $name,
                'slug'              => $slug,
                'item_code'         => $itemCode,
                'brand'             => $brand,
                'short_description' => $shortDesc,
                'description'       => $desc,
                'fabrication'       => null,
                'gsm'               => null,
                'gender'            => null,
                'price'             => $price,
                'discount_price'    => $discountPrice,
                'stock_quantity'    => rand(30, 250),
                'main_image'        => $image,
                'is_express'        => $isExpress,
                'is_featured'       => $isFeatured,
                'is_active'         => true,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];

            $counter++;
        }

        // Insert in batches of 250 for speed and transaction safety
        $chunks = array_chunk($productsToInsert, 250);
        $insertedCount = 0;

        foreach ($chunks as $chunk) {
            DB::table('products')->insert($chunk);
            $insertedCount += count($chunk);
            $this->command->info("Inserted {$insertedCount} / {$counter} products...");
        }

        // Create product images for newly inserted products
        $allInserted = DB::table('products')->select('id', 'main_image')->whereNotNull('main_image')->get();
        $imageRecords = [];
        foreach ($allInserted as $p) {
            $imageRecords[] = [
                'product_id' => $p->id,
                'image_url'  => $p->main_image,
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($imageRecords, 250) as $imgChunk) {
            DB::table('product_images')->insertOrIgnore($imgChunk);
        }

        $this->command->info("Successfully inserted {$insertedCount} live business products with images!");
    }

    private function detectCategory(string $name, string $slug): string
    {
        $text = strtolower($name . ' ' . $slug);

        if (preg_match('/(baby|kid|child|diaper|pampers|toy|doll|walker|feeding bottle)/i', $text)) {
            return 'Kids Zone';
        }
        if (preg_match('/(tws|earbud|headphone|headset|charger|cable|power bank|gadget|bluetooth|smartwatch|speaker|adapter)/i', $text)) {
            return 'Gadget';
        }
        if (preg_match('/(mobile|phone|smartphone|samsung|xiaomi|realme|vivo|oppo|iphone|nokia)/i', $text)) {
            return 'Mobile';
        }
        if (preg_match('/(keyboard|mouse|usb|laptop|ram|ssd|router|computer|webcam)/i', $text)) {
            return 'Computer Accessories';
        }
        if (preg_match('/(shoe|sandal|slipper|sneaker|boot|footwear|bata|apex)/i', $text)) {
            return 'Footwear';
        }
        if (preg_match('/(t-shirt|shirt|pant|jeans|panjabi|trouser|polo|lungi|innerwear|boxer|vest|belt|wallet)/i', $text)) {
            return "Men's Clothing";
        }
        if (preg_match('/(sharee|saree|kurti|three piece|salwar|hijab|borkha|gown|lehenga|bra|panties|nighty)/i', $text)) {
            return "Women's Clothing";
        }
        if (preg_match('/(book|pen|pencil|notebook|paper|stationery|eraser|scale|sharpener)/i', $text)) {
            return 'Stationery & Books';
        }
        if (preg_match('/(tea|coffee|sprite|coke|pepsi|fanta|7up|juice|beverage|drink|water|frooto|tang|horlicks|boost)/i', $text)) {
            return 'Beverage';
        }
        if (preg_match('/(biscuit|cookies|toast|noodles|maggi|pasta|chips|chanachur|snack|cake|chocolate|wafer|kurkure)/i', $text)) {
            return 'Snacks';
        }
        if (preg_match('/(milk|dairy|butter|cheese|curd|yogurt|ghee|dano|marks|lactogen)/i', $text)) {
            return 'Dairy';
        }
        if (preg_match('/(chicken|meat|beef|mutton|fish|hilsa|prawn|egg|kebab|nugget|sausage)/i', $text)) {
            return 'Meat & Fish';
        }
        if (preg_match('/(potato|onion|garlic|ginger|tomato|vegetable|fruit|apple|orange|banana|mango|cucumber|lemon)/i', $text)) {
            return 'Fruits & Vegetables';
        }
        if (preg_match('/(burger|pizza|sandwich|fried chicken|roll|grill|biryani|kacchi|chowmein|soup)/i', $text)) {
            return 'Fast Food';
        }
        if (preg_match('/(gas|lpg|cylinder|regulator|stove)/i', $text)) {
            return 'LPG Gas Service';
        }
        if (preg_match('/(cleaner|dish wash|spray|toilet|harpic|wheel|rin|surf|detergent|soap|shampoo|paste|close up|pepsodent|dettol|savlon|bleach|vim|hit|mortein|lizol)/i', $text)) {
            return 'Grocery';
        }
        if (preg_match('/(oil|mustard|soyabean|rice|dal|flour|atta|maida|suji|sugar|salt|spice|masala|turmeric|chilli|cumin|coriander|radhuni|rupchanda|teer|pran|aci|fresh|pusti)/i', $text)) {
            return 'Grocery';
        }
        if (preg_match('/(feed|cattle|cow|goat|bird|poultry|fish feed)/i', $text)) {
            return 'Animal Feed';
        }

        return 'Grocery';
    }

    private function detectBrand(string $name): ?string
    {
        $brands = [
            'Radhuni', 'Aarong', 'Rupchanda', 'ACI Pure', 'ACI', 'Teer', 'Pran', 'Fresh', 'Pusti',
            'Fixol', 'Godrej', 'Pureit', 'Sprite', 'Coca Cola', 'Pepsi', '7up', 'Fanta', 'Ahmed',
            'Dabur', 'Harpic', 'Dettol', 'Savlon', 'Wheel', 'Rin', 'Surf Excel', 'Vim', 'Close Up',
            'Pepsodent', 'Meril', 'Tibetan', 'Dano', 'Marks', 'Maggi', 'Knorr', 'Ispahani', 'Taaza',
            'Bata', 'Apex', 'Winner fashions', 'Airdots Pro', 'Xiaomi', 'Samsung', 'Realme'
        ];

        foreach ($brands as $b) {
            if (stripos($name, $b) !== false) {
                return $b;
            }
        }

        return null;
    }
}
