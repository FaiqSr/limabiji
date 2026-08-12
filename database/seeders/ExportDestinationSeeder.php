<?php

namespace Database\Seeders;

use App\Models\ExportDestination;
use Illuminate\Database\Seeder;

class ExportDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Japan',
                'name_id' => 'Jepang',
                'country_code' => 'jp',
                'longitude' => 138.2529,
                'latitude' => 36.2048,
                'description' => 'Key export market for high-grade specialty and enzymatic civet coffee.',
                'description_id' => 'Pasar ekspor utama untuk kopi civet enzimatik dan specialty kelas tinggi.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'China',
                'name_id' => 'Tiongkok',
                'country_code' => 'cn',
                'longitude' => 104.1954,
                'latitude' => 35.8617,
                'description' => 'Rapidly growing market for luxury and specialty coffee products.',
                'description_id' => 'Pasar yang berkembang pesat untuk produk kopi specialty premium.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Bangladesh',
                'name_id' => 'Bangladesh',
                'country_code' => 'bd',
                'longitude' => 90.3563,
                'latitude' => 23.6850,
                'description' => 'Emerging market for premium imported Indonesian coffee beans.',
                'description_id' => 'Pasar berkembang untuk biji kopi impor premium asal Indonesia.',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Malaysia',
                'name_id' => 'Malaysia',
                'country_code' => 'my',
                'longitude' => 101.9758,
                'latitude' => 4.2105,
                'description' => 'Established Southeast Asian hub for specialty coffee distribution.',
                'description_id' => 'Pusat distribusi kopi specialty terkemuka di Asia Tenggara.',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Australia',
                'name_id' => 'Australia',
                'country_code' => 'au',
                'longitude' => 133.7751,
                'latitude' => -25.2744,
                'description' => 'World-renowned specialty coffee market appreciating unique processed profiles.',
                'description_id' => 'Pasar kopi specialty ternama dunia yang mengapresiasi profil proses unik.',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Peru',
                'name_id' => 'Peru',
                'country_code' => 'pe',
                'longitude' => -75.0152,
                'latitude' => -9.1900,
                'description' => 'South American partner and specialty coffee hub.',
                'description_id' => 'Mitra Amerika Selatan dan hub kopi specialty.',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Saudi Arabia',
                'name_id' => 'Arab Saudi',
                'country_code' => 'sa',
                'longitude' => 45.0792,
                'latitude' => 23.8859,
                'description' => 'Major Middle Eastern destination for high-end specialty civet coffee.',
                'description_id' => 'Destinasi utama Timur Tengah untuk kopi civet specialty kelas atas.',
                'is_active' => true,
                'order' => 7,
            ],
        ];

        foreach ($locations as $loc) {
            ExportDestination::updateOrCreate(
                ['country_code' => $loc['country_code']],
                $loc
            );
        }
    }
}
