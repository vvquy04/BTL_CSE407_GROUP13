<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_coupon')->insert([
            [
                'coupon_name' => 'Giảm giá 10% đơn hàng đầu tiên',
                'coupon_code' => 'FIRST10',
                'coupon_time' => 10, // 10%
                'coupon_number' => 100, // Số lượng mã giảm giá
                'coupon_condition' => 1, // 1 = phần trăm, 0 = số tiền cố định
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'coupon_name' => 'Giảm 500,000đ cho đơn từ 5 triệu',
                'coupon_code' => 'SAVE500K',
                'coupon_time' => 500000, // 500,000 VND
                'coupon_number' => 50,
                'coupon_condition' => 0, // Số tiền cố định
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'coupon_name' => 'Giảm 15% ngày Valentine',
                'coupon_code' => 'VALENTINE15',
                'coupon_time' => 15, // 15%
                'coupon_number' => 200,
                'coupon_condition' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'coupon_name' => 'Giảm 20% Black Friday',
                'coupon_code' => 'BLACKFRIDAY20',
                'coupon_time' => 20, // 20%
                'coupon_number' => 500,
                'coupon_condition' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'coupon_name' => 'Miễn phí ship toàn quốc',
                'coupon_code' => 'FREESHIP',
                'coupon_time' => 50000, // 50,000 VND (phí ship)
                'coupon_number' => 300,
                'coupon_condition' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}