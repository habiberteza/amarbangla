<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 12, 2)->default(0.00)->after('status');
            }
            if (!Schema::hasColumn('users', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(5.00)->after('balance');
            }
            if (!Schema::hasColumn('users', 'commission_type')) {
                $table->string('commission_type', 30)->default('percentage')->after('commission_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'commission_rate', 'commission_type']);
        });
    }
};
