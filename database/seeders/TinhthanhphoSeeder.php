<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinhthanhphoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_tinhthanhpho')->insert([
            [
                'matp' => 1,
                'name_city' => 'Thành phố Hồ Chí Minh',
                'type' => 'Thành phố Trung ương',
            ],
            [
                'matp' => 2,
                'name_city' => 'Hà Nội',
                'type' => 'Thành phố Trung ương',
            ],
            [
                'matp' => 3,
                'name_city' => 'Hải Phòng',
                'type' => 'Thành phố Trung ương',
            ],
            [
                'matp' => 4,
                'name_city' => 'Đà Nẵng',
                'type' => 'Thành phố Trung ương',
            ],
            [
                'matp' => 5,
                'name_city' => 'Cần Thơ',
                'type' => 'Thành phố Trung ương',
            ],
            [
                'matp' => 6,
                'name_city' => 'An Giang',
                'type' => 'Tỉnh',
            ],
            [
                'matp' => 7,
                'name_city' => 'Bà Rịa - Vũng Tàu',
                'type' => 'Tỉnh',
            ],
            [
                'matp' => 8,
                'name_city' => 'Bình Dương',
                'type' => 'Tỉnh',
            ],
            [
                'matp' => 9,
                'name_city' => 'Bình Phước',
                'type' => 'Tỉnh',
            ],
            [
                'matp' => 10,
                'name_city' => 'Đồng Nai',
                'type' => 'Tỉnh',
            ],
        ]);
    }
}
