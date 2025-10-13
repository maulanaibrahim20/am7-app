<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::create([
            'address' => 'Blok bunderan, Kiajaran Wetan, Kec. Lohbener, Kabupaten Indramayu, Jawa Barat 45252',
            'open_hours' => '24 Jam',
            'phone' => '081802312656',
            'facebook_url' => '#',
            'twitter_url' => '#',
            'linkedin_url' => '#',
            'instagram_url' => '#',
        ]);
    }
}
