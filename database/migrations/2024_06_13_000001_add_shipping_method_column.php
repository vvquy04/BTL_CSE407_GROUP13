<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingMethodColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->tinyInteger('shipping_method')->default(1)->after('shipping_note')->comment('1: Standard, 2: Express');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->dropColumn('shipping_method');
        });
    }
} 