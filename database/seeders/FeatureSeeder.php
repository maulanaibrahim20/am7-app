<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feature::insert([
            [
                'icon' => 'fa fa-truck',
                'title' => 'Layanan Servis Truk Lengkap',
                'description' => 'Kami menyediakan berbagai layanan servis dan perawatan untuk semua jenis truk — mulai dari pemeriksaan rutin, ganti oli, hingga perbaikan mesin berat.',
                'link_text' => 'Lihat Layanan',
                'link_url' => '/services',
                'order' => 1,
                'background_style' => null,
                'is_active' => true,
            ],
            [
                'icon' => 'fa fa-users-cog',
                'title' => 'Tim Mekanik Profesional',
                'description' => 'Dikerjakan oleh mekanik berpengalaman dan bersertifikat yang memahami karakteristik setiap tipe truk secara mendalam.',
                'link_text' => 'Kenali Kami',
                'link_url' => '/about',
                'order' => 2,
                'background_style' => 'bg-light',
                'is_active' => true,
            ],
            [
                'icon' => 'fa fa-tools',
                'title' => 'Peralatan Modern & Lengkap',
                'description' => 'Kami menggunakan peralatan modern untuk memastikan hasil servis yang cepat, akurat, dan sesuai standar industri transportasi berat.',
                'link_text' => 'Pelajari Lebih Lanjut',
                'link_url' => '/facilities',
                'order' => 3,
                'background_style' => null,
                'is_active' => true,
            ],
        ]);
    }
}
