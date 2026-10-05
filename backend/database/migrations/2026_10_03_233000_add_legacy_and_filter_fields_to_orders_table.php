<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'invoice_id')) {
                $table->unsignedBigInteger('invoice_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('orders', 'shop_id')) {
                $table->unsignedBigInteger('shop_id')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('orders', 'agent_id')) {
                $table->unsignedBigInteger('agent_id')->nullable()->after('shop_id')->index();
            }
            if (!Schema::hasColumn('orders', 'pourashava')) {
                $table->string('pourashava')->nullable()->after('upazila')->index();
            }
            if (!Schema::hasColumn('orders', 'ward')) {
                $table->string('ward')->nullable()->after('pourashava')->index();
            }
            if (!Schema::hasColumn('orders', 'global_address_id')) {
                $table->unsignedBigInteger('global_address_id')->nullable()->after('ward')->index();
            }
            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status')->default('not_accepted')->after('order_status')->index();
            }
        });

        // Modify order_status and order_items status from enum to varchar so legacy & custom statuses don't throw MySQL enum errors
        try {
            DB::statement("ALTER TABLE `orders` MODIFY `order_status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // Ignore if already varchar or sqlite
        }

        try {
            DB::statement("ALTER TABLE `order_items` MODIFY `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = [];
            foreach (['invoice_id', 'shop_id', 'agent_id', 'pourashava', 'ward', 'global_address_id', 'delivery_status'] as $c) {
                if (Schema::hasColumn('orders', $c)) {
                    $cols[] = $c;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
