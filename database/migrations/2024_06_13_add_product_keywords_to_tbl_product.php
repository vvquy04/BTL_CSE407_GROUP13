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
        Schema::table('tbl_product', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_product', 'product_keywords')) {
                $table->text('product_keywords')->nullable()->after('product_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_product', 'product_keywords')) {
                $table->dropColumn('product_keywords');
            }
        });
    }
}; 