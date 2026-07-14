@extends('layouts.admin')

@section('title', 'EDVOKAT | Admin Panel')

@section('header_title')
    @php
        $page = request()->query('page', 'dashboard');
        $title = 'Dashboard EDVOKAT';
        if ($page === 'advokat') $title = 'Data Advokat';
        if ($page === 'permohonan') $title = 'Permohonan';
        if ($page === 'verifikasi') $title = 'Verifikasi';
        if ($page === 'dokumen') $title = 'Dokumen';
        if ($page === 'laporan') $title = 'Laporan EDVOKAT';
        if ($page === 'pengaturan') $title = 'Pengaturan EDVOKAT';
        echo $title;
    @endphp
@endsection

@section('content')
<div class="flex flex-col items-center justify-center py-16 px-4 text-center animate-fade-up">
    <!-- Coming Soon Icon Graphic -->
    <div class="relative w-28 h-28 bg-white border border-border-default rounded-base flex items-center justify-center shadow-md mb-8">
        <div class="absolute -top-3 -right-3 bg-brand-alt text-brand-strong text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border-2 border-white shadow-sm">
            Coming Soon
        </div>
        <i data-lucide="construction" class="w-12 h-12 text-brand-alt animate-bounce"></i>
    </div>

    <h1 class="text-3xl font-black tracking-tight text-fg-heading">EVOKAT</h1>
    <p class="text-sm text-brand-medium font-bold uppercase tracking-widest mt-1">Sistem Administrasi Advokat</p>
    
    <div class="h-[2px] w-16 bg-border-default my-6 mx-auto"></div>

    <h2 class="text-xl font-bold text-fg-heading">Fitur sedang dalam pengembangan</h2>
    <p class="text-sm text-fg-body-subtle font-medium mt-3 max-w-md leading-relaxed">
        Halaman ini sedang dipersiapkan untuk mengelola modul {{ strtolower($title) }} di lingkungan Pengadilan Tinggi Tanjungkarang.
    </p>
</div>
@endsection
