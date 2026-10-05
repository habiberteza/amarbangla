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
            if (!Schema::hasColumn('users', 'father_name')) {
                $table->string('father_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('users', 'agent_id')) {
                $table->unsignedBigInteger('agent_id')->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'division')) {
                $table->string('division')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable()->after('division');
            }
            if (!Schema::hasColumn('users', 'upazila')) {
                $table->string('upazila')->nullable()->after('district');
            }
            if (!Schema::hasColumn('users', 'pourashava')) {
                $table->string('pourashava')->nullable()->after('upazila');
            }
            if (!Schema::hasColumn('users', 'ward')) {
                $table->string('ward')->nullable()->after('pourashava');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('ward');
            }
            if (!Schema::hasColumn('users', 'nid_number')) {
                $table->string('nid_number')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'age',
                'agent_id',
                'division',
                'district',
                'upazila',
                'pourashava',
                'ward',
                'address',
                'nid_number',
                'image',
            ]);
        });
    }
};
