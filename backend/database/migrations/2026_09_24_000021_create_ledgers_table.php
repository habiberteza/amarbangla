<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('txn_number')->unique();
            $table->enum('type', ['credit', 'debit']); // credit (inflow), debit (outflow)
            $table->string('category')->default('order_income'); // order_income, shop_payout, agent_commission, delivery_fee, refund, platform_fee, adjustment
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2)->default(0.00);
            $table->string('party_type')->default('shop'); // shop, customer, agent, delivery_boy, platform
            $table->string('party_name')->nullable();
            $table->string('reference_id')->nullable(); // Order #, Payout ID, etc.
            $table->string('payment_method')->nullable(); // bKash, Nagad, Bank, Cash, Rocket
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
