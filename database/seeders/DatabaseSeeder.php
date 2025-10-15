<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Feature, User};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ProductSupplierSeeder::class,
            CarouselSeeder::class,
            SiteSettingSeeder::class,
            AboutSectionSeeder::class,
            FeatureSeeder::class
        ]);
    }
}
