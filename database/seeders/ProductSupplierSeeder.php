<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_suppliers')->insert([
            [
                'product_id' => 1,
                'supplier_id' => 1,
                'supplier_price' => 75000,
                'min_order_qty' => 10,
                'is_primary' => true,
            ],
            [
                'product_id' => 1,
                'supplier_id' => 2,
                'supplier_price' => 77000,
                'min_order_qty' => 5,
                'is_primary' => false,
            ],
            [
                'product_id' => 2,
                'supplier_id' => 1,
                'supplier_price' => 65000,
                'min_order_qty' => 10,
                'is_primary' => true,
            ],
            [
                'product_id' => 2,
                'supplier_id' => 3,
                'supplier_price' => 67000,
                'min_order_qty' => 5,
                'is_primary' => false,
            ],
            [
                'product_id' => 3,
                'supplier_id' => 1,
                'supplier_price' => 35000,
                'min_order_qty' => 20,
                'is_primary' => true,
            ],
            [
                'product_id' => 3,
                'supplier_id' => 2,
                'supplier_price' => 36000,
                'min_order_qty' => 10,
                'is_primary' => false,
            ],
            [
                'product_id' => 4,
                'supplier_id' => 4,
                'supplier_price' => 45000,
                'min_order_qty' => 15,
                'is_primary' => true,
            ],
            [
                'product_id' => 5,
                'supplier_id' => 1,
                'supplier_price' => 120000,
                'min_order_qty' => 10,
                'is_primary' => true,
            ],
            [
                'product_id' => 5,
                'supplier_id' => 5,
                'supplier_price' => 118000,
                'min_order_qty' => 5,
                'is_primary' => false,
            ],
            [
                'product_id' => 6,
                'supplier_id' => 4,
                'supplier_price' => 180000,
                'min_order_qty' => 8,
                'is_primary' => true,
            ],
            [
                'product_id' => 7,
                'supplier_id' => 1,
                'supplier_price' => 450000,
                'min_order_qty' => 5,
                'is_primary' => true,
            ],
            [
                'product_id' => 8,
                'supplier_id' => 2,
                'supplier_price' => 550000,
                'min_order_qty' => 3,
                'is_primary' => true,
            ],
            [
                'product_id' => 9,
                'supplier_id' => 4,
                'supplier_price' => 520000,
                'min_order_qty' => 4,
                'is_primary' => true,
            ],
            [
                'product_id' => 10,
                'supplier_id' => 4,
                'supplier_price' => 780000,
                'min_order_qty' => 4,
                'is_primary' => true,
            ],
        ]);
    }
}
