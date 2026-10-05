<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('global_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('global_addresses', 'pourashava')) {
                $table->string('pourashava')->nullable()->after('upazila');
            }
            if (!Schema::hasColumn('global_addresses', 'ward')) {
                $table->string('ward')->nullable()->after('pourashava');
            }
        });
    }

    public function down(): void
    {
        Schema::table('global_addresses', function (Blueprint $table) {
            $table->dropColumn(['pourashava', 'ward']);
        });
    }
};
