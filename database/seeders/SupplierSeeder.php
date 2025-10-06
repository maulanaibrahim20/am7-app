<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'code'           => 'SUP001',
                'name'           => 'PT Astra Otoparts',
                'contact_person' => 'Bapak Joko',
                'phone'          => '021-12345678',
                'email'          => 'joko@astra.com',
                'address'        => 'Jl. Gaya Motor III No. 5, Jakarta Timur',
                'lead_time_days' => 3,
                'is_active'      => true,
            ],
            [
                'code'           => 'SUP002',
                'name'           => 'CV Maju Jaya Sparepart',
                'contact_person' => 'Ibu Ratih',
                'phone'          => '021-87654321',
                'email'          => 'ratih@majujaya.com',
                'address'        => 'Jl. Raya Bekasi No. 88, Bekasi',
                'lead_time_days' => 2,
                'is_active'      => true,
            ],
            [
                'code'           => 'SUP003',
                'name'           => 'Toko Berkah Motor',
                'contact_person' => 'Bapak Agus',
                'phone'          => '021-55556666',
                'email'          => 'agus@berkahmotor.com',
                'address'        => 'Jl. Otomotif Raya No. 45, Tangerang',
                'lead_time_days' => 1,
                'is_active'      => true,
            ],
            [
                'code'           => 'SUP004',
                'name'           => 'PT Federal Parts Indonesia',
                'contact_person' => 'Ibu Dewi',
                'phone'          => '021-99998888',
                'email'          => 'dewi@federalparts.com',
                'address'        => 'Jl. Industri No. 100, Jakarta Utara',
                'lead_time_days' => 5,
                'is_active'      => true,
            ],
            [
                'code'           => 'SUP005',
                'name'           => 'UD Sumber Rejeki',
                'contact_person' => 'Bapak Hendra',
                'phone'          => '021-44443333',
                'email'          => 'hendra@sumberrejeki.com',
                'address'        => 'Jl. Perdagangan No. 22, Depok',
                'lead_time_days' => 2,
                'is_active'      => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
