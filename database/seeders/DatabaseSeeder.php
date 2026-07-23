<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Requirement;
use App\Models\Faq;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Seeder
        Admin::create([
            'name' => 'Administrator E-Riset',
            'username' => 'admin',
            'email' => 'admin@pt-tanjungkarang.go.id',
            'role' => 'admin',
            'password' => Hash::make('@Admin123'),
        ]);

        // Hukum Seeder
        Admin::create([
            'name' => 'Hukum E-Riset',
            'username' => 'hukum',
            'email' => 'hukum@pt-tanjungkarang.go.id',
            'role' => 'hukum',
            'password' => Hash::make('@Hukum123'),
        ]);

        // Requirements Seeder
        $requirements = [
            ['name' => 'Surat Pengantar Kampus / Instansi', 'description' => 'Surat pengantar resmi dari pimpinan fakultas atau instansi asal (format PDF, maksimal 2 MB).', 'is_required' => true],
            ['name' => 'Proposal Penelitian', 'description' => 'Proposal penelitian lengkap yang berisi latar belakang, rumusan masalah, dan metodologi (format PDF, maksimal 2 MB).', 'is_required' => true],
        ];

        foreach ($requirements as $req) {
            Requirement::create($req);
        }

        // FAQs Seeder
        $faqs = [
            [
                'question' => 'Berapa lama proses pengajuan izin penelitian?',
                'answer' => 'Proses verifikasi dan persetujuan izin penelitian di Pengadilan Tinggi Tanjungkarang memakan waktu 3 s.d. 5 hari kerja sejak berkas dinyatakan lengkap.'
            ],
            [
                'question' => 'Dokumen apa saja yang wajib diunggah?',
                'answer' => 'Dokumen wajib yang harus diunggah di formulir pendaftaran adalah Surat Pengantar dari Kampus/Instansi asal dan Proposal Penelitian. Kedua dokumen wajib berformat PDF dengan ukuran maksimal masing-masing 2 MB.'
            ],
            [
                'question' => 'Bagaimana cara memantau status permohonan saya?',
                'answer' => 'Anda dapat memantau status permohonan melalui halaman "Cek Status" dengan memasukkan Nomor Registrasi unik yang Anda dapatkan setelah berhasil mengirimkan formulir permohonan.'
            ],
            [
                'question' => 'Apa yang harus dilakukan jika permohonan ditolak?',
                'answer' => 'Jika ditolak, silakan baca catatan penolakan dari admin pada fitur lacak status. Anda dapat memperbaiki berkas atau data yang kurang sesuai lalu mengirimkan permohonan baru.'
            ],
            [
                'question' => 'Apakah ada biaya untuk pengajuan izin penelitian?',
                'answer' => 'Tidak ada biaya sama sekali. Pengurusan permohonan izin penelitian elektronik (E-RISET) di Pengadilan Tinggi Tanjungkarang bersifat gratis dan transparan.'
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // Announcements Seeder
        Announcement::create([
            'title' => 'Layanan E-RISET Resmi Dirilis',
            'content' => 'Selamat datang di E-RISET (Electronic Research Permit System) Pengadilan Tinggi Tanjungkarang. Sistem ini digunakan untuk mengajukan izin penelitian secara online bagi mahasiswa, dosen, dan peneliti umum secara mandiri, transparan, dan efisien.',
            'is_active' => true,
        ]);

    }
}
