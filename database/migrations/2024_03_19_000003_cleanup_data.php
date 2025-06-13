<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupData extends Migration
{
    public function up()
    {
        // Cleanup test customers
        DB::table('tbl_customers')
            ->where('customer_email', 'like', '%@gmail.com')
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();

        // Cleanup test orders
        DB::table('tbl_order')
            ->where('order_status', '=', 'Đang chờ xử lý')
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();

        // Cleanup expired coupons
        DB::table('tbl_coupon')
            ->where('coupon_time', '<=', 0)
            ->orWhere('created_at', '<', Carbon::now()->subMonths(3))
            ->delete();

        // Generate order codes for existing orders
        $orders = DB::table('tbl_order')->get();
        foreach ($orders as $order) {
            $updateData = [
                'order_code' => 'ORD' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT)
            ];
            // Only update shipping_fee if property exists
            if (property_exists($order, 'shipping_city') && !empty($order->shipping_city)) {
                $fee = DB::table('tbl_feeship')
                    ->where('fee_matp', function($query) use ($order) {
                        $query->select('matp')
                            ->from('tbl_tinhthanhpho')
                            ->where('name_city', 'like', '%' . $order->shipping_city . '%')
                            ->first();
                    })
                    ->value('fee_feeship') ?? 0;
                $updateData['shipping_fee'] = $fee;
            }
            DB::table('tbl_order')
                ->where('order_id', $order->order_id)
                ->update($updateData);
        }

        // Update payment details
        $payments = DB::table('tbl_payment')->get();
        foreach ($payments as $payment) {
            $order = DB::table('tbl_order')
                ->where('payment_id', $payment->payment_id)
                ->first();

            if ($order) {
                DB::table('tbl_payment')
                    ->where('payment_id', $payment->payment_id)
                    ->update([
                        'payment_amount' => $order->order_total,
                        'payment_details' => json_encode([
                            'order_id' => $order->order_id,
                            'order_code' => $order->order_code,
                            'payment_method' => $payment->payment_method
                        ]),
                        'transaction_id' => 'TXN' . str_pad($payment->payment_id, 6, '0', STR_PAD_LEFT)
                    ]);
            }
        }
    }

    public function down()
    {
        // Cannot restore deleted data
        // This is a one-way migration
    }
} 