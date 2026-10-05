<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('customer_type_id')->nullable()->constrained('customer_types')->nullOnDelete();
                $table->string('name');
                $table->string('phone')->index();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->decimal('balance', 10, 2)->default(0.00);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
