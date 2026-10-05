<?php

namespace Database\Seeders;

use App\Models\GlobalAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalAddressSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/global_addresses.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("Data file not found: {$jsonPath}");
            return;
        }

        $records = json_decode(file_get_contents($jsonPath), true);
        if (empty($records)) {
            $this->command->error("No records found in data file.");
            return;
        }

        $this->command->info("Seeding " . count($records) . " global addresses...");

        DB::transaction(function () use ($records) {
            GlobalAddress::truncate();

            $chunks = array_chunk($records, 500);
            foreach ($chunks as $chunk) {
                DB::table('global_addresses')->insert($chunk);
            }
        });

        $this->command->info("Successfully inserted " . GlobalAddress::count() . " global addresses across " . GlobalAddress::distinct('division')->count('division') . " divisions and " . GlobalAddress::distinct('district')->count('district') . " districts.");
    }
}
