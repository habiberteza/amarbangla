<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding product images from demo_products...');

        $affected = DB::update("
            UPDATE products p
            JOIN demo_products d ON p.demo_id = d.id
            SET p.picture = d.picture
            WHERE (p.picture IS NULL OR p.picture = '' OR p.picture = '[]' OR p.picture = 'null')
              AND d.picture IS NOT NULL
              AND d.picture != ''
              AND d.picture != '[]'
              AND d.picture != 'null'
        ");

        $totalWithImages = DB::table('products')
            ->whereNotNull('picture')
            ->where('picture', '!=', '')
            ->where('picture', '!=', '[]')
            ->where('picture', '!=', 'null')
            ->count();

        $this->command->info("Updated {$affected} products. Total products with images now: {$totalWithImages}.");
    }
}
