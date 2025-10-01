<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id'      => 2, // Oli & Pelumas
            'sku'              => 'OLI-PRT-10W40',
            'name'             => 'Oli Mesin Pertamina Fastron 10W-40',
            'description'      => 'Synthetic motor oil for gasoline engines.',
            'unit'             => 'liter',
            'purchase_price'   => 75000,
            'selling_price'    => 110000,
            'stock_quantity'   => 30,
            'min_stock'        => 10,
            'max_stock'        => 200,
            'reorder_point'    => 15,
            'reorder_quantity' => 40,
            'avg_daily_usage'  => 3.0,
            'lead_time_days'   => 5,
            'brand'            => 'Pertamina',
            'compatible_vehicles' => 'All gasoline cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 2, // Oli & Pelumas
            'sku'              => 'OLI-MBL-5W30',
            'name'             => 'Oli Mesin Mobil 1 5W-30',
            'description'      => 'Fully synthetic motor oil for high performance vehicles.',
            'unit'             => 'liter',
            'purchase_price'   => 120000,
            'selling_price'    => 165000,
            'stock_quantity'   => 20,
            'min_stock'        => 8,
            'max_stock'        => 150,
            'reorder_point'    => 10,
            'reorder_quantity' => 30,
            'avg_daily_usage'  => 2.5,
            'lead_time_days'   => 4,
            'brand'            => 'Mobil 1',
            'compatible_vehicles' => 'All gasoline cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 3,
            'sku'              => 'SRV-TUNEUP',
            'name'             => 'Tune Up Service',
            'description'      => 'General tune up service including spark plug check, oil check, and engine cleaning.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 350000,
            'stock_quantity'   => 0,
            'min_stock'        => 0,
            'max_stock'        => 0,
            'reorder_point'    => 0,
            'reorder_quantity' => 0,
            'avg_daily_usage'  => 0,
            'lead_time_days'   => 0,
            'brand'            => null,
            'compatible_vehicles' => 'All vehicles',
            'is_active'        => true,
        ]);
    }
}
