<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_category_product')->insert([
            [
                'category_name' => 'Đồng hồ nam',
                'category_desc' => 'Các mẫu đồng hồ dành cho nam giới, thiết kế mạnh mẽ và thanh lịch',
                'category_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_name' => 'Đồng hồ nữ',
                'category_desc' => 'Các mẫu đồng hồ dành cho nữ giới, thiết kế tinh tế và sang trọng',
                'category_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_name' => 'Đồng hồ thể thao',
                'category_desc' => 'Các mẫu đồng hồ chống nước, phù hợp cho hoạt động thể thao',
                'category_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_name' => 'Đồng hồ thông minh',
                'category_desc' => 'Smartwatch với nhiều tính năng hiện đại và kết nối thông minh',
                'category_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}