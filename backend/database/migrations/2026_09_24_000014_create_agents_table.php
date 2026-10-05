<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agents')) {
            Schema::create('agents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('name');
                $table->string('phone')->unique();
                $table->string('email')->nullable();
                $table->string('nid_number')->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->string('union_ward')->nullable();
                $table->decimal('commission_rate', 5, 2)->default(0.00);
                $table->decimal('balance', 10, 2)->default(0.00);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
