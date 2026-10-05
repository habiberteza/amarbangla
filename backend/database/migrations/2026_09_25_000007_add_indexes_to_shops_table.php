<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->index(['division', 'district', 'upazila'], 'shops_geo_index');
            $table->index('status', 'shops_status_index');
            $table->index('shop_type', 'shops_shop_type_index');
            $table->index('home_feature', 'shops_home_feature_index');
            $table->index('is_featured', 'shops_is_featured_index');
            $table->index('phone', 'shops_phone_index');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropIndex('shops_geo_index');
            $table->dropIndex('shops_status_index');
            $table->dropIndex('shops_shop_type_index');
            $table->dropIndex('shops_home_feature_index');
            $table->dropIndex('shops_is_featured_index');
            $table->dropIndex('shops_phone_index');
        });
    }
};
