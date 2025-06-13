<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingFeeAndDeliveryColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('shipping_method');
            $table->timestamp('estimated_delivery')->nullable()->after('shipping_fee');
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
            $table->dropColumn(['shipping_fee', 'estimated_delivery']);
        });
    }
} 