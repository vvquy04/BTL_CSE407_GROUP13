<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_product')->insert([
            // Đồng hồ nam
            [
                'category_id' => 1,
                'branch_id' => 1,
                'product_name' => 'Casio MTP-1374D-1AVDF',
                'product_desc' => 'Đồng hồ nam Casio với thiết kế thanh lịch, mặt số màu đen sang trọng',
                'product_content' => 'Đồng hồ nam Casio MTP-1374D-1AVDF với vỏ thép không gỉ, kính khoáng chất, chống nước 50m. Thiết kế cổ điển phù hợp cho môi trường công sở.',
                'product_price' => '2450000',
                'product_image' => 'casio-mtp-1374d.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 1,
                'branch_id' => 2,
                'product_name' => 'Citizen BM7108-14E',
                'product_desc' => 'Đồng hồ nam Citizen Eco-Drive với năng lượng ánh sáng',
                'product_content' => 'Đồng hồ nam Citizen BM7108-14E sử dụng công nghệ Eco-Drive, không cần thay pin. Vỏ thép không gỉ, dây da cao cấp, chống nước 100m.',
                'product_price' => '3850000',
                'product_image' => 'citizen-bm7108.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 1,
                'branch_id' => 3,
                'product_name' => 'Seiko SRPD36K1',
                'product_desc' => 'Đồng hồ cơ tự động Seiko 5 Sports',
                'product_content' => 'Đồng hồ cơ tự động Seiko SRPD36K1 với bộ máy 4R36, trữ cót 41 giờ. Thiết kế thể thao năng động, phù hợp cho mọi hoạt động.',
                'product_price' => '4200000',
                'product_image' => 'seiko-srpd36k1.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Đồng hồ nữ
            [
                'category_id' => 2,
                'branch_id' => 1,
                'product_name' => 'Casio LTP-V007D-7EUDF',
                'product_desc' => 'Đồng hồ nữ Casio với thiết kế nhỏ gọn và tinh tế',
                'product_content' => 'Đồng hồ nữ Casio LTP-V007D-7EUDF với vỏ thép không gỉ mạ vàng, mặt số trắng thanh lịch. Dây da màu nâu sang trọng.',
                'product_price' => '1650000',
                'product_image' => 'casio-ltp-v007d.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 2,
                'branch_id' => 5,
                'product_name' => 'Daniel Wellington Classic Petite',
                'product_desc' => 'Đồng hồ nữ Daniel Wellington với thiết kế tối giản',
                'product_content' => 'Đồng hồ nữ Daniel Wellington Classic Petite với mặt số trắng sạch sẽ, dây da thật cao cấp. Thiết kế Scandinavian tối giản và sang trọng.',
                'product_price' => '3200000',
                'product_image' => 'dw-classic-petite.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 2,
                'branch_id' => 6,
                'product_name' => 'Fossil ES4432',
                'product_desc' => 'Đồng hồ nữ Fossil với thiết kế thời trang hiện đại',
                'product_content' => 'Đồng hồ nữ Fossil ES4432 với vỏ thép không gỉ mạ vàng hồng, mặt số có kim cương nhỏ. Dây thép mesh sang trọng.',
                'product_price' => '2850000',
                'product_image' => 'fossil-es4432.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Đồng hồ thể thao
            [
                'category_id' => 3,
                'branch_id' => 1,
                'product_name' => 'Casio G-Shock GA-2100-1A1DR',
                'product_desc' => 'Đồng hồ thể thao G-Shock với thiết kế Carbon Core Guard',
                'product_content' => 'Đồng hồ G-Shock GA-2100-1A1DR với cấu trúc Carbon Core Guard siêu bền, chống sốc và chống nước 200m. Thiết kế octagon độc đáo.',
                'product_price' => '3450000',
                'product_image' => 'gshock-ga2100.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 3,
                'branch_id' => 2,
                'product_name' => 'Citizen Promaster BN0150-28E',
                'product_desc' => 'Đồng hồ lặn Citizen Promaster chuyên nghiệp',
                'product_content' => 'Đồng hồ lặn Citizen Promaster BN0150-28E với khả năng chống nước 300m, vòng bezel xoay một chiều. Sử dụng công nghệ Eco-Drive.',
                'product_price' => '5200000',
                'product_image' => 'citizen-promaster.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Đồng hồ thông minh
            [
                'category_id' => 4,
                'branch_id' => 6,
                'product_name' => 'Fossil Gen 5 Smartwatch',
                'product_desc' => 'Đồng hồ thông minh Fossil với Wear OS by Google',
                'product_content' => 'Đồng hồ thông minh Fossil Gen 5 với chip Snapdragon Wear 3100, GPS, NFC, theo dõi sức khỏe. Tương thích với Android và iOS.',
                'product_price' => '6500000',
                'product_image' => 'fossil-gen5.jpg',
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
        ]);
    }
}