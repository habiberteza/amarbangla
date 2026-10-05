<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->string('post_code')->nullable();
            $table->decimal('delivery_charge', 8, 2)->default(60.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_addresses');
    }
};
