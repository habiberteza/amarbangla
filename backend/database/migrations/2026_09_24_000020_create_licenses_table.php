<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('licenses')) {
            Schema::create('licenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shop_id')->nullable()->index();
                $table->string('shop_name')->nullable();
                $table->string('vendor_name')->nullable();
                $table->string('license_type')->default('Trade License'); // Trade License, Fire License, BSTI, Drug License, TIN
                $table->string('license_number');
                $table->string('document_file')->nullable();
                $table->date('issue_date')->nullable();
                $table->date('expiry_date')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
