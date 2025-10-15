<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{AboutFeature, AboutSection};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcePath = public_path('landing/img/about.jpg');

        if (!file_exists($sourcePath)) {
            $this->command->warn("⚠️ File gambar tidak ditemukan di: {$sourcePath}");
            return;
        }

        $filename = 'about_' . Str::random(8) . '.jpg';
        $destinationPath = 'about/' . $filename;

        Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));

        $about = AboutSection::create([
            'subtitle' => 'Tentang Kami',
            'title' => 'AM7 — Pusat Servis dan Perawatan Truk Terpercaya',
            'description' => 'AM7 hadir sebagai bengkel profesional yang berfokus pada perawatan dan perbaikan truk berbagai jenis. Dengan pengalaman bertahun-tahun dan dukungan tim mekanik ahli, kami memastikan setiap unit truk Anda tetap dalam kondisi prima untuk menunjang operasional bisnis Anda.',
            'image' => $destinationPath,
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

        $this->command->info("✅ AboutSection berhasil dibuat dengan gambar disalin ke storage: storage/{$destinationPath}");
    }
}
