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
            $table->uuid('id')->primary();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->uuid('product_category_id')->nullable();
            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
                ->nullOnDelete();

            $table->string('unit', 50)->nullable();
            $table->float('cost_price', 8)->nullable();
            $table->float('selling_price', 8)->nullable();
            $table->integer('quantity')->nullable();

            $table->string('image_name', 50)->nullable();
            $table->string('original_image_name', 50)->nullable();
            $table->string('image_extension', 50)->nullable();
            $table->string('image_mime_type', 50)->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
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
