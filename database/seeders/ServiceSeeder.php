<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'category_id'       => 6,
                'code'              => 'SRV-GANTI-OLI',
                'name'              => 'Ganti Oli Mesin',
                'description'       => 'Penggantian oli mesin dan filter oli',
                'base_price'        => 50000,
                'estimated_duration' => 30,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 6,
                'code'              => 'SRV-TUNE-UP',
                'name'              => 'Tune Up',
                'description'       => 'Tune up mesin lengkap (busi, filter, dll)',
                'base_price'        => 150000,
                'estimated_duration' => 90,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 6,
                'code'              => 'SRV-SERVICE-10K',
                'name'              => 'Service 10.000 KM',
                'description'       => 'Service berkala 10.000 km',
                'base_price'        => 200000,
                'estimated_duration' => 120,
                'vehicle_type'      => 'car',
                'is_active'         => true,
            ],
            [
                'category_id'       => 7,
                'code'              => 'SRV-OVERHAUL',
                'name'              => 'Overhaul Mesin',
                'description'       => 'Overhaul mesin komplit',
                'base_price'        => 3500000,
                'estimated_duration' => 480,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 7,
                'code'              => 'SRV-TURUN-MESIN',
                'name'              => 'Turun Mesin',
                'description'       => 'Turun mesin ringan',
                'base_price'        => 1500000,
                'estimated_duration' => 360,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 8,
                'code'              => 'SRV-GANTI-KAMPAS',
                'name'              => 'Ganti Kampas Rem',
                'description'       => 'Penggantian kampas rem depan atau belakang',
                'base_price'        => 100000,
                'estimated_duration' => 60,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 8,
                'code'              => 'SRV-BLEEDING-REM',
                'name'              => 'Bleeding Rem',
                'description'       => 'Bleeding minyak rem',
                'base_price'        => 75000,
                'estimated_duration' => 45,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 9,
                'code'              => 'SRV-SERVICE-AC',
                'name'              => 'Service AC',
                'description'       => 'Service AC (cuci evaporator, cek freon)',
                'base_price'        => 250000,
                'estimated_duration' => 90,
                'vehicle_type'      => 'car',
                'is_active'         => true,
            ],
            [
                'category_id'       => 9,
                'code'              => 'SRV-ISI-FREON',
                'name'              => 'Isi Freon AC',
                'description'       => 'Pengisian freon AC mobil',
                'base_price'        => 300000,
                'estimated_duration' => 30,
                'vehicle_type'      => 'car',
                'is_active'         => true,
            ],
            [
                'category_id'       => 10,
                'code'              => 'SRV-GANTI-AKI',
                'name'              => 'Ganti Aki',
                'description'       => 'Penggantian aki mobil',
                'base_price'        => 50000,
                'estimated_duration' => 20,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
            [
                'category_id'       => 10,
                'code'              => 'SRV-CEK-KELISTRIKAN',
                'name'              => 'Cek Kelistrikan',
                'description'       => 'Pengecekan sistem kelistrikan lengkap',
                'base_price'        => 100000,
                'estimated_duration' => 60,
                'vehicle_type'      => 'both',
                'is_active'         => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
