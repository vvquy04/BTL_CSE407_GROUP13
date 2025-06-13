<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BranchProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_branch_product')->insert([
            [
                'branch_name' => 'Casio',
                'branch_desc' => 'Thương hiệu đồng hồ Nhật Bản nổi tiếng với công nghệ hiện đại và độ bền cao',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Citizen',
                'branch_desc' => 'Thương hiệu đồng hồ Nhật Bản với công nghệ Eco-Drive độc đáo',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Seiko',
                'branch_desc' => 'Thương hiệu đồng hồ Nhật Bản với lịch sử lâu đời và chất lượng vượt trội',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Orient',
                'branch_desc' => 'Thương hiệu đồng hồ cơ Nhật Bản với thiết kế cổ điển và hiện đại',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Daniel Wellington',
                'branch_desc' => 'Thương hiệu đồng hồ Thụy Điển với thiết kế tối giản và thanh lịch',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Fossil',
                'branch_desc' => 'Thương hiệu đồng hồ Mỹ với thiết kế thời trang và hiện đại',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'branch_name' => 'Tissot',
                'branch_desc' => 'Thương hiệu đồng hồ Thụy Sỹ cao cấp với truyền thống làm đồng hồ lâu đời',
                'branch_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}