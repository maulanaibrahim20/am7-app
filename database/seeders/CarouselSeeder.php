<?php

namespace Database\Seeders;

use App\Models\Carousel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ],
        ]);
    }
}
