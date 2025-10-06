<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Oli & Pelumas',
                'slug'        => 'oli-pelumas',
                'description' => 'Produk oli mesin, oli transmisi, dan pelumas',
                'type'        => 'product',
                'is_active'   => true,
            ],
            [
                'name'        => 'Filter',
                'slug'        => 'filter',
                'description' => 'Filter oli, filter udara, filter bensin',
                'type'        => 'product',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kampas Rem',
                'slug'        => 'kampas-rem',
                'description' => 'Kampas rem depan dan belakang',
                'type'        => 'product',
                'is_active'   => true,
            ],
            [
                'name'        => 'Aki & Baterai',
                'slug'        => 'aki-baterai',
                'description' => 'Aki kering dan basah berbagai merk',
                'type'        => 'product',
                'is_active'   => true,
            ],
            [
                'name'        => 'Ban & Velg',
                'slug'        => 'ban-velg',
                'description' => 'Ban dan velg berbagai ukuran',
                'type'        => 'product',
                'is_active'   => true,
            ],
            [
                'name'        => 'Service Berkala',
                'slug'        => 'service-berkala',
                'description' => 'Perawatan rutin kendaraan',
                'type'        => 'service',
                'is_active'   => true,
            ],
            [
                'name'        => 'Perbaikan Mesin',
                'slug'        => 'perbaikan-mesin',
                'description' => 'Service dan perbaikan mesin kendaraan',
                'type'        => 'service',
                'is_active'   => true,
            ],
            [
                'name'        => 'Sistem Rem',
                'slug'        => 'sistem-rem',
                'description' => 'Perbaikan dan penggantian sistem rem',
                'type'        => 'service',
                'is_active'   => true,
            ],
            [
                'name'        => 'AC Mobil',
                'slug'        => 'ac-mobil',
                'description' => 'Service dan perbaikan AC mobil',
                'type'        => 'service',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kelistrikan',
                'slug'        => 'kelistrikan',
                'description' => 'Perbaikan sistem kelistrikan kendaraan',
                'type'        => 'service',
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
