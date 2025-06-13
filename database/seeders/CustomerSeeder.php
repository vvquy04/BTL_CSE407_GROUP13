<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_customers')->insert([
            [
                'customer_name' => 'Nguyễn Văn An',
                'customer_email' => 'nguyenvanan@gmail.com',
                'customer_phone' => '0901234567',
                'customer_password' => md5('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_name' => 'Trần Thị Bình',
                'customer_email' => 'tranthibinh@gmail.com',
                'customer_phone' => '0902345678',
                'customer_password' => md5('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_name' => 'Lê Hoàng Cường',
                'customer_email' => 'lehoangcuong@gmail.com',
                'customer_phone' => '0903456789',
                'customer_password' => md5('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_name' => 'Phạm Thị Dung',
                'customer_email' => 'phamthidung@gmail.com',
                'customer_phone' => '0904567890',
                'customer_password' => md5('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'customer_name' => 'Hoàng Văn Em',
                'customer_email' => 'hoangvanem@gmail.com',
                'customer_phone' => '0905678901',
                'customer_password' => md5('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}