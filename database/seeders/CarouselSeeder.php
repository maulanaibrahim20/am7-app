<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{File, Storage};
use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = [
            'carousel-bg-1.jpg',
            'carousel-bg-2.jpg',
            'carousel-1.png',
            'carousel-2.png',
        ];

        foreach ($files as $file) {
            $source = public_path('landing/img/' . $file);
            $destination = 'landing/img/' . $file;
            if (File::exists($source) && !Storage::disk('public')->exists($destination)) {
                Storage::disk('public')->put($destination, File::get($source));
            }
        }

        Carousel::insert([
            [
                'title' => 'Layanan Servis Truk Profesional & Terpercaya',
                'subtitle' => 'Bengkel Mobil & Truck Profesional',
                'button_text' => 'Lihat Layanan',
                'button_url' => '#',
                'background_image' => 'landing/img/carousel-bg-1.jpg',
                'foreground_image' => 'landing/img/carousel-1.png',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Booking Servis Truk Anda Secara Online',
                'subtitle' => 'Jadwalkan Servis Truk Anda Hari Ini',
                'button_text' => 'Buat Janji Servis',
                'button_url' => '#',
                'background_image' => 'landing/img/carousel-bg-2.jpg',
                'foreground_image' => 'landing/img/carousel-2.png',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
