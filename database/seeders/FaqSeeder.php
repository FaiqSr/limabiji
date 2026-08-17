<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How does your enzymatic civet coffee process work without animals?',
                'question_id' => 'Bagaimana proses kopi luwak enzimatik Anda bekerja tanpa hewan?',
                'answer' => 'We replicate the natural fermentation process of wild civets using bio-identical plant-based enzymes and precise temperature-controlled fermentation. This yields the signature smooth, low-acidity profile of luxury civet coffee with 100% cruelty-free consistency.',
                'answer_id' => 'Kami mereplikasi proses fermentasi alami musang luwak liar menggunakan enzim berbasis tanaman bio-identik dan fermentasi terkontrol suhu presisi. Ini menghasilkan profil luwak mewah yang lembut dan rendah keasaman secara 100% bebas eksploitasi.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'question' => 'What is the Minimum Order Quantity (MOQ) for international exports?',
                'question_id' => 'Berapa Jumlah Pesanan Minimum (MOQ) untuk ekspor internasional?',
                'answer' => 'For air freight and sample lots, our minimum order starts at 20 kg in vacuum-sealed food-grade bags. For full container ocean freight (FCL/LCL), we accommodate orders starting from 500 kg up to bulk supply.',
                'answer_id' => 'Untuk kargo udara dan lot sampel, pesanan minimum kami mulai dari 20 kg dalam kantong food-grade tersegel vakum. Untuk kargo laut kontainer penuh (FCL/LCL), kami melayani pesanan mulai dari 500 kg hingga pasokan curah.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'question' => 'Which coffee origins and varieties do you offer?',
                'question_id' => 'Asal daerah dan varietas kopi apa saja yang Anda tawarkan?',
                'answer' => 'We primarily process single-origin specialty Arabica green beans harvested from West Java high-altitude farms (Preanger), as well as curated lots from Toraja, Aceh Gayo, Malang, and Yogyakarta.',
                'answer_id' => 'Kami utamanya memproses biji hijau Arabika specialty single-origin dari perkebunan dataran tinggi Jawa Barat (Preanger), serta lot terkurasi dari Toraja, Aceh Gayo, Malang, dan Yogyakarta.',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'question' => 'Do you provide green bean samples for roasteries before purchasing?',
                'question_id' => 'Apakah Anda menyediakan sampel biji hijau untuk roastery sebelum membeli?',
                'answer' => 'Yes. We provide 250g–1kg sample packs for licensed roasters and coffee importers globally. You can request a sample kit by contacting our sales team via the export contact form.',
                'answer_id' => 'Ya. Kami menyediakan paket sampel 250g–1kg untuk roaster berlisensi dan importir kopi secara global. Anda dapat meminta kit sampel dengan menghubungi tim penjualan kami melalui formulir kontak ekspor.',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'question' => 'What export certifications and documentation do you provide?',
                'question_id' => 'Sertifikasi dan dokumentasi ekspor apa yang Anda sediakan?',
                'answer' => 'Every export shipment comes complete with a Certificate of Origin (COO), Phytosanitary Certificate, Bill of Lading, Commercial Invoice, Packing List, and Quality Analysis Lab Reports.',
                'answer_id' => 'Setiap pengiriman ekspor dilengkapi dengan Surat Keterangan Asal (COO), Sertifikat Fitosanitari, Bill of Lading, Faktur Komersial, Packing List, dan Laporan Hasil Analisis Kualitas Lab.',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }
}
