<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->decimal('price_china_cny', 15, 2);
            $table->decimal('price_indonesia_idr', 15, 2);
            $table->decimal('shipping_cost_idr', 15, 2)->default(0);
            $table->decimal('tax_estimate_idr', 15, 2)->default(0);
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('source_url_china')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};