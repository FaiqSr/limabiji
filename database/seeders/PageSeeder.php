<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'template' => 'default',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'blocks' => [
                    [
                        'block_type' => 'hero',
                        'order' => 0,
                        'content' => [
                            'en' => [
                                'label' => 'LIMA BIJI AGRITECH',
                                'heading' => 'SPECIALTY ENZYMATIC<br>CIVET COFFEE',
                                'subheading' => 'Luxury civet coffee profile — zero animal cruelty. Our proprietary enzymatic fermentation replicates the natural civet process with scientific precision.',
                                'cta_text' => 'Explore Our Process',
                                'cta_url' => '/innovation',
                            ],
                            'id' => [
                                'label' => 'LIMA BIJI AGRITECH',
                                'heading' => 'KOPI LUWAK<br>ENZIMATIK SPECIALTY',
                                'subheading' => 'Profil kopi luwak mewah — tanpa eksploitasi hewan. Fermentasi enzimatik proprietary kami mereplikasi proses luwak alami dengan presisi ilmiah.',
                                'cta_text' => 'Jelajahi Proses Kami',
                                'cta_url' => '/innovation',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'text',
                        'order' => 1,
                        'content' => [
                            'en' => [
                                'heading' => 'EXPORT EXPERIENCE',
                                'body' => 'Our specialty enzymatic civet coffee reaches roasteries across the globe.',
                            ],
                            'id' => [
                                'heading' => 'PENGALAMAN EKSPOR',
                                'body' => 'Kopi luwak enzimatik specialty kami menjangkau roastery di seluruh dunia.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'stats',
                        'order' => 2,
                        'content' => [
                            'en' => [
                                'heading' => 'We provide coffee for',
                                'items' => [
                                    ['label' => 'Export Countries', 'value' => '7+'],
                                    ['label' => 'Partner Farms', 'value' => '10+'],
                                    ['label' => 'Cruelty-Free', 'value' => '100%'],
                                    ['label' => 'Origins', 'value' => '6'],
                                ],
                            ],
                            'id' => [
                                'heading' => 'Kami menyediakan kopi untuk',
                                'items' => [
                                    ['label' => 'Negara Ekspor', 'value' => '7+'],
                                    ['label' => 'Petani Mitra', 'value' => '10+'],
                                    ['label' => 'Bebas Eksploitasi', 'value' => '100%'],
                                    ['label' => 'Asal Daerah', 'value' => '6'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'cta',
                        'order' => 3,
                        'content' => [
                            'en' => [
                                'heading' => 'Ready to Elevate Your Specialty Offerings?',
                                'body' => 'Partner with us for direct trade, ethical sourcing, and custom enzymatic processing micro-lots.',
                                'button_text' => 'Get Samples & Quote',
                                'button_url' => '/contact',
                            ],
                            'id' => [
                                'heading' => 'Siap Meningkatkan Penawaran Specialty Anda?',
                                'body' => 'Bermitra dengan kami untuk perdagangan langsung, pengadaan etis, dan lot mikro pemrosesan enzimatik kustom.',
                                'button_text' => 'Minta Sampel & Penawaran',
                                'button_url' => '/contact',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'faq',
                        'order' => 4,
                        'content' => [
                            'en' => [
                                'heading' => 'FAQs',
                                'items' => [
                                    [
                                        'question' => 'How does your enzymatic civet coffee process work without animals?',
                                        'answer' => 'We replicate the natural fermentation process of wild civets using bio-identical plant-based enzymes and precise temperature-controlled fermentation. This yields the signature smooth, low-acidity profile of luxury civet coffee with 100% cruelty-free consistency.',
                                    ],
                                    [
                                        'question' => 'What is the Minimum Order Quantity (MOQ) for international exports?',
                                        'answer' => 'For air freight and sample lots, our minimum order starts at 20 kg in vacuum-sealed food-grade bags. For full container ocean freight (FCL/LCL), we accommodate orders starting from 500 kg up to bulk supply.',
                                    ],
                                    [
                                        'question' => 'Which coffee origins and varieties do you offer?',
                                        'answer' => 'We primarily process single-origin specialty Arabica green beans harvested from West Java high-altitude farms (Preanger), as well as curated lots from Toraja, Aceh Gayo, Malang, and Yogyakarta.',
                                    ],
                                    [
                                        'question' => 'Do you provide green bean samples for roasteries before purchasing?',
                                        'answer' => 'Yes. We provide 250g–1kg sample packs for licensed roasters and coffee importers globally. You can request a sample kit by contacting our sales team via the export contact form.',
                                    ],
                                    [
                                        'question' => 'What export certifications and documentation do you provide?',
                                        'answer' => 'Every export shipment comes complete with a Certificate of Origin (COO), Phytosanitary Certificate, Bill of Lading, Commercial Invoice, Packing List, and Quality Analysis Lab Reports.',
                                    ],
                                ],
                            ],
                            'id' => [
                                'heading' => 'TANYA JAWAB',
                                'items' => [
                                    [
                                        'question' => 'Bagaimana proses kopi luwak enzimatik Anda bekerja tanpa hewan?',
                                        'answer' => 'Kami mereplikasi proses fermentasi alami musang luwak liar menggunakan enzim berbasis tanaman bio-identik dan fermentasi terkontrol suhu presisi. Ini menghasilkan profil luwak mewah yang lembut dan rendah keasaman secara 100% bebas eksploitasi.',
                                    ],
                                    [
                                        'question' => 'Berapa Jumlah Pesanan Minimum (MOQ) untuk ekspor internasional?',
                                        'answer' => 'Untuk kargo udara dan lot sampel, pesanan minimum kami mulai dari 20 kg dalam kantong food-grade tersegel vakum. Untuk kargo laut kontainer penuh (FCL/LCL), kami melayani pesanan mulai dari 500 kg hingga pasokan curah.',
                                    ],
                                    [
                                        'question' => 'Asal daerah dan varietas kopi apa saja yang Anda tawarkan?',
                                        'answer' => 'Kami utamanya memproses biji hijau Arabika specialty single-origin dari perkebunan dataran tinggi Jawa Barat (Preanger), serta lot terkurasi dari Toraja, Aceh Gayo, Malang, dan Yogyakarta.',
                                    ],
                                    [
                                        'question' => 'Apakah Anda menyediakan sampel biji hijau untuk roastery sebelum membeli?',
                                        'answer' => 'Ya. Kami menyediakan paket sampel 250g–1kg untuk roaster berlisensi dan importir kopi secara global. Anda dapat meminta kit sampel dengan menghubungi tim penjualan kami melalui formulir kontak ekspor.',
                                    ],
                                    [
                                        'question' => 'Sertifikasi dan dokumentasi ekspor apa yang Anda sediakan?',
                                        'answer' => 'Setiap pengiriman ekspor dilengkapi dengan Surat Keterangan Asal (COO), Sertifikat Fitosanitari, Bill of Lading, Faktur Komersial, Packing List, dan Laporan Hasil Analisis Kualitas Lab.',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Innovation',
                'slug' => 'innovation',
                'template' => 'innovation',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'blocks' => [
                    [
                        'block_type' => 'hero',
                        'order' => 0,
                        'content' => [
                            'en' => [
                                'label' => 'Innovation',
                                'heading' => 'ENZYMATIC<br>CIVET PROCESS',
                                'subheading' => 'Our proprietary biotechnology replicates the natural civet fermentation process — without a single animal involved. Same luxury profile. Zero ethical compromise.',
                            ],
                            'id' => [
                                'label' => 'Inovasi',
                                'heading' => 'PROSES<br>LUWAK ENZIMATIK',
                                'subheading' => 'Bioteknologi proprietary kami mereplikasi proses fermentasi luwak alami — tanpa melibatkan satu hewan pun. Profil mewah yang sama. Tanpa kompromi etika.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'process_steps',
                        'order' => 1,
                        'content' => [
                            'en' => [
                                'heading' => 'HOW IT WORKS',
                                'subtitle' => 'Six precise steps from harvest to export — every batch crafted with scientific rigor.',
                                'items' => [
                                    ['step' => '01', 'title' => 'Ethical Cherry Sourcing', 'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop', 'description' => 'We partner directly with high-altitude smallholder farms across West Java, Toraja, and Aceh. Only 100% ripe, hand-picked red cherries with sugar levels above 20° Brix are accepted.', 'details' => 'Hand-picked red cherries, 20°+ Brix sugar level, High altitude (1200m+)'],
                                    ['step' => '02', 'title' => 'Enzyme Isolation & Formulation', 'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop', 'description' => 'Our bio-scientists isolate natural proteolytic and lipolytic enzymes that mirror the digestive biochemistry of the Asian Palm Civet (Paradoxurus hermaphroditus).', 'details' => 'Plant-based enzymes, Bio-identical process, Zero animal involvement'],
                                    ['step' => '03', 'title' => 'Controlled Fermentation', 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop', 'description' => 'Depulped beans undergo a 36-hour bio-fermentation in stainless steel bioreactors. Temperature, pH, and enzyme concentration are monitored in real time.', 'details' => '36-hour duration, Real-time pH control, Stainless steel tanks'],
                                    ['step' => '04', 'title' => 'Washing & Solar Drying', 'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop', 'description' => 'Beans are thoroughly washed with mountain spring water to stop fermentation, then dried on raised solar beds to achieve an optimal 11% moisture content.', 'details' => 'Spring water wash, Raised bed drying, 11% target moisture'],
                                    ['step' => '05', 'title' => 'Resting & Conditioning', 'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop', 'description' => 'Parchment coffee rests in temperature-controlled warehouses for 30–60 days. This allows flavor precursors to stabilize before hulling.', 'details' => '30–60 days rest, Climate-controlled, Parchment aging'],
                                    ['step' => '06', 'title' => 'Quality Grading & Export', 'image' => 'https://images.unsplash.com/photo-1587080413959-06b859fb107d?q=80&w=800&auto=format&fit=crop', 'description' => 'Every lot undergoes cupping, density sorting, and screen sizing. Only beans scoring 84+ points are approved for export. Vacuum-packed in food-grade GrainPro bags.', 'details' => 'SCA score: 84+, Screen: 16–18, GrainPro packed'],
                                ],
                            ],
                            'id' => [
                                'heading' => 'CARA KERJA',
                                'subtitle' => 'Enam langkah presisi dari panen hingga ekspor — setiap batch dibuat dengan ketelitian ilmiah.',
                                'items' => [
                                    ['step' => '01', 'title' => 'Pengadaan Ceri Etis', 'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop', 'description' => 'Kami bermitra langsung dengan petani kecil dataran tinggi di seluruh Jawa Barat, Toraja, dan Aceh. Hanya ceri merah 100% matang yang dipetik tangan dengan kadar gula di atas 20° Brix yang diterima.', 'details' => 'Petik tangan ceri merah, Kadar gula 20°+ Brix, Dataran tinggi (1200m+)'],
                                    ['step' => '02', 'title' => 'Isolasi & Formulasi Enzim', 'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop', 'description' => 'Ilmuwan bio kami mengisolasi enzim proteolitik dan lipolitik alami yang mencerminkan biokimia pencernaan Musang Luwak Asia (Paradoxurus hermaphroditus).', 'details' => 'Enzim berbasis tanaman, Proses bio-identik, Tanpa keterlibatan hewan'],
                                    ['step' => '03', 'title' => 'Fermentasi Terkontrol', 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop', 'description' => 'Biji yang telah dikupas menjalani bio-fermentasi 36 jam dalam bioreaktor stainless steel. Suhu, pH, dan konsentrasi enzim dipantau secara real-time.', 'details' => 'Durasi 36 jam, Kontrol pH real-time, Tangki stainless steel'],
                                    ['step' => '04', 'title' => 'Pencucian & Pengeringan Matahari', 'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop', 'description' => 'Biji dicuci menyeluruh dengan air mata air pegunungan untuk menghentikan fermentasi, lalu dikeringkan di atas bedengan surya untuk mencapai kadar air optimal 11%.', 'details' => 'Cuci air mata air, Pengeringan bedengan, Target kadar air 11%'],
                                    ['step' => '05', 'title' => 'Penyimpanan & Kondisioning', 'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop', 'description' => 'Kopi parchment disimpan dalam gudang dengan suhu terkontrol selama 30–60 hari. Ini memungkinkan prekursor rasa stabil sebelum pengupasan.', 'details' => 'Istirahat 30–60 hari, Kontrol iklim, Penuaan parchment'],
                                    ['step' => '06', 'title' => 'Grading Kualitas & Ekspor', 'image' => 'https://images.unsplash.com/photo-1587080413959-06b859fb107d?q=80&w=800&auto=format&fit=crop', 'description' => 'Setiap lot menjalani cupping, penyortiran densitas, dan sizing. Hanya biji dengan skor 84+ yang disetujui untuk ekspor. Dikemas vakum dalam kantong GrainPro food-grade.', 'details' => 'Skor SCA: 84+, Screen: 16–18, Kemasan GrainPro'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'text_with_stats',
                        'order' => 2,
                        'content' => [
                            'en' => [
                                'heading' => 'Why Cruelty-Free Matters',
                                'body' => 'Traditional civet coffee (Kopi Luwak) relies on caged wild civets force-fed coffee cherries — a practice widely condemned by animal welfare organizations and specialty coffee associations.',
                                'body2' => 'Our enzymatic process achieves the identical proteolytic breakdown of coffee proteins without exploiting any animal. The result is a coffee that is:',
                                'checklist' => 'Ethically sourced and 100% cruelty-free, Consistent in quality — every batch tastes the same, Food-safe and hygienically processed, Lower in bitterness, higher in sweetness',
                                'items' => [
                                    ['label' => 'SCA Cupping Score', 'value' => '84+'],
                                    ['label' => 'Animals Involved', 'value' => '0'],
                                    ['label' => 'Fermentation', 'value' => '48–72h'],
                                    ['label' => 'Quality Steps', 'value' => '6'],
                                    ['label' => 'Export Countries', 'value' => '7+'],
                                ],
                            ],
                            'id' => [
                                'heading' => 'Mengapa Bebas Eksploitasi Itu Penting',
                                'body' => 'Kopi luwak tradisional (Kopi Luwak) bergantung pada musang liar yang dikurung dan dicekok paksa ceri kopi — praktik yang dikutuk luas oleh organisasi kesejahteraan hewan dan asosiasi kopi specialty.',
                                'body2' => 'Proses enzimatik kami mencapai pemecahan proteolitik identik dari protein kopi tanpa mengeksploitasi hewan apa pun. Hasilnya adalah kopi yang:',
                                'checklist' => 'Bersumber etis dan 100% bebas eksploitasi, Kualitas konsisten — setiap batch terasa sama, Aman pangan dan diproses higienis, Lebih rendah kepahitan, lebih tinggi kemanisan',
                                'items' => [
                                    ['label' => 'Skor Cupping SCA', 'value' => '84+'],
                                    ['label' => 'Hewan Terlibat', 'value' => '0'],
                                    ['label' => 'Fermentasi', 'value' => '48–72j'],
                                    ['label' => 'Langkah Kualitas', 'value' => '6'],
                                    ['label' => 'Negara Ekspor', 'value' => '7+'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'cta',
                        'order' => 3,
                        'content' => [
                            'en' => [
                                'heading' => 'Interested in Our Process?',
                                'body' => 'Request sample lots, technical documentation, or schedule a virtual tour of our fermentation facility.',
                                'button_text' => 'Request Samples',
                                'button_url' => '/contact',
                            ],
                            'id' => [
                                'heading' => 'Tertarik dengan Proses Kami?',
                                'body' => 'Minta sampel lot, dokumentasi teknis, atau jadwalkan tur virtual fasilitas fermentasi kami.',
                                'button_text' => 'Minta Sampel',
                                'button_url' => '/contact',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'News Library',
                'slug' => 'news',
                'template' => 'news',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'blocks' => [
                    [
                        'block_type' => 'hero',
                        'order' => 0,
                        'content' => [
                            'en' => [
                                'label' => 'News & Stories',
                                'heading' => 'NEWS<br>LIBRARY',
                                'subheading' => 'Export updates, fermentation breakthroughs, and stories from our partner farms.',
                            ],
                            'id' => [
                                'label' => 'Berita & Cerita',
                                'heading' => 'PERPUSTAKAAN<br>BERITA',
                                'subheading' => 'Pembaruan ekspor, terobosan fermentasi, dan cerita dari petani mitra kami.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'articles',
                        'order' => 1,
                        'content' => [
                            'en' => [
                                'heading' => 'NEWS & ARTICLES',
                            ],
                            'id' => [
                                'heading' => 'BERITA & ARTIKEL',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Testimonials',
                'slug' => 'testimonials',
                'template' => 'testimonials',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'blocks' => [
                    [
                        'block_type' => 'hero',
                        'order' => 0,
                        'content' => [
                            'en' => [
                                'label' => 'Testimonials',
                                'heading' => 'WHAT THEY<br>SAY',
                                'subheading' => 'Trusted by specialty coffee roasters, importers, and barista champions across the globe.',
                            ],
                            'id' => [
                                'label' => 'Testimoni',
                                'heading' => 'APA KATA<br>MEREKA',
                                'subheading' => 'Dipercaya oleh roaster kopi specialty, importir, dan juara barista di seluruh dunia.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'testimonials',
                        'order' => 1,
                        'content' => [
                            'en' => [
                                'heading' => 'WHAT THEY SAY',
                            ],
                            'id' => [
                                'heading' => 'APA KATA MEREKA',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'template' => 'contact',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'blocks' => [
                    [
                        'block_type' => 'hero',
                        'order' => 0,
                        'content' => [
                            'en' => [
                                'label' => 'Get in Touch',
                                'heading' => "LET'S<br>TALK.",
                                'subheading' => "Each one of us is an expert in a different market and can help you with all of your roastery's requirements.",
                            ],
                            'id' => [
                                'label' => 'Hubungi Kami',
                                'heading' => 'MARI<br>BICARA.',
                                'subheading' => 'Masing-masing dari kami ahli di pasar yang berbeda dan dapat membantu Anda dengan semua kebutuhan roastery Anda.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => 'contact',
                        'order' => 1,
                        'content' => [
                            'en' => [
                                'heading' => 'GET IN TOUCH',
                                'subtitle' => "Each one of us is an expert in a different market and can help you with all of your roastery's requirements.",
                                'email' => 'export@limabijiagritech.com',
                                'phone' => '+62 812 3456 7890',
                                'address' => 'Bogor, West Java, Indonesia',
                            ],
                            'id' => [
                                'heading' => 'HUBUNGI KAMI',
                                'subtitle' => 'Masing-masing dari kami ahli di pasar yang berbeda dan dapat membantu Anda dengan semua kebutuhan roastery Anda.',
                                'email' => 'export@limabijiagritech.com',
                                'phone' => '+62 812 3456 7890',
                                'address' => 'Bogor, Jawa Barat, Indonesia',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $blocksData = $pageData['blocks'] ?? [];
            unset($pageData['blocks']);

            $page = Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );

            $page->blocks()->delete();

            foreach ($blocksData as $block) {
                PageBlock::create([
                    'page_id' => $page->id,
                    'block_type' => $block['block_type'],
                    'order' => $block['order'],
                    'content' => $block['content'],
                    'is_visible' => true,
                ]);
            }
        }
    }
}
