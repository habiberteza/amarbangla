<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            if (!Schema::hasColumn('licenses', 'license_key')) {
                $table->string('license_key')->nullable()->after('license_number');
            }
            if (!Schema::hasColumn('licenses', 'start_date')) {
                $table->date('start_date')->nullable()->after('issue_date');
            }
            if (!Schema::hasColumn('licenses', 'end_date')) {
                $table->date('end_date')->nullable()->after('expiry_date');
            }
        });

        // Sync existing data from license_number, issue_date, expiry_date
        DB::table('licenses')->whereNull('license_key')->update([
            'license_key' => DB::raw('license_number'),
        ]);
        DB::table('licenses')->whereNull('start_date')->update([
            'start_date' => DB::raw('issue_date'),
        ]);
        DB::table('licenses')->whereNull('end_date')->update([
            'end_date' => DB::raw('expiry_date'),
        ]);
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['license_key', 'start_date', 'end_date']);
        });
    }
};
