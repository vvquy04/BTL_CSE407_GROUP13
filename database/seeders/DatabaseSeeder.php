<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        $this->call([
            // Địa danh (chạy trước vì có foreign key)
            TinhthanhphoSeeder::class,
            QuanhuyenSeeder::class,
            XaphuongthitranSeeder::class,
            
            // Dữ liệu chính
            AdminSeeder::class,
            CategoryProductSeeder::class,
            BranchProductSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            PaymentSeeder::class,
            ShippingSeeder::class,
            OrderSeeder::class,
            OrderDetailsSeeder::class,
            CouponSeeder::class,
            FeeShipSeeder::class, 
        ]);
    }
}