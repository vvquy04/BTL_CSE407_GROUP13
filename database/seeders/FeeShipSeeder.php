<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeShipSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_feeship')->insert([
            // TP.HCM - Quận 1
            [
                'fee_matp' => 79, // TP.HCM
                'fee_maqh' => 760, // Quận 1
                'fee_xaid' => 1, // Phường Tân Định
                'fee_feeship' => '25000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 79,
                'fee_maqh' => 760,
                'fee_xaid' => 2, // Phường Đa Kao
                'fee_feeship' => '25000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 79,
                'fee_maqh' => 760,
                'fee_xaid' => 3, // Phường Bến Nghé
                'fee_feeship' => '25000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // TP.HCM - Quận 3
            [
                'fee_matp' => 79,
                'fee_maqh' => 770, // Quận 3
                'fee_xaid' => 11, // Phường 1
                'fee_feeship' => '25000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 79,
                'fee_maqh' => 770,
                'fee_xaid' => 12, // Phường 2
                'fee_feeship' => '25000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // TP.HCM - Quận Bình Thạnh
            [
                'fee_matp' => 79,
                'fee_maqh' => 765, // Quận Bình Thạnh
                'fee_xaid' => 21, // Phường 1
                'fee_feeship' => '30000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 79,
                'fee_maqh' => 765,
                'fee_xaid' => 22, // Phường 2
                'fee_feeship' => '30000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Hà Nội - Quận Ba Đình
            [
                'fee_matp' => 1, // Hà Nội
                'fee_maqh' => 1, // Quận Ba Đình
                'fee_xaid' => 31, // Phường Phúc Xá
                'fee_feeship' => '35000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 1,
                'fee_maqh' => 1,
                'fee_xaid' => 32, // Phường Trúc Bạch
                'fee_feeship' => '35000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Hà Nội - Quận Hoàn Kiếm
            [
                'fee_matp' => 1,
                'fee_maqh' => 2, // Quận Hoàn Kiếm
                'fee_xaid' => 41, // Phường Phúc Tân
                'fee_feeship' => '35000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 1,
                'fee_maqh' => 2,
                'fee_xaid' => 42, // Phường Đồng Xuân
                'fee_feeship' => '35000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Đà Nẵng - Quận Hải Châu
            [
                'fee_matp' => 48, // Đà Nẵng
                'fee_maqh' => 492, // Quận Hải Châu
                'fee_xaid' => 51, // Phường Thanh Bình
                'fee_feeship' => '45000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'fee_matp' => 48,
                'fee_maqh' => 492,
                'fee_xaid' => 52, // Phường Thuận Phước
                'fee_feeship' => '45000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Mặc định cho các tỉnh khác
            [
                'fee_matp' => 0, // Tỉnh khác
                'fee_maqh' => 0, // Quận/huyện khác
                'fee_xaid' => 0, // Xã/phường khác
                'fee_feeship' => '60000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}