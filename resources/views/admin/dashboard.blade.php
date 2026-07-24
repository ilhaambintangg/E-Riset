@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Statistik')

@section('header_actions')
<div class="flex items-center gap-3">
    <a href="{{ url('/register-permit') }}" target="_blank" class="btn-brand btn-base inline-flex items-center gap-1.5 shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Create Research
    </a>
</div>
@endsection

@section('content')

<!-- Custom SaaS Dashboard Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Dashboard</h1>
        <p class="text-base text-white/85 font-normal mt-1">Selamat Datang Kembali</p>
        <p class="text-xs text-white/60 font-medium mt-1">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    @yield('header_actions')
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1: Total -->
    <div class="card-interactive h-[110px] flex items-center justify-between border-t-[3px] border-t-slate-800 p-5 shadow-xs relative overflow-hidden bg-white">
        <div class="flex flex-col justify-between h-full">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Permohonan</span>
            <span class="text-3xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['total'] ?? 0 }}</span>
        </div>
        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
            <i data-lucide="file-text" class="w-4.5 h-4.5 text-slate-600"></i>
        </div>
    </div>

    <!-- Stat Card 2: Pending -->
    <div class="card-interactive h-[110px] flex items-center justify-between border-t-[3px] border-t-[#F59E0B] p-5 shadow-xs relative overflow-hidden bg-white">
        <div class="flex flex-col justify-between h-full">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu</span>
            <span class="text-3xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['pending'] ?? 0 }}</span>
        </div>
        <div class="w-9 h-9 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
            <i data-lucide="clock" class="w-4.5 h-4.5 text-[#F59E0B]"></i>
        </div>
    </div>

    <!-- Stat Card 3: Processing -->
    <div class="card-interactive h-[110px] flex items-center justify-between border-t-[3px] border-t-[#2563EB] p-5 shadow-xs relative overflow-hidden bg-white">
        <div class="flex flex-col justify-between h-full">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Diproses</span>
            <span class="text-3xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['processing'] ?? 0 }}</span>
        </div>
        <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
            <i data-lucide="refresh-cw" class="w-4.5 h-4.5 text-[#2563EB]"></i>
        </div>
    </div>

    <!-- Stat Card 4: Approved -->
    <div class="card-interactive h-[110px] flex items-center justify-between border-t-[3px] border-t-[#16A34A] p-5 shadow-xs relative overflow-hidden bg-white">
        <div class="flex flex-col justify-between h-full">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Disetujui</span>
            <span class="text-3xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['approved'] ?? 0 }}</span>
        </div>
        <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
            <i data-lucide="check-circle" class="w-4.5 h-4.5 text-[#16A34A]"></i>
        </div>
    </div>
</div>

<!-- Table Section Card -->
<div class="card-static overflow-hidden animate-fade-up bg-white" style="animation-delay: 0.1s;">
    <div class="px-6 py-5 border-b border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-brand">
                <i data-lucide="history" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-fg-heading leading-tight m-0">Permohonan Terbaru</h2>
                <p class="text-xs text-fg-body-subtle mt-0.5">Update data per menit ini</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn-secondary btn-sm rounded-lg inline-flex items-center gap-1.5 px-4 py-2 border-border-default hover:bg-slate-50 font-semibold text-xs text-fg-body">
                <i data-lucide="filter" class="w-3.5 h-3.5 text-fg-body-subtle"></i> Filter
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="bg-brand-soft hover:bg-brand/15 text-brand font-bold px-4 py-2 rounded-lg text-xs transition-all inline-flex items-center gap-1.5 shrink-0">
                Lihat Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
    
    <!-- Table content -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-xs bg-slate-50">
                    <th class="px-6 py-4.5 bg-slate-50">No. Registrasi</th>
                    <th class="px-6 py-4.5 bg-slate-50">Pemohon</th>
                    <th class="px-6 py-4.5 bg-slate-50">Universitas</th>
                    <th class="px-6 py-4.5 bg-slate-50">Tanggal Masuk</th>
                    <th class="px-6 py-4.5 bg-slate-50">Status</th>
                    <th class="px-6 py-4.5 bg-slate-50 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                @forelse($recentSubmissions as $sub)
                    @php
                        // Get initials for avatar
                        $initials = collect(explode(' ', $sub->name))
                            ->map(fn($n) => substr($n, 0, 1))
                            ->take(2)
                            ->join('');
                        
                        // Map status styles
                        $statusStyles = match($sub->current_status) {
                            'Menunggu Verifikasi' => [
                                'bg' => 'bg-amber-50/70 text-[#F59E0B] border-amber-200/50',
                                'dot' => 'bg-[#F59E0B]'
                            ],
                            'Sedang Diproses' => [
                                'bg' => 'bg-blue-50/70 text-[#2563EB] border-blue-200/50',
                                'dot' => 'bg-[#2563EB]'
                            ],
                            'Disetujui' => [
                                'bg' => 'bg-emerald-50/70 text-[#16A34A] border-emerald-200/50',
                                'dot' => 'bg-[#16A34A]'
                            ],
                            'Ditolak' => [
                                'bg' => 'bg-red-50/70 text-[#DC2626] border-red-200/50',
                                'dot' => 'bg-[#DC2626]'
                            ],
                            default => [
                                'bg' => 'bg-slate-50 text-slate-600 border-slate-200',
                                'dot' => 'bg-slate-400'
                            ]
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-brand tracking-wide">{{ $sub->registration_number }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-fg-heading font-bold text-xs flex items-center justify-center shrink-0 border border-border-default shadow-2xs">
                                    {{ strtoupper($initials) }}
                                </div>
                                <span class="font-semibold text-sm text-fg-heading">{{ $sub->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-fg-body text-sm">{{ $sub->university }}</td>
                        <td class="px-6 py-4 text-fg-body-subtle text-xs">{{ $sub->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold {{ $statusStyles['bg'] }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $sub->current_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="btn-secondary btn-sm inline-flex items-center gap-1.5 rounded-lg px-4 py-1.5 font-semibold text-xs text-fg-body">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-fg-body-subtle"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-fg-body-subtle bg-white">
                            <div class="flex flex-col items-center justify-center py-6">
                                <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mb-3"></i>
                                <p class="font-semibold text-fg-heading">Belum ada data permohonan masuk.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer with Mockup Circular Pagination styling -->
    <div class="px-6 py-4.5 border-t border-border-default flex items-center justify-between bg-white">
        <p class="text-xs text-fg-body-subtle font-medium">Menampilkan {{ $recentSubmissions->count() }} dari {{ $recentSubmissions->count() }} permohonan</p>
        <div class="flex items-center gap-2">
            <button class="w-8 h-8 rounded-lg border border-border-default hover:bg-slate-50 flex items-center justify-center text-fg-body-subtle disabled:opacity-50 transition-colors" disabled>
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
            <button class="w-8 h-8 rounded-lg bg-brand text-white font-bold text-xs flex items-center justify-center shadow-sm">
                1
            </button>
            <button class="w-8 h-8 rounded-lg border border-border-default hover:bg-slate-50 flex items-center justify-center text-fg-body-subtle disabled:opacity-50 transition-colors" disabled>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>

@endsection
