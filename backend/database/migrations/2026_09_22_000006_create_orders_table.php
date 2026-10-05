<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->string('customer_email')->nullable();
                $table->text('shipping_address');
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount', 10, 2)->default(0.00);
                $table->decimal('shipping_fee', 10, 2)->default(60.00);
                $table->decimal('total_amount', 10, 2);
                $table->enum('payment_method', ['cod', 'bkash', 'nagad', 'rocket', 'bank'])->default('cod');
                $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
                $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
