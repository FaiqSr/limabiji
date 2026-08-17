<?php

namespace Database\Seeders;

use App\Models\InnovationStep;
use Illuminate\Database\Seeder;

class InnovationStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'step_number' => '01',
                'title' => 'Ethical Cherry Sourcing',
                'title_id' => 'Pengadaan Ceri Etis',
                'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop',
                'description' => 'We partner directly with high-altitude smallholder farms across West Java, Toraja, and Aceh. Only 100% ripe, hand-picked red cherries with sugar levels above 20° Brix are accepted.',
                'description_id' => 'Kami bermitra langsung dengan petani kecil dataran tinggi di seluruh Jawa Barat, Toraja, dan Aceh. Hanya ceri merah 100% matang yang dipetik tangan dengan kadar gula di atas 20° Brix yang diterima.',
                'details' => 'Hand-picked red cherries, 20°+ Brix sugar level, High altitude (1200m+)',
                'details_id' => 'Petik tangan ceri merah, Kadar gula 20°+ Brix, Dataran tinggi (1200m+)',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'step_number' => '02',
                'title' => 'Enzyme Isolation & Formulation',
                'title_id' => 'Isolasi & Formulasi Enzim',
                'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Our bio-scientists isolate natural proteolytic and lipolytic enzymes that mirror the digestive biochemistry of the Asian Palm Civet (Paradoxurus hermaphroditus).',
                'description_id' => 'Ilmuwan bio kami mengisolasi enzim proteolitik dan lipolitik alami yang mencerminkan biokimia pencernaan Musang Luwak Asia (Paradoxurus hermaphroditus).',
                'details' => 'Plant-based enzymes, Bio-identical process, Zero animal involvement',
                'details_id' => 'Enzim berbasis tanaman, Proses bio-identik, Tanpa keterlibatan hewan',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'step_number' => '03',
                'title' => 'Controlled Fermentation',
                'title_id' => 'Fermentasi Terkontrol',
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop',
                'description' => 'Depulped beans undergo a 36-hour bio-fermentation in stainless steel bioreactors. Temperature, pH, and enzyme concentration are monitored in real time.',
                'description_id' => 'Biji yang telah dikupas menjalani bio-fermentasi 36 jam dalam bioreaktor stainless steel. Suhu, pH, dan konsentrasi enzim dipantau secara real-time.',
                'details' => '36-hour duration, Real-time pH control, Stainless steel tanks',
                'details_id' => 'Durasi 36 jam, Kontrol pH real-time, Tangki stainless steel',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'step_number' => '04',
                'title' => 'Washing & Solar Drying',
                'title_id' => 'Pencucian & Pengeringan Matahari',
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop',
                'description' => 'Beans are thoroughly washed with mountain spring water to stop fermentation, then dried on raised solar beds to achieve an optimal 11% moisture content.',
                'description_id' => 'Biji dicuci menyeluruh dengan air mata air pegunungan untuk menghentikan fermentasi, lalu dikeringkan di atas bedengan surya untuk mencapai kadar air optimal 11%.',
                'details' => 'Spring water wash, Raised bed drying, 11% target moisture',
                'details_id' => 'Cuci air mata air, Pengeringan bedengan, Target kadar air 11%',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'step_number' => '05',
                'title' => 'Resting & Conditioning',
                'title_id' => 'Penyimpanan & Kondisioning',
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop',
                'description' => 'Parchment coffee rests in temperature-controlled warehouses for 30–60 days. This allows flavor precursors to stabilize before hulling.',
                'description_id' => 'Kopi parchment disimpan dalam gudang dengan suhu terkontrol selama 30–60 hari. Ini memungkinkan prekursor rasa stabil sebelum pengupasan.',
                'details' => '30–60 days rest, Climate-controlled, Parchment aging',
                'details_id' => 'Istirahat 30–60 hari, Kontrol iklim, Penuaan parchment',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'step_number' => '06',
                'title' => 'Quality Grading & Export',
                'title_id' => 'Grading Kualitas & Ekspor',
                'image' => 'https://images.unsplash.com/photo-1587080413959-06b859fb107d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Every lot undergoes cupping, density sorting, and screen sizing. Only beans scoring 84+ points are approved for export. Vacuum-packed in food-grade GrainPro bags.',
                'description_id' => 'Setiap lot menjalani cupping, penyortiran densitas, dan sizing. Hanya biji dengan skor 84+ yang disetujui untuk ekspor. Dikemas vakum dalam kantong GrainPro food-grade.',
                'details' => 'SCA score: 84+, Screen: 16–18, GrainPro packed',
                'details_id' => 'Skor SCA: 84+, Screen: 16–18, Kemasan GrainPro',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($steps as $step) {
            InnovationStep::updateOrCreate(
                ['title' => $step['title']],
                $step
            );
        }
    }
}
