<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuanhuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_quanhuyen')->insert([
            // Quận huyện của TP.HCM (matp = 1)
            [
                'maqh' => 1,
                'name_quanhuyen' => 'Quận 1',
                'type' => 'Quận',
                'matp' => 1,
            ],
            [
                'maqh' => 2,
                'name_quanhuyen' => 'Quận 2',
                'type' => 'Quận',
                'matp' => 1,
            ],
            [
                'maqh' => 3,
                'name_quanhuyen' => 'Quận 3',
                'type' => 'Quận',
                'matp' => 1,
            ],
            [
                'maqh' => 4,
                'name_quanhuyen' => 'Quận 4',
                'type' => 'Quận',
                'matp' => 1,
            ],
            [
                'maqh' => 5,
                'name_quanhuyen' => 'Quận 5',
                'type' => 'Quận',
                'matp' => 1,
            ],
            // Quận huyện của Hà Nội (matp = 2)
            [
                'maqh' => 6,
                'name_quanhuyen' => 'Quận Ba Đình',
                'type' => 'Quận',
                'matp' => 2,
            ],
            [
                'maqh' => 7,
                'name_quanhuyen' => 'Quận Hoàn Kiếm',
                'type' => 'Quận',
                'matp' => 2,
            ],
            [
                'maqh' => 8,
                'name_quanhuyen' => 'Quận Đống Đa',
                'type' => 'Quận',
                'matp' => 2,
            ],
            [
                'maqh' => 9,
                'name_quanhuyen' => 'Quận Hai Bà Trưng',
                'type' => 'Quận',
                'matp' => 2,
            ],
            [
                'maqh' => 10,
                'name_quanhuyen' => 'Quận Cầu Giấy',
                'type' => 'Quận',
                'matp' => 2,
            ],
        ]);
    }
}
