<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_phone');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_type')->default('customer'); // customer, seller, agent, delivery_boy, custom
            $table->text('message');
            $table->string('gateway')->default('GreenWeb');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent');
            $table->text('response_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
