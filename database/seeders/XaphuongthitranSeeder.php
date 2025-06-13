<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XaphuongthitranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_xaphuongthitran')->insert([
            // Phường của Quận 1 (maqh = 1)
            [
                'xaid' => 1,
                'name_xaphuong' => 'Phường Bến Nghé',
                'type' => 'Phường',
                'maqh' => 1,
            ],
            [
                'xaid' => 2,
                'name_xaphuong' => 'Phường Bến Thành',
                'type' => 'Phường',
                'maqh' => 1,
            ],
            [
                'xaid' => 3,
                'name_xaphuong' => 'Phường Cầu Kho',
                'type' => 'Phường',
                'maqh' => 1,
            ],
            [
                'xaid' => 4,
                'name_xaphuong' => 'Phường Cầu Ông Lãnh',
                'type' => 'Phường',
                'maqh' => 1,
            ],
            [
                'xaid' => 5,
                'name_xaphuong' => 'Phường Cô Giang',
                'type' => 'Phường',
                'maqh' => 1,
            ],
            // Phường của Quận 2 (maqh = 2)
            [
                'xaid' => 6,
                'name_xaphuong' => 'Phường An Phú',
                'type' => 'Phường',
                'maqh' => 2,
            ],
            [
                'xaid' => 7,
                'name_xaphuong' => 'Phường Thảo Điền',
                'type' => 'Phường',
                'maqh' => 2,
            ],
            [
                'xaid' => 8,
                'name_xaphuong' => 'Phường Bình An',
                'type' => 'Phường',
                'maqh' => 2,
            ],
            // Phường của Quận Ba Đình, Hà Nội (maqh = 6)
            [
                'xaid' => 9,
                'name_xaphuong' => 'Phường Phúc Xá',
                'type' => 'Phường',
                'maqh' => 6,
            ],
            [
                'xaid' => 10,
                'name_xaphuong' => 'Phường Trúc Bạch',
                'type' => 'Phường',
                'maqh' => 6,
            ],
        ]);
    }
}
