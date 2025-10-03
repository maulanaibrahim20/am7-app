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
            'category_id'      => 4,
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
            'avg_daily_usage'  => 3,
            'lead_time_days'   => 5,
            'brand'            => 'Pertamina',
            'compatible_vehicles' => 'All gasoline cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 4,
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
            'category_id'      => 4,
            'sku'              => 'OLI-YML-10W50',
            'name'             => 'Yamalube 10W-50',
            'description'      => 'High performance synthetic oil for motorcycles.',
            'unit'             => 'liter',
            'purchase_price'   => 65000,
            'selling_price'    => 95000,
            'stock_quantity'   => 40,
            'min_stock'        => 15,
            'max_stock'        => 250,
            'reorder_point'    => 20,
            'reorder_quantity' => 50,
            'avg_daily_usage'  => 4,
            'lead_time_days'   => 6,
            'brand'            => 'Yamaha',
            'compatible_vehicles' => 'Motorcycles',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 4,
            'sku'              => 'OLI-HND-10W30',
            'name'             => 'Honda Genuine Oil 10W-30',
            'description'      => 'Genuine oil for Honda cars and motorcycles.',
            'unit'             => 'liter',
            'purchase_price'   => 60000,
            'selling_price'    => 90000,
            'stock_quantity'   => 50,
            'min_stock'        => 20,
            'max_stock'        => 300,
            'reorder_point'    => 25,
            'reorder_quantity' => 60,
            'avg_daily_usage'  => 5,
            'lead_time_days'   => 5,
            'brand'            => 'Honda',
            'compatible_vehicles' => 'Honda Cars & Motorcycles',
            'is_active'        => true,
        ]);

        // --- Category 5: Filter ---
        Product::create([
            'category_id'      => 5,
            'sku'              => 'FLT-OLI-TOY',
            'name'             => 'Oil Filter Toyota',
            'description'      => 'Genuine Toyota oil filter for various models.',
            'unit'             => 'pcs',
            'purchase_price'   => 45000,
            'selling_price'    => 75000,
            'stock_quantity'   => 25,
            'min_stock'        => 10,
            'max_stock'        => 100,
            'reorder_point'    => 12,
            'reorder_quantity' => 20,
            'avg_daily_usage'  => 1,
            'lead_time_days'   => 3,
            'brand'            => 'Toyota',
            'compatible_vehicles' => 'Toyota cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 5,
            'sku'              => 'FLT-ANG-MTR',
            'name'             => 'Air Filter Motorcycle',
            'description'      => 'Universal air filter for motorcycles.',
            'unit'             => 'pcs',
            'purchase_price'   => 25000,
            'selling_price'    => 50000,
            'stock_quantity'   => 35,
            'min_stock'        => 15,
            'max_stock'        => 120,
            'reorder_point'    => 18,
            'reorder_quantity' => 25,
            'avg_daily_usage'  => 2,
            'lead_time_days'   => 4,
            'brand'            => 'Universal',
            'compatible_vehicles' => 'Motorcycles',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 5,
            'sku'              => 'FLT-BNS-NIS',
            'name'             => 'Cabin Filter Nissan',
            'description'      => 'Cabin air filter for Nissan cars.',
            'unit'             => 'pcs',
            'purchase_price'   => 70000,
            'selling_price'    => 120000,
            'stock_quantity'   => 15,
            'min_stock'        => 5,
            'max_stock'        => 80,
            'reorder_point'    => 8,
            'reorder_quantity' => 15,
            'avg_daily_usage'  => 0.8,
            'lead_time_days'   => 5,
            'brand'            => 'Nissan',
            'compatible_vehicles' => 'Nissan cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 5,
            'sku'              => 'FLT-FUL-SUZ',
            'name'             => 'Fuel Filter Suzuki',
            'description'      => 'Genuine Suzuki fuel filter.',
            'unit'             => 'pcs',
            'purchase_price'   => 55000,
            'selling_price'    => 95000,
            'stock_quantity'   => 20,
            'min_stock'        => 8,
            'max_stock'        => 90,
            'reorder_point'    => 10,
            'reorder_quantity' => 20,
            'avg_daily_usage'  => 1,
            'lead_time_days'   => 4,
            'brand'            => 'Suzuki',
            'compatible_vehicles' => 'Suzuki cars',
            'is_active'        => true,
        ]);

        // --- Category 6: Service Berkala ---
        Product::create([
            'category_id'      => 6,
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

        Product::create([
            'category_id'      => 6,
            'sku'              => 'SRV-BRKCHK',
            'name'             => 'Brake Check Service',
            'description'      => 'Brake system inspection and adjustment.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 200000,
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

        Product::create([
            'category_id'      => 6,
            'sku'              => 'SRV-ACCHK',
            'name'             => 'AC Service',
            'description'      => 'Inspection, cleaning, and refilling of air conditioner system.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 400000,
            'stock_quantity'   => 0,
            'min_stock'        => 0,
            'max_stock'        => 0,
            'reorder_point'    => 0,
            'reorder_quantity' => 0,
            'avg_daily_usage'  => 0,
            'lead_time_days'   => 0,
            'brand'            => null,
            'compatible_vehicles' => 'All cars',
            'is_active'        => true,
        ]);

        // --- Category 7: Perbaikan Mesin ---
        Product::create([
            'category_id'      => 7,
            'sku'              => 'SRV-OVH',
            'name'             => 'Engine Overhaul',
            'description'      => 'Complete disassembly and rebuilding of engine.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 2500000,
            'stock_quantity'   => 0,
            'min_stock'        => 0,
            'max_stock'        => 0,
            'reorder_point'    => 0,
            'reorder_quantity' => 0,
            'avg_daily_usage'  => 0,
            'lead_time_days'   => 0,
            'brand'            => null,
            'compatible_vehicles' => 'All cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 7,
            'sku'              => 'SRV-GSKT',
            'name'             => 'Cylinder Head Gasket Replacement',
            'description'      => 'Replacement of cylinder head gasket including labor.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 1200000,
            'stock_quantity'   => 0,
            'min_stock'        => 0,
            'max_stock'        => 0,
            'reorder_point'    => 0,
            'reorder_quantity' => 0,
            'avg_daily_usage'  => 0,
            'lead_time_days'   => 0,
            'brand'            => null,
            'compatible_vehicles' => 'All cars',
            'is_active'        => true,
        ]);

        Product::create([
            'category_id'      => 7,
            'sku'              => 'SRV-INJR',
            'name'             => 'Injector Cleaning',
            'description'      => 'Professional cleaning of fuel injectors.',
            'unit'             => 'service',
            'purchase_price'   => 0,
            'selling_price'    => 800000,
            'stock_quantity'   => 0,
            'min_stock'        => 0,
            'max_stock'        => 0,
            'reorder_point'    => 0,
            'reorder_quantity' => 0,
            'avg_daily_usage'  => 0,
            'lead_time_days'   => 0,
            'brand'            => null,
            'compatible_vehicles' => 'All cars',
            'is_active'        => true,
        ]);
    }
}
