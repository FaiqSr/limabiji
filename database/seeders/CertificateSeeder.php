<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            [
                'name' => 'Halal Indonesia',
                'issuer' => 'BPJPH Kementerian Agama RI',
                'certificate_number' => 'ID32110001234560723',
                'logo' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=300&auto=format&fit=crop',
                'description' => 'Sertifikasi jaminan produk halal resmi untuk seluruh lini proses kopi fermentasi enzimatik dan turunan kopi specialty.',
                'issued_date' => '2023-07-15',
                'expiry_date' => '2027-07-15',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'BPOM RI',
                'issuer' => 'Badan Pengawas Obat dan Makanan RI',
                'certificate_number' => 'BPOM RI MD 867010001234',
                'logo' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=300&auto=format&fit=crop',
                'description' => 'Izin edar resmi keamanan pangan olahan untuk komoditas kopi specialty berstandar mutu tinggi.',
                'issued_date' => '2023-09-10',
                'expiry_date' => '2028-09-10',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Organik Indonesia',
                'issuer' => 'Lembaga Sertifikasi Organik (SNI 6729:2016)',
                'certificate_number' => '042/LSO-INOFICE/SNI-ORG/2023',
                'logo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=300&auto=format&fit=crop',
                'description' => 'Sertifikasi pertanian organik bebas pestisida sintetis dan pupuk kimia pada kebun kemitraan petani binaan.',
                'issued_date' => '2023-11-01',
                'expiry_date' => '2026-11-01',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'HACCP Codex Alimentarius',
                'issuer' => 'Food Safety Assurance Certification',
                'certificate_number' => 'HACCP-ID-2024-8841',
                'logo' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=300&auto=format&fit=crop',
                'description' => 'Sistem analisis bahaya dan pengendalian titik kritis pada fasilitas bioreaktor dan pemrosesan kopi.',
                'issued_date' => '2024-01-20',
                'expiry_date' => '2027-01-20',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'ISO 22000:2018',
                'issuer' => 'International Organization for Standardization',
                'certificate_number' => 'ISO22000-FSMS-2024-912',
                'logo' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=300&auto=format&fit=crop',
                'description' => 'Standar internasional sistem manajemen keamanan pangan dari panen ceri hingga pengemasan ekspor.',
                'issued_date' => '2024-02-15',
                'expiry_date' => '2027-02-15',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'SCA Quality Standard 84+',
                'issuer' => 'Specialty Coffee Association Protocol',
                'certificate_number' => 'SCA-ID-84PLUS-2024',
                'logo' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=300&auto=format&fit=crop',
                'description' => 'Verifikasi skor cupping minimum 84+ poin Specialty Coffee Association dengan profil rasa konsisten.',
                'issued_date' => '2024-03-01',
                'expiry_date' => '2026-03-01',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($certificates as $cert) {
            Certificate::updateOrCreate(
                ['certificate_number' => $cert['certificate_number']],
                $cert
            );
        }
    }
}
