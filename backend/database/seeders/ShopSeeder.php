<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $startTime = microtime(true);
        $this->command->info('Seeding all nationwide Amar Bangla shops from live business architecture...');

        // 1. Ensure master vendor and admin users exist
        $masterVendor = User::firstOrCreate(
            ['email' => 'merchant@amarbangla.com.bd'],
            [
                'name'     => 'Amar Bangla Merchant Network',
                'phone'    => '01714070770',
                'role'     => 'vendor',
                'password' => Hash::make('password123'),
                'status'   => 'active',
            ]
        );

        $vendorUserMap = [
            'default' => $masterVendor->id,
        ];

        // 2. Fetch Category ID mappings
        $catFood        = Category::where('name', 'like', '%FOOD%')->first()?->id ?? 1;
        $subGrocery     = Category::where('name', 'Grocery')->first()?->id ?? 2;
        $subFastFood    = Category::where('name', 'Fast Food')->first()?->id ?? 8;
        $subMeatFish    = Category::where('name', 'Meat & Fish')->first()?->id ?? 3;

        $catElectronics = Category::where('name', 'like', '%ELECTRONIC%')->first()?->id ?? 9;
        $subMobile      = Category::where('name', 'Mobile')->first()?->id ?? 10;
        $subGadget      = Category::where('name', 'Gadget')->first()?->id ?? 11;

        $catFashion     = Category::where('name', 'Fashion')->first()?->id ?? 13;
        $subMensCloth   = Category::where('name', "Men's Clothing")->first()?->id ?? 14;
        $subWomensCloth = Category::where('name', "Women's Clothing")->first()?->id ?? 15;
        $subKidsZone    = Category::where('name', 'Kids Zone')->first()?->id ?? 16;
        $subFootwear    = Category::where('name', 'Footwear')->first()?->id ?? 17;

        $catServices    = Category::where('name', 'Services')->first()?->id ?? 21;
        $subLaundry     = Category::where('name', 'Laundry Service')->first()?->id ?? 22;
        $subLpg         = Category::where('name', 'LPG Gas Service')->first()?->id ?? 23;
        $subMistri      = Category::where('name', 'Mistri Seba')->first()?->id ?? 24;

        // 3. Known Live Verified Merchant Stores
        $verifiedShops = [
            [
                'name'            => 'Amar Bangla Shop',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'top',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'logo'            => 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1643866917.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80',
                'phone'           => '+880 1714070770',
                'email'           => 'shop@amarbangla.com.bd',
                'address'         => 'House 14, Road 5, Dhanmondi, Dhaka',
                'house_address'   => 'Holding 14/A, Dhanmondi R/A',
                'division'        => 'Dhaka',
                'district'        => 'Dhaka',
                'upazila'         => 'Dhanmondi',
                'commission_rate' => 5.00,
                'balance'         => 18540.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Official flagship verified marketplace outlet for high quality fashion, apparel, and daily essentials across Bangladesh.',
            ],
            [
                'name'            => 'Amar Bangla Express Store',
                'type'            => 'express',
                'shop_type'       => 'express',
                'priority_type'   => 'top',
                'category_id'     => $catFood,
                'sub_category_id' => $subFastFood,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Fast Food',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/logo_1643864917.jpg',
                'image'           => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1200&q=80',
                'phone'           => '+880 1799887766',
                'email'           => 'express@amarbangla.com.bd',
                'address'         => 'Plot 8, Mirpur-10, Dhaka',
                'division'        => 'Dhaka',
                'district'        => 'Dhaka',
                'upazila'         => 'Mirpur',
                'commission_rate' => 7.00,
                'balance'         => 9420.50,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Fast 2-hour super express delivery store for groceries, meat, fish, fresh vegetables and essential beverages.',
            ],
            [
                'name'            => 'Gaming mobile point',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'top',
                'category_id'     => $catElectronics,
                'sub_category_id' => $subMobile,
                'category'        => 'ELECTRONICS',
                'sub_category'    => 'Mobile',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1666594590.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1666594590.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&q=80',
                'phone'           => '+880 1712345678',
                'email'           => 'gaming.mobile@amarbangla.com.bd',
                'address'         => 'Shariatpur Market, Shariatpur Sadar',
                'division'        => 'Dhaka',
                'district'        => 'Shariatpur',
                'upazila'         => 'Shariatpur Sadar',
                'commission_rate' => 5.00,
                'balance'         => 24350.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Authentic smartphones, gaming gadgets, official accessories, and high performance mobile devices.',
            ],
            [
                'name'            => 'Sky Mobile Point',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catElectronics,
                'sub_category_id' => $subMobile,
                'category'        => 'ELECTRONICS',
                'sub_category'    => 'Mobile',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1643866733.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1643866733.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=1200&q=80',
                'phone'           => '+880 1723456789',
                'email'           => 'sky.mobile@amarbangla.com.bd',
                'address'         => 'Jessore City Center, Jashore',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Jashore Sadar',
                'commission_rate' => 5.00,
                'balance'         => 8210.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Authorized multi-brand mobile dealer for Xiaomi, Vivo, Oppo, Realme with brand warranty.',
            ],
            [
                'name'            => 'Jessore Mart',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=1200&q=80',
                'phone'           => '+880 1734567890',
                'email'           => 'jessore.mart@amarbangla.com.bd',
                'address'         => 'Rail Road, Jashore Sadar',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Jashore Sadar',
                'commission_rate' => 6.00,
                'balance'         => 14750.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Top retail grocery and fresh food marketplace in Jashore with same-day home delivery.',
            ],
            [
                'name'            => 'Amar GADGET',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catElectronics,
                'sub_category_id' => $subGadget,
                'category'        => 'ELECTRONICS',
                'sub_category'    => 'Gadget',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=1200&q=80',
                'phone'           => '+880 1745678901',
                'email'           => 'amar.gadget@amarbangla.com.bd',
                'address'         => 'Khulna Shopping Complex, Sonadanga, Khulna',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Sonadanga',
                'commission_rate' => 7.00,
                'balance'         => 12100.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Smart watches, earbuds, powerbanks, Bluetooth speakers, and latest tech gadgets.',
            ],
            [
                'name'            => 'Kids Zone KJA7460',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subKidsZone,
                'category'        => 'Fashion',
                'sub_category'    => 'Kids Zone',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=1200&q=80',
                'phone'           => '+880 1756789012',
                'email'           => 'kidszone@amarbangla.com.bd',
                'address'         => 'Dhaka Road, Jashore',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Jashore Sadar',
                'commission_rate' => 8.00,
                'balance'         => 5400.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Kids fashion, baby care, soft toys, children educational games, and infant outfits.',
            ],
            [
                'name'            => 'Protein Market',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subMeatFish,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Meat & Fish',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1672204981.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1672204981.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1200&q=80',
                'phone'           => '+880 1767890123',
                'email'           => 'protein.market@amarbangla.com.bd',
                'address'         => 'Sonadanga Main Road, Khulna',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Sonadanga',
                'commission_rate' => 6.00,
                'balance'         => 18900.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Fresh river fish, organic chicken, beef, mutton, and fresh dairy products delivered frozen/chilled.',
            ],
            [
                'name'            => 'দেশি রত্ন',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subWomensCloth,
                'category'        => 'Fashion',
                'sub_category'    => "Women's Clothing",
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1684294966.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1684294966.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=1200&q=80',
                'phone'           => '+880 1778901234',
                'email'           => 'deshiratna@amarbangla.com.bd',
                'address'         => 'Tangail Road, Gazipur',
                'division'        => 'Dhaka',
                'district'        => 'Gazipur',
                'upazila'         => 'Gazipur Sadar',
                'commission_rate' => 7.00,
                'balance'         => 7320.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Handcrafted traditional sarees, jamdani, silk, boutique three-piece, and artisanal jewelry.',
            ],
            [
                'name'            => 'সৃষ্টির সমাহার',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subWomensCloth,
                'category'        => 'Fashion',
                'sub_category'    => "Women's Clothing",
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=1200&q=80',
                'phone'           => '+880 1789012345',
                'email'           => 'sristir.somahar@amarbangla.com.bd',
                'address'         => 'Natore Road, Rajshahi',
                'division'        => 'Rajshahi',
                'district'        => 'Rajshahi',
                'upazila'         => 'Boalia',
                'commission_rate' => 8.00,
                'balance'         => 11500.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Nakshi kantha, authentic clay art, folk handicrafts, and bespoke hand-embroidery.',
            ],
            [
                'name'            => 'লন্ড্রি সেবা শরীয়তপুর',
                'type'            => 'local',
                'shop_type'       => 'local',
                'priority_type'   => 'general',
                'category_id'     => $catServices,
                'sub_category_id' => $subLaundry,
                'category'        => 'Services',
                'sub_category'    => 'Laundry Service',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1715692825.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1715692825.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?w=1200&q=80',
                'phone'           => '+880 1790123456',
                'email'           => 'laundry.shariatpur@amarbangla.com.bd',
                'address'         => 'Palong Bazar, Shariatpur Sadar',
                'division'        => 'Dhaka',
                'district'        => 'Shariatpur',
                'upazila'         => 'Shariatpur Sadar',
                'commission_rate' => 8.00,
                'balance'         => 4200.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Professional steam wash, dry cleaning, ironing, and doorstep pickup & delivery in Shariatpur.',
            ],
            [
                'name'            => 'মিস্ত্রি সেবা শরীয়তপুর',
                'type'            => 'local',
                'shop_type'       => 'local',
                'priority_type'   => 'general',
                'category_id'     => $catServices,
                'sub_category_id' => $subMistri,
                'category'        => 'Services',
                'sub_category'    => 'Mistri Seba',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1715693482.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1715693482.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=1200&q=80',
                'phone'           => '+880 1701234567',
                'email'           => 'mistri.shariatpur@amarbangla.com.bd',
                'address'         => 'Court Road, Shariatpur Sadar',
                'division'        => 'Dhaka',
                'district'        => 'Shariatpur',
                'upazila'         => 'Shariatpur Sadar',
                'commission_rate' => 10.00,
                'balance'         => 6800.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Expert electric, plumbing, air conditioning, and household repair mechanics on demand.',
            ],
            [
                'name'            => 'Narayanganj Mart',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner_1716208799.jpg',
                'image'           => 'https://amarbangla.com.bd/uploads/schools/banner_1716208799.jpg',
                'banner'          => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1200&q=80',
                'phone'           => '+880 1712348901',
                'email'           => 'narayanganj.mart@amarbangla.com.bd',
                'address'         => 'BB Road, Narayanganj Sadar',
                'division'        => 'Dhaka',
                'district'        => 'Narayanganj',
                'upazila'         => 'Narayanganj Sadar',
                'commission_rate' => 5.00,
                'balance'         => 19450.00,
                'is_featured'     => true,
                'home_feature'    => true,
                'status'          => 'active',
                'description'     => 'Premier departmental superstore in Narayanganj with extensive fresh groceries and household goods.',
            ],
            [
                'name'            => 'বাপ্পি স্টোর Bappy Store',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/logo_1643864912.jpg',
                'image'           => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200&q=80',
                'phone'           => '+880 1715678901',
                'email'           => 'bappy.store@amarbangla.com.bd',
                'address'         => 'Khulna Bazar, Khulna Sadar',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Khulna Sadar',
                'commission_rate' => 5.00,
                'balance'         => 11300.00,
                'is_featured'     => false,
                'home_feature'    => false,
                'status'          => 'active',
                'description'     => 'Popular neighborhood grocery store stocking rice, spices, lentils, oil, and consumer items.',
            ],
            [
                'name'            => 'Shomrat Store',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1716789012',
                'email'           => 'shomrat@amarbangla.com.bd',
                'address'         => 'Sonadanga, Khulna',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Sonadanga',
                'commission_rate' => 5.00,
                'balance'         => 6450.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'Alom Store',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1717890123',
                'email'           => 'alom@amarbangla.com.bd',
                'address'         => 'Daulatpur Bazar, Khulna',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Daulatpur',
                'commission_rate' => 5.00,
                'balance'         => 4800.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'Naj Shoes নাজ জুতো',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subFootwear,
                'category'        => 'Fashion',
                'sub_category'    => 'Footwear',
                'phone'           => '+880 1718901234',
                'email'           => 'naj.shoes@amarbangla.com.bd',
                'address'         => 'New Market, Jashore Sadar',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Jashore Sadar',
                'commission_rate' => 6.00,
                'balance'         => 7600.00,
                'status'          => 'active',
            ],
            [
                'name'            => "Salim's taylors",
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subMensCloth,
                'category'        => 'Fashion',
                'sub_category'    => "Men's Clothing",
                'phone'           => '+880 1719012345',
                'email'           => 'salims.taylors@amarbangla.com.bd',
                'address'         => 'Station Road, Jashore',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Jashore Sadar',
                'commission_rate' => 8.00,
                'balance'         => 3900.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'লুঙ্গি ঘর',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subMensCloth,
                'category'        => 'Fashion',
                'sub_category'    => "Men's Clothing",
                'phone'           => '+880 1720123456',
                'email'           => 'lungighor@amarbangla.com.bd',
                'address'         => 'Cloth Market, Sirajganj',
                'division'        => 'Rajshahi',
                'district'        => 'Sirajganj',
                'upazila'         => 'Belkuchi',
                'commission_rate' => 6.00,
                'balance'         => 8900.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'সমস্ত মিস্ত্রি সেবা, নওয়াপাড়া',
                'type'            => 'local',
                'shop_type'       => 'local',
                'priority_type'   => 'general',
                'category_id'     => $catServices,
                'sub_category_id' => $subMistri,
                'category'        => 'Services',
                'sub_category'    => 'Mistri Seba',
                'phone'           => '+880 1721234567',
                'email'           => 'mistri.nowapara@amarbangla.com.bd',
                'address'         => 'Nowapara Industrial Area, Abhaynagar',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Abhaynagar',
                'commission_rate' => 10.00,
                'balance'         => 5300.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'Tamanna Islam',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFashion,
                'sub_category_id' => $subWomensCloth,
                'category'        => 'Fashion',
                'sub_category'    => "Women's Clothing",
                'phone'           => '+880 1722345678',
                'email'           => 'tamanna@amarbangla.com.bd',
                'address'         => 'Uttara Sector 7, Dhaka',
                'division'        => 'Dhaka',
                'district'        => 'Dhaka',
                'upazila'         => 'Uttara',
                'commission_rate' => 7.00,
                'balance'         => 9150.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'কাঁচা বাজার, নওয়াপাড়া',
                'type'            => 'local',
                'shop_type'       => 'local',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1723456789',
                'email'           => 'kachabazar.nowapara@amarbangla.com.bd',
                'address'         => 'Nowapara Bazar, Abhaynagar',
                'division'        => 'Khulna',
                'district'        => 'Jashore',
                'upazila'         => 'Abhaynagar',
                'commission_rate' => 5.00,
                'balance'         => 6700.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'দাদি বাড়ি',
                'type'            => 'food',
                'shop_type'       => 'food',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subFastFood,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Fast Food',
                'phone'           => '+880 1724567890',
                'email'           => 'dadibari@amarbangla.com.bd',
                'address'         => 'Sonadanga, Khulna',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Sonadanga',
                'commission_rate' => 10.00,
                'balance'         => 4100.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'রমনী পার্ক',
                'type'            => 'food',
                'shop_type'       => 'food',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subFastFood,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Fast Food',
                'phone'           => '+880 1725678901',
                'email'           => 'romonipark@amarbangla.com.bd',
                'address'         => 'Shahbagh, Dhaka',
                'division'        => 'Dhaka',
                'district'        => 'Dhaka',
                'upazila'         => 'Ramna',
                'commission_rate' => 10.00,
                'balance'         => 8200.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'ATIF Super Mart',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1726789012',
                'email'           => 'atifspermart@amarbangla.com.bd',
                'address'         => 'Rajshahi Sadar, Boalia',
                'division'        => 'Rajshahi',
                'district'        => 'Rajshahi',
                'upazila'         => 'Boalia',
                'commission_rate' => 5.00,
                'balance'         => 13400.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'Satkhira Mobile Mart',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catElectronics,
                'sub_category_id' => $subMobile,
                'category'        => 'ELECTRONICS',
                'sub_category'    => 'Mobile',
                'phone'           => '+880 1727890123',
                'email'           => 'satkhira.mobile@amarbangla.com.bd',
                'address'         => 'Satkhira Market, Satkhira Sadar',
                'division'        => 'Khulna',
                'district'        => 'Satkhira',
                'upazila'         => 'Satkhira Sadar',
                'commission_rate' => 5.00,
                'balance'         => 6900.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'খুলনা মার্ট',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1728901234',
                'email'           => 'khulnamart@amarbangla.com.bd',
                'address'         => 'Shibbari More, Khulna Sadar',
                'division'        => 'Khulna',
                'district'        => 'Khulna',
                'upazila'         => 'Khulna Sadar',
                'commission_rate' => 5.00,
                'balance'         => 15600.00,
                'status'          => 'active',
            ],
            [
                'name'            => 'সাতক্ষীরা মার্ট',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'priority_type'   => 'general',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'phone'           => '+880 1729012345',
                'email'           => 'satkhiramart@amarbangla.com.bd',
                'address'         => 'Sultanpur Bazar, Satkhira Sadar',
                'division'        => 'Khulna',
                'district'        => 'Satkhira',
                'upazila'         => 'Satkhira Sadar',
                'commission_rate' => 5.00,
                'balance'         => 11200.00,
                'status'          => 'active',
            ],
        ];

        // 4. Load Global Addresses Geo Hierarchy
        $jsonPath = database_path('data/global_addresses.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("Geo addresses file not found at {$jsonPath}");
            return;
        }

        $addressList = json_decode(file_get_contents($jsonPath), true);
        $this->command->info('Loaded ' . count($addressList) . ' address points.');

        // Group into unique Upazilas with their Division and District
        $uniqueUpazilas = [];
        $uniqueDistricts = [];

        foreach ($addressList as $row) {
            $div  = trim($row['division'] ?? '');
            $dist = trim($row['district'] ?? '');
            $upz  = trim($row['upazila'] ?? '');

            if ($div && $dist && $upz) {
                $key = "{$div}|{$dist}|{$upz}";
                if (!isset($uniqueUpazilas[$key])) {
                    $uniqueUpazilas[$key] = [
                        'division'   => $div,
                        'district'   => $dist,
                        'upazila'    => $upz,
                        'pourashava' => !empty($row['pourashava']) ? trim($row['pourashava']) : 'Pourashava',
                        'ward'       => !empty($row['ward']) ? trim($row['ward']) : 'ward 1',
                    ];
                }
            }

            if ($div && $dist) {
                $distKey = "{$div}|{$dist}";
                if (!isset($uniqueDistricts[$distKey])) {
                    $uniqueDistricts[$distKey] = [
                        'division' => $div,
                        'district' => $dist,
                    ];
                }
            }
        }

        $this->command->info('Extracted ' . count($uniqueUpazilas) . ' unique Upazilas and ' . count($uniqueDistricts) . ' Districts.');

        // 5. Build nationwide shops dataset
        $shopsToInsert = [];
        $existingSlugs = [];
        $now = now()->toDateTimeString();

        // Helper to generate a unique SEO slug
        $makeSlug = function (string $name, string $geo) use (&$existingSlugs): string {
            $base = Str::slug($name . ' ' . $geo);
            if (empty($base)) {
                $base = 'shop-' . Str::random(6);
            }
            $slug = $base;
            $counter = 1;
            while (isset($existingSlugs[$slug])) {
                $slug = "{$base}-" . (++$counter);
            }
            $existingSlugs[$slug] = true;
            return $slug;
        };

        // A. Insert Verified & Flagship Merchants First
        foreach ($verifiedShops as $s) {
            $slug = $makeSlug($s['name'], $s['upazila'] ?? $s['district'] ?? '');
            $shopsToInsert[] = array_merge([
                'user_id'         => $masterVendor->id,
                'name'            => $s['name'],
                'slug'            => $slug,
                'type'            => $s['type'] ?? 'regular',
                'shop_type'       => $s['shop_type'] ?? 'regular',
                'priority_type'   => $s['priority_type'] ?? 'general',
                'category_id'     => $s['category_id'] ?? null,
                'sub_category_id' => $s['sub_category_id'] ?? null,
                'category'        => $s['category'] ?? 'Retail',
                'sub_category'    => $s['sub_category'] ?? null,
                'logo'            => $s['logo'] ?? null,
                'image'           => $s['image'] ?? null,
                'banner'          => $s['banner'] ?? null,
                'phone'           => $s['phone'] ?? '+880 1714070770',
                'email'           => $s['email'] ?? null,
                'address'         => $s['address'] ?? null,
                'house_address'   => $s['house_address'] ?? null,
                'division'        => $s['division'] ?? 'Dhaka',
                'district'        => $s['district'] ?? 'Dhaka',
                'upazila'         => $s['upazila'] ?? null,
                'pourashava'      => 'Pourashava',
                'ward'            => 'ward 1',
                'commission_rate' => $s['commission_rate'] ?? 5.00,
                'balance'         => $s['balance'] ?? 0.00,
                'rating'          => 4.85,
                'is_featured'     => $s['is_featured'] ?? false,
                'home_feature'    => $s['home_feature'] ?? false,
                'status'          => $s['status'] ?? 'active',
                'description'     => $s['description'] ?? 'Verified official Amar Bangla partner outlet.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ], $s);
        }

        // B. Regional Franchise Outlets for Every Upazila
        // 1. {Upazila} Mart (Grocery)
        // 2. LPG Gas Service {Upazila}
        // 3. মিস্ত্রি সেবা {Upazila} (Mistri Seba)
        // 4. লন্ড্রি সেবা {Upazila} (Laundry Service)
        // 5. Fast Food {Upazila} (Food & Snacks)
        // 6. {Upazila} Mobile & Gadget (Electronics)
        $templates = [
            [
                'prefix'          => '',
                'suffix'          => ' Mart',
                'type'            => 'regular',
                'shop_type'       => 'local',
                'category_id'     => $catFood,
                'sub_category_id' => $subGrocery,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Grocery',
                'commission_rate' => 5.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg',
                'image'           => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1200&q=80',
                'desc'            => 'Local area outlet for daily groceries, rice, pulses, cooking oils, and fresh produce.',
            ],
            [
                'prefix'          => 'LPG Gas Service ',
                'suffix'          => '',
                'type'            => 'express',
                'shop_type'       => 'express',
                'category_id'     => $catServices,
                'sub_category_id' => $subLpg,
                'category'        => 'Services',
                'sub_category'    => 'LPG Gas Service',
                'commission_rate' => 7.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=1200&q=80',
                'desc'            => 'Quick home delivery of LPG gas cylinders (Bashundhara, Omera, Jamuna, Beximco) and regulator safety accessories.',
            ],
            [
                'prefix'          => 'মিস্ত্রি সেবা ',
                'suffix'          => '',
                'type'            => 'local',
                'shop_type'       => 'local',
                'category_id'     => $catServices,
                'sub_category_id' => $subMistri,
                'category'        => 'Services',
                'sub_category'    => 'Mistri Seba',
                'commission_rate' => 10.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=1200&q=80',
                'desc'            => 'Certified electric, sanitary plumbing, water motor, refrigerator, and household repair mechanics.',
            ],
            [
                'prefix'          => 'লন্ড্রি সেবা ',
                'suffix'          => '',
                'type'            => 'local',
                'shop_type'       => 'local',
                'category_id'     => $catServices,
                'sub_category_id' => $subLaundry,
                'category'        => 'Services',
                'sub_category'    => 'Laundry Service',
                'commission_rate' => 8.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?w=1200&q=80',
                'desc'            => 'Professional steam laundry, dry cleaning, ironing, and suit pressing with doorstep pickup.',
            ],
            [
                'prefix'          => 'Fast Food ',
                'suffix'          => '',
                'type'            => 'food',
                'shop_type'       => 'food',
                'category_id'     => $catFood,
                'sub_category_id' => $subFastFood,
                'category'        => 'FOOD & GROCERY',
                'sub_category'    => 'Fast Food',
                'commission_rate' => 10.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80',
                'desc'            => 'Burgers, chicken fry, pizza, noodles, local snacks, and beverages delivered hot.',
            ],
            [
                'prefix'          => '',
                'suffix'          => ' Mobile & Gadget',
                'type'            => 'regular',
                'shop_type'       => 'regular',
                'category_id'     => $catElectronics,
                'sub_category_id' => $subMobile,
                'category'        => 'ELECTRONICS',
                'sub_category'    => 'Mobile',
                'commission_rate' => 5.00,
                'logo'            => 'https://amarbangla.com.bd/uploads/schools/banner.png',
                'image'           => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80',
                'banner'          => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1200&q=80',
                'desc'            => 'Mobile phones, chargers, smart gadgets, earphones, and accessories at competitive prices.',
            ],
        ];

        $phoneCounter = 1710000000;

        foreach ($uniqueUpazilas as $geo) {
            $upz  = $geo['upazila'];
            $dist = $geo['district'];
            $div  = $geo['division'];
            $pour = $geo['pourashava'];
            $ward = $geo['ward'];

            foreach ($templates as $tpl) {
                $shopName = $tpl['prefix'] . $upz . $tpl['suffix'];
                $slug = $makeSlug($shopName, $dist);
                $phoneCounter++;
                $phone = '+880 ' . substr((string)$phoneCounter, 0, 4) . ' ' . substr((string)$phoneCounter, 4);

                $shopsToInsert[] = [
                    'user_id'         => $masterVendor->id,
                    'name'            => $shopName,
                    'slug'            => $slug,
                    'type'            => $tpl['type'],
                    'shop_type'       => $tpl['shop_type'],
                    'priority_type'   => 'general',
                    'category_id'     => $tpl['category_id'],
                    'sub_category_id' => $tpl['sub_category_id'],
                    'category'        => $tpl['category'],
                    'sub_category'    => $tpl['sub_category'],
                    'logo'            => $tpl['logo'],
                    'image'           => $tpl['image'],
                    'banner'          => $tpl['banner'],
                    'phone'           => $phone,
                    'email'           => Str::slug($shopName) . '@amarbangla.com.bd',
                    'address'         => "{$upz} Bazar, {$upz}, {$dist}",
                    'house_address'   => "Holding #12, {$pour}, {$ward}, {$upz}",
                    'division'        => $div,
                    'district'        => $dist,
                    'upazila'         => $upz,
                    'pourashava'      => $pour,
                    'ward'            => $ward,
                    'commission_rate' => $tpl['commission_rate'],
                    'balance'         => 0.00,
                    'rating'          => 4.80,
                    'is_featured'     => false,
                    'home_feature'    => false,
                    'status'          => 'active',
                    'description'     => $tpl['desc'],
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        // C. Also add District Central Outlets for all 64 Districts
        foreach ($uniqueDistricts as $geo) {
            $dist = $geo['district'];
            $div  = $geo['division'];

            $distTemplates = [
                [
                    'name' => "{$dist} Central Mart",
                    'type' => 'regular', 'shop_type' => 'regular',
                    'cat_id' => $catFood, 'sub_id' => $subGrocery,
                    'cat' => 'FOOD & GROCERY', 'sub' => 'Grocery',
                    'comm' => 5.00,
                ],
                [
                    'name' => "LPG Gas Service {$dist} Sadar",
                    'type' => 'express', 'shop_type' => 'express',
                    'cat_id' => $catServices, 'sub_id' => $subLpg,
                    'cat' => 'Services', 'sub' => 'LPG Gas Service',
                    'comm' => 7.00,
                ],
            ];

            foreach ($distTemplates as $dtpl) {
                $slug = $makeSlug($dtpl['name'], $div);
                $phoneCounter++;
                $phone = '+880 ' . substr((string)$phoneCounter, 0, 4) . ' ' . substr((string)$phoneCounter, 4);

                $shopsToInsert[] = [
                    'user_id'         => $masterVendor->id,
                    'name'            => $dtpl['name'],
                    'slug'            => $slug,
                    'type'            => $dtpl['type'],
                    'shop_type'       => $dtpl['shop_type'],
                    'priority_type'   => 'general',
                    'category_id'     => $dtpl['cat_id'],
                    'sub_category_id' => $dtpl['sub_id'],
                    'category'        => $dtpl['cat'],
                    'sub_category'    => $dtpl['sub'],
                    'logo'            => 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg',
                    'image'           => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80',
                    'banner'          => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80',
                    'phone'           => $phone,
                    'email'           => Str::slug($dtpl['name']) . '@amarbangla.com.bd',
                    'address'         => "District Central Point, {$dist}",
                    'house_address'   => "Main Road, {$dist} Sadar",
                    'division'        => $div,
                    'district'        => $dist,
                    'upazila'         => "{$dist} Sadar",
                    'pourashava'      => 'Pourashava',
                    'ward'            => 'ward 1',
                    'commission_rate' => $dtpl['comm'],
                    'balance'         => 0.00,
                    'rating'          => 4.80,
                    'is_featured'     => false,
                    'home_feature'    => false,
                    'status'          => 'active',
                    'description'     => "District-wide central fulfillment outlet for {$dist}.",
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        $totalCount = count($shopsToInsert);
        $this->command->info("Total compiled shops to insert: {$totalCount}");

        // 6. Truncate / Refresh Shops table cleanly to avoid duplicate clutter
        Shop::truncate();

        // 7. Bulk Insert in chunks of 200 for lightning speed
        $chunkSize = 200;
        $chunks = array_chunk($shopsToInsert, $chunkSize);
        $insertedSoFar = 0;

        foreach ($chunks as $idx => $chunk) {
            DB::table('shops')->insert($chunk);
            $insertedSoFar += count($chunk);
            if (($idx + 1) % 5 === 0 || $insertedSoFar === $totalCount) {
                $this->command->info("Inserted {$insertedSoFar} / {$totalCount} shops...");
            }
        }

        $duration = round(microtime(true) - $startTime, 2);
        $this->command->info("Successfully inserted {$insertedSoFar} shops in {$duration} seconds!");
    }
}
