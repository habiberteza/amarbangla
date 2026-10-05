<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'balance')) {
                $table->decimal('balance', 12, 2)->default(0.00);
            }
            if (!Schema::hasColumn('shops', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('shops', 'pourashava')) {
                $table->string('pourashava')->nullable();
            }
            if (!Schema::hasColumn('shops', 'ward')) {
                $table->string('ward')->nullable();
            }
            if (!Schema::hasColumn('shops', 'house_address')) {
                $table->text('house_address')->nullable();
            }
            if (!Schema::hasColumn('shops', 'comment')) {
                $table->text('comment')->nullable();
            }
            if (!Schema::hasColumn('shops', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->index();
            }
            if (!Schema::hasColumn('shops', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable()->index();
            }
            if (!Schema::hasColumn('shops', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('shops', 'sub_category')) {
                $table->string('sub_category')->nullable();
            }
            if (!Schema::hasColumn('shops', 'shop_type')) {
                $table->string('shop_type')->nullable();
            }
            if (!Schema::hasColumn('shops', 'priority_type')) {
                $table->string('priority_type')->nullable();
            }
            if (!Schema::hasColumn('shops', 'home_feature')) {
                $table->boolean('home_feature')->default(false);
            }
            if (!Schema::hasColumn('shops', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn([
                'balance',
                'image',
                'pourashava',
                'ward',
                'house_address',
                'comment',
                'category_id',
                'sub_category_id',
                'category',
                'sub_category',
                'shop_type',
                'priority_type',
                'home_feature',
                'password',
            ]);
        });
    }
};
