<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_boys', 'division')) {
                $table->string('division')->nullable()->after('address');
            }
            if (!Schema::hasColumn('delivery_boys', 'pourashava')) {
                $table->string('pourashava')->nullable()->after('upazila');
            }
            if (!Schema::hasColumn('delivery_boys', 'ward')) {
                $table->string('ward')->nullable()->after('pourashava');
            }
            if (!Schema::hasColumn('delivery_boys', 'father_name')) {
                $table->string('father_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('delivery_boys', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('delivery_boys', 'age')) {
                $table->integer('age')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('delivery_boys', 'agent_id')) {
                $table->unsignedBigInteger('agent_id')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('delivery_boys', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0.00)->after('cash_in_hand');
            }
            if (!Schema::hasColumn('delivery_boys', 'image')) {
                $table->string('image')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('delivery_boys', 'password')) {
                $table->string('password')->nullable()->after('phone');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_boy_id')) {
                $table->unsignedBigInteger('delivery_boy_id')->nullable()->after('user_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['agent_id']);
            }
            $table->dropColumn([
                'division',
                'pourashava',
                'ward',
                'father_name',
                'mother_name',
                'age',
                'agent_id',
                'balance',
                'image',
                'password',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_boy_id']);
            $table->dropColumn('delivery_boy_id');
        });
    }
};
