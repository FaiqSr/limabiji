<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // --- Pages & Page Blocks ---
        $this->call(PageSeeder::class);

        // --- Origins (idempotent) ---
        $this->call(OriginSeeder::class);

        // --- Articles (idempotent) ---
        $this->seedArticles();

        // --- Testimonials (idempotent) ---
        $this->seedTestimonials();

        // --- Site Settings (idempotent via set() method) ---
        SiteSetting::set('site_name', 'Lima Biji Agritech', 'en', 'general');
        SiteSetting::set('site_name', 'Lima Biji Agritech', 'id', 'general');
        SiteSetting::set('contact_email', 'export@limabijiagritech.com', null, 'contact');
        SiteSetting::set('contact_phone', '+62 812 3456 7890', null, 'contact');
        SiteSetting::set('contact_address', 'Bogor, West Java, Indonesia', 'en', 'contact');
        SiteSetting::set('contact_address', 'Bogor, Jawa Barat, Indonesia', 'id', 'contact');
        SiteSetting::set('contact_hours', 'Mon – Fri, 8:00 – 16:00 WIB', null, 'contact');
    }

    private function seedHomepageBlocks(Page $home): void
    {
        PageBlock::insert([
            [
                'page_id' => $home->id, 'block_type' => 'hero', 'order' => 0,
                'content' => json_encode([
                    'en' => ['label' => 'LIMA BIJI AGRITECH', 'heading' => 'SPECIALTY ENZYMATIC<br>CIVET COFFEE', 'subheading' => 'Luxury civet coffee profile — zero animal cruelty. Our proprietary enzymatic fermentation replicates the natural civet process with scientific precision.', 'cta_text' => 'Explore Our Process', 'cta_url' => '/innovation'],
                    'id' => ['label' => 'LIMA BIJI AGRITECH', 'heading' => 'KOPI LUWAK<br>ENZIMATIK SPECIALTY', 'subheading' => 'Profil kopi luwak mewah — tanpa eksploitasi hewan. Fermentasi enzimatik proprietary kami mereplikasi proses luwak alami dengan presisi ilmiah.', 'cta_text' => 'Jelajahi Proses Kami', 'cta_url' => '/innovation'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $home->id, 'block_type' => 'text', 'order' => 1,
                'content' => json_encode([
                    'en' => ['heading' => 'EXPORT EXPERIENCE', 'body' => 'Our specialty enzymatic civet coffee reaches roasteries across the globe.'],
                    'id' => ['heading' => 'PENGALAMAN EKSPOR', 'body' => 'Kopi luwak enzimatik specialty kami menjangkau roastery di seluruh dunia.'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $home->id, 'block_type' => 'stats', 'order' => 2,
                'content' => json_encode([
                    'en' => ['heading' => 'We provide coffee for', 'items' => [['label' => 'Roasteries', 'value' => '400+'], ['label' => 'Countries', 'value' => '69+']]],
                    'id' => ['heading' => 'Kami menyediakan kopi untuk', 'items' => [['label' => 'Roastery', 'value' => '400+'], ['label' => 'Negara', 'value' => '69+']]],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $home->id, 'block_type' => 'cta', 'order' => 3,
                'content' => json_encode([
                    'en' => ['heading' => 'Ready to Elevate Your Coffee Menu?', 'body' => 'Partner with Lima Biji for consistent, high-scoring specialty enzymatic civet coffee.', 'button_text' => 'Get in Touch', 'button_url' => '/contact'],
                    'id' => ['heading' => 'Siap Meningkatkan Menu Kopi Anda?', 'body' => 'Bermitra dengan Lima Biji untuk kopi luwak enzimatik specialty yang konsisten.', 'button_text' => 'Hubungi Kami', 'button_url' => '/contact'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $home->id, 'block_type' => 'faq', 'order' => 4,
                'content' => json_encode([
                    'en' => ['heading' => 'FAQs', 'items' => [
                        ['question' => 'Can I get Loyalty Discounts?', 'answer' => 'Yes! We offer discounts on specialty coffee for higher volumes. 2 tonnes in 12 months: 3%, 4 tonnes: 7%, 6 tonnes: 12%.'],
                        ['question' => 'Can I order samples of all coffees?', 'answer' => 'All coffees can be sampled, except for special offers and low availability coffees.'],
                        ['question' => 'Do you ship to Asia Pacific & Middle East?', 'answer' => 'We use ocean freight, port-to-port. You handle customs clearance and transportation to your roastery.'],
                        ['question' => 'How can I order a sample?', 'answer' => 'Request a sample online or via email. We will send it if available.'],
                        ['question' => 'What purchasing options do I have?', 'answer' => 'Check our offer list for in-stock coffees and preships. NA Direct for 30+ bags and special orders.'],
                        ['question' => 'Where can I find information on each coffee?', 'answer' => 'Each coffee has a dedicated product page with sensorial info, cupping score, producer, farm, and processing details.'],
                        ['question' => 'How can I stay informed on incoming harvest?', 'answer' => 'Check our overview page and homepage dashboard for latest headlines and harvest updates.'],
                    ]],
                    'id' => ['heading' => 'TANYA JAWAB', 'items' => [
                        ['question' => 'Apakah ada Diskon Loyalitas?', 'answer' => 'Ya! Kami menawarkan diskon untuk volume lebih tinggi. 2 ton dalam 12 bulan: 3%, 4 ton: 7%, 6 ton: 12%.'],
                        ['question' => 'Bisakah saya memesan sampel semua kopi?', 'answer' => 'Semua kopi dapat disampel, kecuali penawaran khusus dan kopi dengan ketersediaan rendah.'],
                        ['question' => 'Apakah Anda mengirim ke Asia Pasifik & Timur Tengah?', 'answer' => 'Kami menggunakan angkutan laut, pelabuhan-ke-pelabuhan. Anda menangani bea cukai dan transportasi.'],
                        ['question' => 'Bagaimana cara memesan sampel?', 'answer' => 'Minta sampel secara online atau via email. Kami akan mengirimkannya jika tersedia.'],
                        ['question' => 'Opsi pembelian apa yang tersedia?', 'answer' => 'Lihat daftar penawaran kami untuk kopi stok dan preship. NA Direct untuk 30+ bag dan pesanan khusus.'],
                        ['question' => 'Di mana saya bisa menemukan informasi setiap kopi?', 'answer' => 'Setiap kopi memiliki halaman produk khusus dengan info sensorik, skor cupping, produsen, dan detail pemrosesan.'],
                        ['question' => 'Bagaimana saya tetap mendapat info panen?', 'answer' => 'Periksa halaman overview dan dashboard beranda kami untuk berita terbaru dan pembaruan panen.'],
                    ]],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    private function seedInnovationBlocks(Page $innovation): void
    {
        PageBlock::insert([
            [
                'page_id' => $innovation->id, 'block_type' => 'hero', 'order' => 0,
                'content' => json_encode([
                    'en' => ['label' => 'Innovation', 'heading' => 'ENZYMATIC<br>CIVET PROCESS', 'subheading' => 'Our proprietary biotechnology replicates the natural civet fermentation process — without a single animal involved. Same luxury profile. Zero ethical compromise.'],
                    'id' => ['label' => 'Inovasi', 'heading' => 'PROSES<br>LUWAK ENZIMATIK', 'subheading' => 'Bioteknologi proprietary kami mereplikasi proses fermentasi luwak alami — tanpa melibatkan satu hewan pun. Profil mewah yang sama. Tanpa kompromi etika.'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $innovation->id, 'block_type' => 'process_steps', 'order' => 1,
                'content' => json_encode([
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
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $innovation->id, 'block_type' => 'text_with_stats', 'order' => 2,
                'content' => json_encode([
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
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'page_id' => $innovation->id, 'block_type' => 'cta', 'order' => 3,
                'content' => json_encode([
                    'en' => ['heading' => 'Interested in Our Process?', 'body' => 'Request sample lots, technical documentation, or schedule a virtual tour of our fermentation facility.', 'button_text' => 'Request Samples', 'button_url' => '/contact'],
                    'id' => ['heading' => 'Tertarik dengan Proses Kami?', 'body' => 'Minta sampel lot, dokumentasi teknis, atau jadwalkan tur virtual fasilitas fermentasi kami.', 'button_text' => 'Minta Sampel', 'button_url' => '/contact'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    private function seedContactBlocks(Page $contact): void
    {
        PageBlock::insert([
            [
                'page_id' => $contact->id, 'block_type' => 'hero', 'order' => 0,
                'content' => json_encode([
                    'en' => ['label' => 'Get in Touch', 'heading' => "LET'S<br>TALK.", 'subheading' => "Each one of us is an expert in a different market and can help you with all of your roastery's requirements."],
                    'id' => ['label' => 'Hubungi Kami', 'heading' => 'MARI<br>BICARA.', 'subheading' => 'Masing-masing dari kami ahli di pasar yang berbeda dan dapat membantu Anda dengan semua kebutuhan roastery Anda.'],
                ]),
                'is_visible' => true, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    private function seedArticles(): void
    {
        $categories = [
            ['name' => 'Export Market', 'name_id' => 'Pasar Ekspor', 'slug' => 'export-market'],
            ['name' => 'Innovation', 'name_id' => 'Inovasi', 'slug' => 'innovation'],
            ['name' => 'Sustainability', 'name_id' => 'Keberlanjutan', 'slug' => 'sustainability'],
            ['name' => 'Agritech', 'name_id' => 'Agriteknologi', 'slug' => 'agritech'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['name']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $articles = [
            [
                'title' => 'Expansion to Japanese Market: High-Grade Specialty Civet Coffee Exported to Tokyo',
                'slug' => 'expansion-japanese-market',
                'category' => 'Export Market',
                'categories' => ['Export Market', 'Agritech'],
                'excerpt' => 'Lima Biji Agritech officially ships its first premium batch of enzymatic civet coffee to top-tier roasteries in Japan.',
                'content' => '<p>Lima Biji Agritech officially ships its first premium batch of enzymatic civet coffee to top-tier roasteries in Japan, marking a major milestone in our Asia-Pacific export strategy.</p>',
                'content_id' => '<p>Lima Biji Agritech secara resmi mengirimkan batch premium pertama kopi luwak enzimatik ke roastery kelas atas di Jepang, menandai tonggak penting dalam strategi ekspor Asia-Pasifik kami.</p>',
                'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?q=80&w=800&auto=format&fit=crop',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Sustainable Enzymatic Fermentation Process Achieves International Certification',
                'slug' => 'sustainable-enzymatic-fermentation-certification',
                'category' => 'Innovation',
                'categories' => ['Innovation', 'Sustainability'],
                'excerpt' => 'Our revolutionary cruelty-free enzymatic civet processing technique earns high acclaim for quality and sustainability.',
                'content' => '<p>Our revolutionary cruelty-free enzymatic civet processing technique earns high acclaim for quality and sustainability from international coffee organizations.</p>',
                'content_id' => '<p>Teknik pemrosesan luwak enzimatik bebas eksploitasi kami yang revolusioner mendapat pengakuan tinggi untuk kualitas dan keberlanjutan dari organisasi kopi internasional.</p>',
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'New Partner Farm Partnership Program Launched Across West Java Highlands',
                'slug' => 'partner-farm-program-west-java',
                'category' => 'Sustainability',
                'categories' => ['Sustainability'],
                'excerpt' => 'Empowering local farmers through specialty green bean cultivation and ethical harvest standards across West Java highlands.',
                'content' => '<p>Empowering local farmers through specialty green bean cultivation and ethical harvest standards across West Java highlands.</p>',
                'content_id' => '<p>Memberdayakan petani lokal melalui budidaya green bean specialty dan standar panen etis di seluruh dataran tinggi Jawa Barat.</p>',
                'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($articles as $artData) {
            $catNames = $artData['categories'] ?? [];
            unset($artData['categories']);

            $article = Article::updateOrCreate(['slug' => $artData['slug']], $artData);

            $catIds = [];
            foreach ($catNames as $cName) {
                if (isset($categoryModels[$cName])) {
                    $catIds[] = $categoryModels[$cName]->id;
                }
            }
            if (! empty($catIds)) {
                $article->categories()->sync($catIds);
            }
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['name' => 'Hiroshi Tanaka', 'company' => 'Tokyo Roastery', 'content' => "The enzymatic civet process produces an incredibly clean cup with zero bitterness. Our customers in Tokyo can't get enough.", 'content_id' => 'Proses luwak enzimatik menghasilkan cangkir yang sangat bersih tanpa rasa pahit. Pelanggan kami di Tokyo sangat menyukainya.', 'is_featured' => true, 'order' => 1],
            ['name' => 'Sarah Chen', 'company' => 'Melbourne Coffee Co.', 'content' => "Lima Biji's consistency across micro-lots is unmatched. Every batch meets our exacting specialty standards.", 'content_id' => 'Konsistensi Lima Biji di seluruh micro-lot tidak tertandingi. Setiap batch memenuhi standar specialty kami yang ketat.', 'is_featured' => true, 'order' => 2],
            ['name' => 'Marco Verdi', 'company' => 'Milan Espresso', 'content' => 'Finally — a true luxury civet experience without the ethical concerns. The fermentation precision is remarkable.', 'content_id' => 'Akhirnya — pengalaman luwak mewah sejati tanpa masalah etika. Presisi fermentasinya luar biasa.', 'is_featured' => true, 'order' => 3],
        ];
        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'company' => $testimonial['company']],
                $testimonial
            );
        }
    }
}
