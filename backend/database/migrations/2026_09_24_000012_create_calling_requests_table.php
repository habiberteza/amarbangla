<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('calling_requests')) {
            Schema::create('calling_requests', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('phone');
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->string('product_name')->nullable();
                $table->text('notes')->nullable();
                $table->text('admin_notes')->nullable();
                $table->enum('status', ['pending', 'called', 'completed', 'cancelled'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('calling_requests');
    }
};
