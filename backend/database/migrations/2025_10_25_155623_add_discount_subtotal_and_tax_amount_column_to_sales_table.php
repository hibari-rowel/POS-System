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
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->after('user_id')->nullable();
            }

            if (!Schema::hasColumn('sales', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->after('user_id')->nullable();
            }

            if (!Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 15, 2)->after('user_id')->nullable();
            }

            if (Schema::hasColumn('sales', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->dropColumn('subtotal_amount');
            }

            if (Schema::hasColumn('sales', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }

            if (Schema::hasColumn('sales', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }

            if (Schema::hasColumn('sales', 'total_amount')) {
                $table->float('total_amount', 8)->change();
            }
        });
    }
};
