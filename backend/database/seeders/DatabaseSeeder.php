<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin & Vendor Users
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@amarbangla.com.bd',
            'phone' => '01714070770',
            'role' => 'super_admin',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $vendor1 = User::create([
            'name' => 'Amar Bangla Official Merchant',
            'email' => 'merchant@amarbangla.com.bd',
            'phone' => '01711223344',
            'role' => 'vendor',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $vendor2 = User::create([
            'name' => 'Express Mart Merchant',
            'email' => 'express@amarbangla.com.bd',
            'phone' => '01799887766',
            'role' => 'vendor',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $customer = User::create([
            'name' => 'Rahim Ahmed',
            'email' => 'customer@example.com',
            'phone' => '01812345678',
            'role' => 'customer',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        // 2. Settings
        $settings = [
            'site_name' => 'Amer Bangla Bazar',
            'site_tagline' => "Bangladesh's local online shopping store",
            'support_phone' => '+880 1714070770',
            'support_email' => 'suggestionbd@gmail.com',
            'meta_pixel' => '754148889720140',
            'currency' => '৳',
            'currency_code' => 'BDT',
            'address' => 'Dhaka, Khulna & All across Bangladesh',
            'facebook_url' => 'https://facebook.com/amarbangla',
            'delivery_charge_inside_dhaka' => '60',
            'delivery_charge_outside_dhaka' => '120',
        ];
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 3. Categories & Subcategories
        $categoriesData = [
            [
                'name' => 'FOOD & GROCERY',
                'icon' => 'utensils',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80',
                'is_featured' => true,
                'children' => [
                    'Grocery',
                    'Meat & Fish',
                    'Fruits & Vegetables',
                    'Snacks',
                    'Beverage',
                    'Dairy',
                    'Fast Food',
                ],
            ],
            [
                'name' => 'ELECTRONICS',
                'icon' => 'smartphone',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=500&q=80',
                'is_featured' => true,
                'children' => [
                    'Mobile',
                    'Gadget',
                    'Computer Accessories',
                ],
            ],
            [
                'name' => 'Fashion',
                'icon' => 'shirt',
                'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=500&q=80',
                'is_featured' => true,
                'children' => [
                    "Men's Clothing",
                    "Women's Clothing",
                    'Kids Zone',
                    'Footwear',
                ],
            ],
            [
                'name' => 'Education',
                'icon' => 'book-open',
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=500&q=80',
                'is_featured' => false,
                'children' => [
                    'Home Tutor',
                    'Stationery & Books',
                ],
            ],
            [
                'name' => 'Services',
                'icon' => 'wrench',
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=500&q=80',
                'is_featured' => false,
                'children' => [
                    'Laundry Service',
                    'LPG Gas Service',
                    'Mistri Seba',
                    'Water Service',
                    'Rent a Car',
                ],
            ],
            [
                'name' => 'Dairy Farm',
                'icon' => 'milk',
                'image' => 'https://images.unsplash.com/photo-1527153857715-3908f2ae5e81?w=500&q=80',
                'is_featured' => false,
                'children' => [
                    'Birds Food',
                    'Animal Feed',
                ],
            ],
            [
                'name' => 'Internet Service',
                'icon' => 'wifi',
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=500&q=80',
                'is_featured' => false,
                'children' => [
                    'Wi-Fi Connection',
                    'Broadband Plans',
                ],
            ],
        ];

        $categoryMap = [];
        $sort = 1;
        foreach ($categoriesData as $cat) {
            $parent = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'image' => $cat['image'],
                'sort_order' => $sort++,
                'is_active' => true,
                'is_featured' => $cat['is_featured'],
            ]);
            $categoryMap[$cat['name']] = $parent;

            foreach ($cat['children'] as $childName) {
                $child = Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'icon' => $cat['icon'],
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => false,
                ]);
                $categoryMap[$childName] = $child;
            }
        }

        // 4. Shops
        $shop1 = Shop::create([
            'user_id' => $vendor1->id,
            'name' => 'Amar Bangla Shop',
            'slug' => 'amar-bangla-shop',
            'type' => 'regular',
            'logo' => 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg',
            'banner' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80',
            'phone' => '+880 1714070770',
            'email' => 'shop@amarbangla.com.bd',
            'address' => 'House 14, Road 5, Dhanmondi, Dhaka',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Dhanmondi',
            'commission_rate' => 5.00,
            'rating' => 4.9,
            'is_featured' => true,
            'status' => 'active',
            'description' => 'Official verified marketplace outlet for high quality fashion, apparel, and daily essentials across Bangladesh.',
        ]);

        $shop2 = Shop::create([
            'user_id' => $vendor2->id,
            'name' => 'Amar Bangla Express Store',
            'slug' => 'amar-bangla-express-store',
            'type' => 'express',
            'logo' => 'https://amarbangla.com.bd/uploads/schools/logo_1643864917.jpg',
            'banner' => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1200&q=80',
            'phone' => '+880 1799887766',
            'email' => 'express@amarbangla.com.bd',
            'address' => 'Plot 8, Mirpur-10, Dhaka',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Mirpur',
            'commission_rate' => 7.00,
            'rating' => 4.8,
            'is_featured' => true,
            'status' => 'active',
            'description' => 'Fast 2-hour super express delivery store for groceries, meat, fish, fresh vegetables and essential beverages.',
        ]);

        $shop3 = Shop::create([
            'user_id' => $vendor1->id,
            'name' => 'Amar Food Corner',
            'slug' => 'amar-food-corner',
            'type' => 'food',
            'logo' => 'https://amarbangla.com.bd/uploads/schools/logo_1643864912.jpg',
            'banner' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80',
            'phone' => '+880 1755443322',
            'email' => 'food@amarbangla.com.bd',
            'address' => 'Golam Kibria Road, Sonadanga, Khulna',
            'division' => 'Khulna',
            'district' => 'Khulna',
            'upazila' => 'Sonadanga',
            'commission_rate' => 10.00,
            'rating' => 4.7,
            'is_featured' => true,
            'status' => 'active',
            'description' => 'Fresh home-made and restaurant dishes delivered hot to your doorstep with cash on delivery.',
        ]);

        $shop4 = Shop::create([
            'user_id' => $vendor2->id,
            'name' => 'Khulna Local Electronics & Gadget',
            'slug' => 'khulna-local-electronics',
            'type' => 'local',
            'logo' => 'https://amarbangla.com.bd/uploads/schools/logo_1643864897.jpg',
            'banner' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=1200&q=80',
            'phone' => '+880 1733221100',
            'email' => 'gadgets@amarbangla.com.bd',
            'address' => 'Dakbangla Mor, Khulna Sadar',
            'division' => 'Khulna',
            'district' => 'Khulna',
            'upazila' => 'Khulna Sadar',
            'commission_rate' => 4.50,
            'rating' => 4.6,
            'is_featured' => false,
            'status' => 'active',
            'description' => 'Neighborhood certified electronics, mobile accessories, chargers, cables and sound systems.',
        ]);

        // 5. Products & Variants
        $productsData = [
            [
                'shop' => $shop1,
                'category' => $categoryMap["Men's Clothing"] ?? null,
                'name' => "Men's T-shirt white amar bangla",
                'slug' => 'mens-t-shirt-white-amar-bangla',
                'item_code' => '55220',
                'brand' => 'Winner fashions',
                'short_description' => 'Premium 95% cotton 5% elastic single jersey breathable t-shirt for daily comfort.',
                'description' => "Winner Men's T-shirt. Item Code: 55220. Brand: Winner fashions. Gender: Men. Fabrication: 95% cotton 5% elastic s/j. GSM-160-170. Size: M, L, XL. Color: White. Soft texture, premium stitching, fade resistant fabric.",
                'fabrication' => '95% cotton 5% elastic s/j',
                'gsm' => '160-170',
                'gender' => 'Men',
                'price' => 380.00,
                'discount_price' => 320.00,
                'stock_quantity' => 150,
                'main_image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&q=80',
                'is_express' => false,
                'is_featured' => true,
                'variants' => [
                    ['type' => 'Size', 'value' => 'M', 'price_adjustment' => 0, 'stock' => 50],
                    ['type' => 'Size', 'value' => 'L', 'price_adjustment' => 0, 'stock' => 50],
                    ['type' => 'Size', 'value' => 'XL', 'price_adjustment' => 20, 'stock' => 50],
                    ['type' => 'Color', 'value' => 'White', 'price_adjustment' => 0, 'stock' => 150],
                ],
            ],
            [
                'shop' => $shop1,
                'category' => $categoryMap["Men's Clothing"] ?? null,
                'name' => "Men's T-shirt sky blue amar bangla",
                'slug' => 'mens-t-shirt-sky-blue-amar-bangla',
                'item_code' => '55300',
                'brand' => 'Winner fashions',
                'short_description' => 'Light and refreshing sky blue men cotton crewneck t-shirt.',
                'description' => "Winner Men's T-shirt. Item Code: 55300. Brand: Winner fashions. Gender: Men. Fabrication: 95% cotton 5% elastic s/j. GSM-160-170. Size: M, L, XL. Color: Sky Blue.",
                'fabrication' => '95% cotton 5% elastic s/j',
                'gsm' => '160-170',
                'gender' => 'Men',
                'price' => 380.00,
                'discount_price' => 320.00,
                'stock_quantity' => 120,
                'main_image' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&q=80',
                'is_express' => false,
                'is_featured' => true,
                'variants' => [
                    ['type' => 'Size', 'value' => 'M', 'price_adjustment' => 0, 'stock' => 40],
                    ['type' => 'Size', 'value' => 'L', 'price_adjustment' => 0, 'stock' => 40],
                    ['type' => 'Size', 'value' => 'XL', 'price_adjustment' => 20, 'stock' => 40],
                ],
            ],
            [
                'shop' => $shop1,
                'category' => $categoryMap["Men's Clothing"] ?? null,
                'name' => "Men's T-shirt printed amar bangla",
                'slug' => 'mens-t-shirt-printed-amar-bangla',
                'item_code' => '55400',
                'brand' => 'Winner fashions',
                'short_description' => 'Contemporary graphic printed cotton t-shirt with premium finish.',
                'description' => "Winner Men's Printed T-shirt. Item Code: 55400. High quality rubberized print that won't crack or peel after multiple washes.",
                'fabrication' => '100% combed cotton',
                'gsm' => '175',
                'gender' => 'Men',
                'price' => 420.00,
                'discount_price' => 360.00,
                'stock_quantity' => 90,
                'main_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&q=80',
                'is_express' => false,
                'is_featured' => true,
                'variants' => [
                    ['type' => 'Size', 'value' => 'M', 'price_adjustment' => 0, 'stock' => 30],
                    ['type' => 'Size', 'value' => 'L', 'price_adjustment' => 0, 'stock' => 30],
                    ['type' => 'Size', 'value' => 'XL', 'price_adjustment' => 0, 'stock' => 30],
                ],
            ],
            [
                'shop' => $shop2,
                'category' => $categoryMap['Grocery'] ?? null,
                'name' => 'ACI Pure Miniket Premium Rice 5kg',
                'slug' => 'aci-pure-miniket-rice-5kg',
                'item_code' => 'GR-881',
                'brand' => 'ACI Pure',
                'short_description' => 'Clean, slender grain Miniket rice sorted and sealed untouched by human hand.',
                'description' => 'ACI Pure Miniket Rice 5kg bag. Premium aromatic rice grain for daily wholesome family meals.',
                'fabrication' => null,
                'gsm' => null,
                'gender' => null,
                'price' => 430.00,
                'discount_price' => 395.00,
                'stock_quantity' => 200,
                'main_image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=800&q=80',
                'is_express' => true,
                'is_featured' => true,
                'variants' => [],
            ],
            [
                'shop' => $shop2,
                'category' => $categoryMap['Grocery'] ?? null,
                'name' => 'Rupchanda Fortified Soyabean Oil 5 Litre',
                'slug' => 'rupchanda-soyabean-oil-5l',
                'item_code' => 'GR-882',
                'brand' => 'Rupchanda',
                'short_description' => 'Vitamin A fortified cholesterol free healthy cooking oil.',
                'description' => 'Rupchanda Fortified Soyabean Oil 5 Litre jar. Sealed original factory pack.',
                'fabrication' => null,
                'gsm' => null,
                'gender' => null,
                'price' => 890.00,
                'discount_price' => 840.00,
                'stock_quantity' => 100,
                'main_image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800&q=80',
                'is_express' => true,
                'is_featured' => true,
                'variants' => [],
            ],
            [
                'shop' => $shop2,
                'category' => $categoryMap['Fruits & Vegetables'] ?? null,
                'name' => 'Organic Fresh Red Tomatoes 1kg',
                'slug' => 'organic-fresh-red-tomatoes-1kg',
                'item_code' => 'FV-101',
                'brand' => 'Local Farm',
                'short_description' => 'Farm fresh, juicy, naturally ripened local red tomatoes.',
                'description' => 'Fresh red local tomatoes hand-picked directly from Khulna agro farms.',
                'fabrication' => null,
                'gsm' => null,
                'gender' => null,
                'price' => 60.00,
                'discount_price' => 50.00,
                'stock_quantity' => 300,
                'main_image' => 'https://images.unsplash.com/photo-1546470427-0d4db154ceb7?w=800&q=80',
                'is_express' => true,
                'is_featured' => false,
                'variants' => [],
            ],
            [
                'shop' => $shop4,
                'category' => $categoryMap['Gadget'] ?? null,
                'name' => 'TWS Bluetooth 5.3 Wireless Earbuds with LED Display',
                'slug' => 'tws-bluetooth-wireless-earbuds-led-display',
                'item_code' => 'GD-309',
                'brand' => 'Airdots Pro',
                'short_description' => 'Deep bass, noise cancellation, 30 hours playback with power bank case.',
                'description' => 'High quality stereo sound TWS earbuds with digital battery display case. Compatible with Android & iOS.',
                'fabrication' => null,
                'gsm' => null,
                'gender' => null,
                'price' => 1250.00,
                'discount_price' => 890.00,
                'stock_quantity' => 80,
                'main_image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&q=80',
                'is_express' => true,
                'is_featured' => true,
                'variants' => [
                    ['type' => 'Color', 'value' => 'Black', 'price_adjustment' => 0, 'stock' => 50],
                    ['type' => 'Color', 'value' => 'White', 'price_adjustment' => 0, 'stock' => 30],
                ],
            ],
            [
                'shop' => $shop3,
                'category' => $categoryMap['Fast Food'] ?? null,
                'name' => 'Special Crispy Fried Chicken Bucket (4 Pcs) + French Fries',
                'slug' => 'crispy-fried-chicken-bucket-4pcs-fries',
                'item_code' => 'FD-401',
                'brand' => 'Amar Food Kitchen',
                'short_description' => 'Hot, spicy, and extra crunchy fried chicken with seasoned french fries.',
                'description' => 'Freshly prepared crispy fried chicken 4 pieces with side of hot golden fries and chili garlic dip.',
                'fabrication' => null,
                'gsm' => null,
                'gender' => null,
                'price' => 450.00,
                'discount_price' => 399.00,
                'stock_quantity' => 50,
                'main_image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=800&q=80',
                'is_express' => true,
                'is_featured' => true,
                'variants' => [],
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::create([
                'shop_id' => $pData['shop']->id,
                'category_id' => $pData['category'] ? $pData['category']->id : null,
                'name' => $pData['name'],
                'slug' => $pData['slug'],
                'item_code' => $pData['item_code'],
                'brand' => $pData['brand'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'fabrication' => $pData['fabrication'],
                'gsm' => $pData['gsm'],
                'gender' => $pData['gender'],
                'price' => $pData['price'],
                'discount_price' => $pData['discount_price'],
                'stock_quantity' => $pData['stock_quantity'],
                'main_image' => $pData['main_image'],
                'is_express' => $pData['is_express'],
                'is_featured' => $pData['is_featured'],
                'is_active' => true,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $pData['main_image'],
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            foreach ($pData['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'type' => $v['type'],
                    'value' => $v['value'],
                    'price_adjustment' => $v['price_adjustment'],
                    'stock' => $v['stock'],
                ]);
            }
        }

        // 6. Banners & Sliders
        $banners = [
            [
                'title' => 'Special Flash Campaign',
                'subtitle' => 'Save up to 50% on Daily Essentials',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/slider_1_1643173852.jpg',
                'link_url' => '/shops?campaign=1',
                'position' => 'hero_slider',
                'sort_order' => 1,
            ],
            [
                'title' => 'Amar Bangla Express Delivery',
                'subtitle' => 'Fastest Delivery to Your Doorstep in 2 Hours',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/slider_2_1643173867.jpg',
                'link_url' => '/shops?express=1',
                'position' => 'hero_slider',
                'sort_order' => 2,
            ],
            [
                'title' => 'Local Shop Connect',
                'subtitle' => 'Empowering Local Stores across Bangladesh',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/slider_3_1643173879.jpg',
                'link_url' => '/shops',
                'position' => 'hero_slider',
                'sort_order' => 3,
            ],
            [
                'title' => 'Fresh Bazar Grocery',
                'subtitle' => 'Discounted prices for members',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/banner_left_1671950100.jpg',
                'link_url' => '/shops/search_result?cat_id=2',
                'position' => 'home_banner_left',
                'sort_order' => 1,
            ],
            [
                'title' => 'Summer Fashion Collection',
                'subtitle' => 'New arrivals for Men & Women',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/banner_right_1671949959.jpg',
                'link_url' => '/shops/search_result?cat_id=14',
                'position' => 'home_banner_top',
                'sort_order' => 2,
            ],
            [
                'title' => 'Smart Gadgets & Electronics',
                'subtitle' => '100% Genuine with Warranty',
                'image_url' => 'https://amarbangla.com.bd/uploads/website_image/banner_right_1671949975.jpg',
                'link_url' => '/shops/search_result?cat_id=17',
                'position' => 'home_banner_bottom',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $b) {
            Banner::create($b);
        }

        // 7. Sample Initial Order
        $sampleProduct = Product::first();
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_email' => $customer->email,
            'shipping_address' => 'House 22, Road 4, Sector 7, Uttara, Dhaka',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Uttara',
            'notes' => 'Please call before delivery',
            'subtotal' => 640.00,
            'discount' => 0.00,
            'shipping_fee' => 60.00,
            'total_amount' => 700.00,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'confirmed',
            'transaction_id' => null,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'shop_id' => $sampleProduct->shop_id,
            'product_id' => $sampleProduct->id,
            'product_name' => $sampleProduct->name,
            'variant_info' => 'Size: L, Color: White',
            'price' => 320.00,
            'quantity' => 2,
            'subtotal' => 640.00,
            'status' => 'pending',
        ]);

        $this->call(GlobalAddressSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
