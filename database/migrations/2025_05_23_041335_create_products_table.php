<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('external_product_code')->nullable();
            $table->string('product_name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('stock');
            $table->decimal('selling_price', 12, 2);
            $table->string('company_id')->nullable(); // Optional: If you want to associate products with a company
            $table->string('image')->nullable();
            $table->text('information')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
