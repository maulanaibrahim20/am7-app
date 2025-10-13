<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{AboutFeature, AboutSection};

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $about = AboutSection::create([
            'subtitle' => '// Tentang Kami //',
            'title' => 'AM7 — Pusat Servis dan Perawatan Truk Terpercaya',
            'description' => 'AM7 hadir sebagai bengkel profesional yang berfokus pada perawatan dan perbaikan truk berbagai jenis. Dengan pengalaman bertahun-tahun dan dukungan tim mekanik ahli, kami memastikan setiap unit truk Anda tetap dalam kondisi prima untuk menunjang operasional bisnis Anda.',
            'image' => 'landing/img/about.jpg',
            'experience_years' => 15,
            'experience_label' => 'Tahun Pengalaman',
            'button_text' => 'Pelajari Lebih Lanjut',
            'button_url' => '/about',
        ]);

        AboutFeature::insert([
            [
                'about_section_id' => $about->id,
                'order' => 1,
                'title' => 'Mekanik Berpengalaman',
                'description' => 'Tim kami terdiri dari tenaga ahli bersertifikat yang berpengalaman dalam servis dan perawatan truk berbagai merek.',
            ],
            [
                'about_section_id' => $about->id,
                'order' => 2,
                'title' => 'Layanan Cepat & Tepat',
                'description' => 'Kami memahami pentingnya waktu operasional truk Anda — karena itu kami menjamin proses servis yang efisien tanpa mengurangi kualitas.',
            ],
            [
                'about_section_id' => $about->id,
                'order' => 3,
                'title' => 'Sistem Digital Terintegrasi',
                'description' => 'Melalui aplikasi bengkel kami, pelanggan dapat memantau status servis, riwayat perawatan, dan melakukan pemesanan secara online.',
            ],
        ]);
    }
}
