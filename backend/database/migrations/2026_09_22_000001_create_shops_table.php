<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->enum('type', ['regular', 'express', 'local', 'food'])->default('regular');
                $table->string('logo')->nullable();
                $table->string('banner')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->decimal('commission_rate', 5, 2)->default(5.00);
                $table->decimal('rating', 3, 2)->default(5.00);
                $table->boolean('is_featured')->default(false);
                $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
