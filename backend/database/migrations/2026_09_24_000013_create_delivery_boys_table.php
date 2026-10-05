<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('delivery_boys')) {
            Schema::create('delivery_boys', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('name');
                $table->string('phone')->unique();
                $table->string('email')->nullable();
                $table->string('nid_number')->nullable();
                $table->enum('vehicle_type', ['bicycle', 'motorcycle', 'van', 'walking'])->default('motorcycle');
                $table->text('address')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->decimal('cash_in_hand', 10, 2)->default(0.00);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_boys');
    }
};
