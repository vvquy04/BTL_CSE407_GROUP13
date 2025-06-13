<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class OptimizeTables extends Migration
{
    public function up()
    {
        // Optimize payment table
        Schema::table('tbl_payment', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_payment', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('tbl_payment', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_details');
            }
        });

        // Optimize order table
        Schema::table('tbl_order', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_order', 'order_code')) {
                $table->string('order_code')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('tbl_order', 'shipping_fee')) {
                $table->decimal('shipping_fee', 12, 2)->default(0)->after('order_total');
            }
            if (!Schema::hasColumn('tbl_order', 'order_note')) {
                $table->text('order_note')->nullable()->after('order_status');
            }
        });

        // Generate unique order codes for existing orders
        $orders = DB::table('tbl_order')->whereNull('order_code')->get();
        foreach ($orders as $order) {
            DB::table('tbl_order')
                ->where('order_id', $order->order_id)
                ->update([
                    'order_code' => 'ORD' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT)
                ]);
        }

        // Add unique constraint after generating codes
        Schema::table('tbl_order', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_order', 'order_code')) {
                $table->unique('order_code');
            }
        });

        // Optimize shipping table
        Schema::table('tbl_shipping', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_shipping', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            if (!Schema::hasColumn('tbl_shipping', 'shipping_street')) {
                $table->string('shipping_street')->after('shipping_name');
            }
            if (!Schema::hasColumn('tbl_shipping', 'shipping_ward')) {
                $table->string('shipping_ward')->after('shipping_street');
            }
            if (!Schema::hasColumn('tbl_shipping', 'shipping_district')) {
                $table->string('shipping_district')->after('shipping_ward');
            }
            if (!Schema::hasColumn('tbl_shipping', 'shipping_city')) {
                $table->string('shipping_city')->after('shipping_district');
            }
        });
    }

    public function down()
    {
        // Revert payment table changes
        Schema::table('tbl_payment', function (Blueprint $table) {
            $table->dropColumn(['payment_details', 'transaction_id']);
        });

        // Revert order table changes
        Schema::table('tbl_order', function (Blueprint $table) {
            $table->dropColumn(['order_code', 'shipping_fee', 'order_note']);
        });

        // Revert shipping table changes
        Schema::table('tbl_shipping', function (Blueprint $table) {
            $table->string('shipping_address')->after('shipping_name');
            $table->dropColumn(['shipping_street', 'shipping_ward', 'shipping_district', 'shipping_city']);
        });
    }
} 