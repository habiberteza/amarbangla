<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('group_posts')) {
            Schema::create('group_posts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content');
                $table->string('image')->nullable();
                $table->string('category')->default('General');
                $table->integer('likes_count')->default(0);
                $table->enum('status', ['published', 'draft', 'archived'])->default('published');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('group_posts');
    }
};
