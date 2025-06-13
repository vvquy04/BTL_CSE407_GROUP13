<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShippingSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_shipping')->insert([
            [
                'shipping_name' => 'Nguyễn Văn An',
                'shipping_address' => '123 Nguyễn Trãi, Quận 1, TP.HCM',
                'shipping_phone' => '0901234567',
                'shipping_email' => 'nguyenvanan@gmail.com',
                'shipping_note' => 'Giao hàng giờ hành chính, gọi trước 15 phút',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'shipping_name' => 'Trần Thị Bình',
                'shipping_address' => '456 Lê Lợi, Quận 3, TP.HCM',
                'shipping_phone' => '0902345678',
                'shipping_email' => 'tranthibinh@gmail.com',
                'shipping_note' => 'Để hàng với bảo vệ nếu không có người',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'shipping_name' => 'Lê Hoàng Cường',
                'shipping_address' => '789 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'shipping_phone' => '0903456789',
                'shipping_email' => 'lehoangcuong@gmail.com',
                'shipping_note' => 'Giao hàng buổi tối sau 18h',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'shipping_name' => 'Phạm Thị Dung',
                'shipping_address' => '321 Võ Văn Tần, Quận 3, TP.HCM',
                'shipping_phone' => '0904567890',
                'shipping_email' => 'phamthidung@gmail.com',
                'shipping_note' => 'Kiểm tra kỹ hàng trước khi giao',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'shipping_name' => 'Hoàng Văn Em',
                'shipping_address' => '654 Cách Mạng Tháng 8, Quận 10, TP.HCM',
                'shipping_phone' => '0905678901',
                'shipping_email' => 'hoangvanem@gmail.com',
                'shipping_note' => 'Gọi điện xác nhận trước khi giao',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}