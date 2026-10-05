<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update global_addresses table using strict string equals
        if (Schema::hasTable('global_addresses') && Schema::hasColumn('global_addresses', 'pourashava')) {
            DB::table('global_addresses')->where('pourashava', '=', '0')->update(['pourashava' => 'City Corporation']);
            DB::table('global_addresses')->where('pourashava', '=', '1')->update(['pourashava' => 'Pourashava']);
            DB::table('global_addresses')->where('pourashava', '=', '2')->update(['pourashava' => 'Union']);
        }

        // 2. Update shops table using strict string equals
        if (Schema::hasTable('shops') && Schema::hasColumn('shops', 'pourashava')) {
            DB::table('shops')->where('pourashava', '=', '0')->update(['pourashava' => 'City Corporation']);
            DB::table('shops')->where('pourashava', '=', '1')->update(['pourashava' => 'Pourashava']);
            DB::table('shops')->where('pourashava', '=', '2')->update(['pourashava' => 'Union']);
        }

        // 3. Update customers table using strict string equals
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'pourashava')) {
            DB::table('customers')->where('pourashava', '=', '0')->update(['pourashava' => 'City Corporation']);
            DB::table('customers')->where('pourashava', '=', '1')->update(['pourashava' => 'Pourashava']);
            DB::table('customers')->where('pourashava', '=', '2')->update(['pourashava' => 'Union']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('global_addresses') && Schema::hasColumn('global_addresses', 'pourashava')) {
            DB::table('global_addresses')->where('pourashava', '=', 'City Corporation')->update(['pourashava' => '0']);
            DB::table('global_addresses')->where('pourashava', '=', 'Pourashava')->update(['pourashava' => '1']);
            DB::table('global_addresses')->where('pourashava', '=', 'Union')->update(['pourashava' => '2']);
        }

        if (Schema::hasTable('shops') && Schema::hasColumn('shops', 'pourashava')) {
            DB::table('shops')->where('pourashava', '=', 'City Corporation')->update(['pourashava' => '0']);
            DB::table('shops')->where('pourashava', '=', 'Pourashava')->update(['pourashava' => '1']);
            DB::table('shops')->where('pourashava', '=', 'Union')->update(['pourashava' => '2']);
        }

        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'pourashava')) {
            DB::table('customers')->where('pourashava', '=', 'City Corporation')->update(['pourashava' => '0']);
            DB::table('customers')->where('pourashava', '=', 'Pourashava')->update(['pourashava' => '1']);
            DB::table('customers')->where('pourashava', '=', 'Union')->update(['pourashava' => '2']);
        }
    }
};
