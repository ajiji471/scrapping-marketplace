<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('marketplace_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('marketplace');
            $table->decimal('current_price', 15, 2);
            $table->decimal('original_price', 15, 2)->nullable();
            $table->integer('sold_count')->default(0);
            $table->decimal('rating', 2, 1)->nullable();
            $table->integer('review_count')->default(0);
            $table->integer('wishlist_count')->default(0);
            $table->integer('search_volume_estimate')->default(0);
            $table->timestamp('scraped_at');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('marketplace_data');
    }
};