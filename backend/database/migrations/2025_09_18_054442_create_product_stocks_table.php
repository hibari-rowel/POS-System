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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('supplier_name')->nullable();
            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->float('price', 8)->nullable();
            $table->integer('quantity')->nullable();
            $table->float('subtotal', 8)->nullable();
            $table->dateTime('stock_date')->nullable();
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
        Schema::dropIfExists('product_stocks');
    }
};
