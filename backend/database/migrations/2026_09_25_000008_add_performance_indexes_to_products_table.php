<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumns('products', ['shop_id', 'is_active'])) {
                $table->index(['shop_id', 'is_active'], 'products_shop_active_index');
            } elseif (Schema::hasColumns('products', ['sch_id', 'status'])) {
                $table->index(['sch_id', 'status'], 'products_sch_status_index');
            }
            if (Schema::hasColumns('products', ['category_id', 'is_active'])) {
                $table->index(['category_id', 'is_active'], 'products_category_active_index');
            } elseif (Schema::hasColumns('products', ['prod_cat_id', 'status'])) {
                $table->index(['prod_cat_id', 'status'], 'products_cat_status_index');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->index('price', 'products_price_index');
            } elseif (Schema::hasColumn('products', 'selling_price')) {
                $table->index('selling_price', 'products_selling_price_index');
            }
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->index('is_featured', 'products_is_featured_index');
            }
            if (Schema::hasColumn('products', 'is_express')) {
                $table->index('is_express', 'products_is_express_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_shop_active_index');
            $table->dropIndex('products_category_active_index');
            $table->dropIndex('products_price_index');
            $table->dropIndex('products_is_featured_index');
            $table->dropIndex('products_is_express_index');
        });
    }
};
