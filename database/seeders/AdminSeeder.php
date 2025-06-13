<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_admin')->insert([
            [
                'admin_email' => 'admin@watchstore.com',
                'admin_password' => md5('admin123'),
                'admin_name' => 'Quản trị viên chính',
                'admin_phone' => '0901234567',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'admin_email' => 'manager@watchstore.com',
                'admin_password' => md5('manager123'),
                'admin_name' => 'Trần Văn Nam',
                'admin_phone' => '0902345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'admin_email' => 'staff@watchstore.com',
                'admin_password' => md5('staff123'),
                'admin_name' => 'Nguyễn Thị Hoa',
                'admin_phone' => '0903456789',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}