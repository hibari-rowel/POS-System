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
        Schema::table('product_sales', function (Blueprint $table) {
            if (Schema::hasColumn('product_sales', 'quantity')) {
                $table->decimal('quantity', 15, 2)->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'price')) {
                $table->decimal('price', 15, 2)->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'discount')) {
                $table->decimal('discount', 15, 2)->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            if (Schema::hasColumn('product_sales', 'quantity')) {
                $table->integer('quantity')->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'price')) {
                $table->float('price', 8)->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'discount')) {
                $table->float('discount', 2)->nullable()->change();
            }

            if (Schema::hasColumn('product_sales', 'subtotal')) {
                $table->float('subtotal', 8)->nullable()->change();
            }
        });
    }
};
