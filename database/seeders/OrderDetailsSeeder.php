<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderDetailsSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_order_details')->insert([
            // Order 1
            [
                'order_id' => 1,
                'product_id' => 1,
                'product_name' => 'Casio MTP-1374D-1AVDF',
                'product_price' => '2450000',
                'product_sales_quanlity' => 1,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            
            // Order 2
            [
                'order_id' => 2,
                'product_id' => 2,
                'product_name' => 'Citizen BM7108-14E',
                'product_price' => '3850000',
                'product_sales_quanlity' => 1,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            
            // Order 3
            [
                'order_id' => 3,
                'product_id' => 3,
                'product_name' => 'Seiko SRPD36K1',
                'product_price' => '4200000',
                'product_sales_quanlity' => 1,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            // Order 4 - Multiple items
            [
                'order_id' => 4,
                'product_id' => 4,
                'product_name' => 'Casio LTP-V007D-7EUDF',
                'product_price' => '1650000',
                'product_sales_quanlity' => 2,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'order_id' => 4,
                'product_id' => 6,
                'product_name' => 'Fossil ES4432',
                'product_price' => '2850000',
                'product_price' => '2850000',
                'product_sales_quanlity' => 1,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            
            // Order 5
            [
                'order_id' => 5,
                'product_id' => 11,
                'product_name' => 'Casio Couple MTP-1130A & LTP-1130A',
                'product_price' => '4200000',
                'product_sales_quanlity' => 1,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ]
        ]);
    }
}