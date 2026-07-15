<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks to safely truncate
        Schema::disableForeignKeyConstraints();
        DB::table('requirements')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('requirements')->insert([
            [
                'name' => 'Surat Pengantar Kampus / Instansi',
                'description' => 'Surat pengantar resmi dari pimpinan fakultas atau instansi asal (format PDF, maksimal 2 MB).',
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Proposal Penelitian',
                'description' => 'Proposal penelitian lengkap yang berisi latar belakang, rumusan masalah, dan metodologi (format PDF, maksimal 2 MB).',
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Update the FAQ answer
        DB::table('faqs')
            ->where('question', 'Dokumen apa saja yang wajib diunggah?')
            ->update([
                'answer' => 'Dokumen wajib yang harus diunggah di formulir pendaftaran adalah Surat Pengantar dari Kampus/Instansi asal dan Proposal Penelitian. Kedua dokumen wajib berformat PDF dengan ukuran maksimal masing-masing 2 MB.',
                'updated_at' => now(),
            ]);
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('requirements')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('requirements')->insert([
            ['name' => 'Surat Permohonan', 'description' => 'Surat permohonan resmi izin penelitian ditujukan kepada Ketua Pengadilan Tinggi Tanjungkarang.', 'is_required' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Proposal Penelitian', 'description' => 'Proposal penelitian lengkap yang berisi latar belakang, rumusan masalah, dan metodologi.', 'is_required' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kartu Tanda Penduduk (KTP)', 'description' => 'KTP Pemohon asli/fotokopi (scan berwarna).', 'is_required' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kartu Tanda Mahasiswa (KTM)', 'description' => 'KTM Pemohon asli/fotokopi (scan berwarna, jika pemohon adalah mahasiswa).', 'is_required' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Surat Pengantar Kampus / Instansi', 'description' => 'Surat pengantar resmi dari pimpinan fakultas atau instansi asal.', 'is_required' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dokumen Pendukung Lainnya', 'description' => 'Kuesioner, daftar pertanyaan wawancara, atau berkas pendukung riset lainnya.', 'is_required' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('faqs')
            ->where('question', 'Dokumen apa saja yang wajib diunggah?')
            ->update([
                'answer' => 'Dokumen wajib meliputi Surat Permohonan Izin Penelitian, Proposal Penelitian, KTP, KTM (untuk mahasiswa), dan Surat Pengantar dari Kampus/Instansi asal.',
                'updated_at' => now(),
            ]);
    }
};
