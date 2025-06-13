<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_order')->insert([
            [
                'customer_id' => 1,
                'shipping_id' => 1,
                'payment_id' => 1,
                'order_total' => '2450000',
                'order_status' => 'Đang xử lý',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'customer_id' => 2,
                'shipping_id' => 2,
                'payment_id' => 2,
                'order_total' => '3850000',
                'order_status' => 'Đã giao hàng',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'customer_id' => 3,
                'shipping_id' => 3,
                'payment_id' => 3,
                'order_total' => '4200000',
                'order_status' => 'Đang giao hàng',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'customer_id' => 4,
                'shipping_id' => 4,
                'payment_id' => 4,
                'order_total' => '6200000',
                'order_status' => 'Đã giao hàng',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'customer_id' => 5,
                'shipping_id' => 5,
                'payment_id' => 5,
                'order_total' => '4200000',
                'order_status' => 'Đã hủy',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(6),
            ]
        ]);
    }
}