<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_payment')->insert([
            [
                'payment_method' => 'Thanh toán khi nhận hàng (COD)',
                'payment_status' => 'Chờ thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_method' => 'Chuyển khoản ngân hàng',
                'payment_status' => 'Đã thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_method' => 'Thanh toán bằng thẻ ATM',
                'payment_status' => 'Đã thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_method' => 'Ví điện tử MoMo',
                'payment_status' => 'Đã thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'payment_method' => 'Thanh toán khi nhận hàng (COD)',
                'payment_status' => 'Đã thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}