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
            if (!Schema::hasColumn('sales', 'change_amount')) {
                $table->decimal('change_amount', 15, 2)->after('total_amount')->nullable();
            }

            if (!Schema::hasColumn('sales', 'cash_amount')) {
                $table->decimal('cash_amount', 15, 2)->after('total_amount')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'cash_amount')) {
                $table->dropColumn('cash_amount');
            }

            if (Schema::hasColumn('sales', 'change_amount')) {
                $table->dropColumn('change_amount');
            }
        });
    }
};
