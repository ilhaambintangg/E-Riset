@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Statistik')

@section('header_actions')
<div class="flex items-center gap-3 animate-fade-in">
    <a href="{{ url('/register-permit') }}" target="_blank" class="btn-brand btn-base inline-flex items-center gap-1.5 shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Create Research
    </a>
    <a href="{{ url('/') }}" target="_blank" class="btn-secondary btn-base inline-flex items-center gap-1.5 shadow-2xs">
        <i data-lucide="external-link" class="w-4 h-4 text-fg-body-subtle"></i> Public View
    </a>
</div>
@endsection

@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-up">
    <!-- Stat Card 1: Total -->
    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-brand relative overflow-hidden bg-white">
        <div class="w-14 h-14 rounded-2xl bg-brand-soft flex items-center justify-center shrink-0 transition-transform duration-300 hover:scale-105">
            <i data-lucide="file-text" class="w-7 h-7 text-brand"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Total Permohonan</p>
            <p class="text-3xl font-extrabold text-fg-heading leading-none">{{ $stats['total'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Stat Card 2: Pending -->
    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-warning relative overflow-hidden bg-white">
        <div class="w-14 h-14 rounded-2xl bg-warning-soft flex items-center justify-center shrink-0 transition-transform duration-300 hover:scale-105">
            <i data-lucide="clock" class="w-7 h-7 text-fg-warning"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Menunggu</p>
            <p class="text-3xl font-extrabold text-fg-heading leading-none">{{ $stats['pending'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Stat Card 3: Processing -->
    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-info relative overflow-hidden bg-white">
        <div class="w-14 h-14 rounded-2xl bg-info-soft flex items-center justify-center shrink-0 transition-transform duration-300 hover:scale-105">
            <i data-lucide="refresh-cw" class="w-7 h-7 text-fg-info"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Diproses</p>
            <p class="text-3xl font-extrabold text-fg-heading leading-none">{{ $stats['processing'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Stat Card 4: Approved -->
    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-success relative overflow-hidden bg-white">
        <div class="w-14 h-14 rounded-2xl bg-success-soft flex items-center justify-center shrink-0 transition-transform duration-300 hover:scale-105">
            <i data-lucide="check-circle" class="w-7 h-7 text-fg-success"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Disetujui</p>
            <p class="text-3xl font-extrabold text-fg-heading leading-none">{{ $stats['approved'] ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Table Section Card -->
<div class="card-static overflow-hidden animate-fade-up bg-white" style="animation-delay: 0.1s;">
    <div class="px-6 py-5 border-b border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-neutral-primary-soft flex items-center justify-center text-brand">
                <i data-lucide="history" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-fg-heading leading-tight m-0">Permohonan Terbaru</h2>
                <p class="text-xs text-fg-body-subtle mt-0.5">Update data per menit ini</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn-secondary btn-sm rounded-full inline-flex items-center gap-1.5 px-4 py-2 border-border-default hover:bg-neutral-primary-soft font-semibold text-xs text-fg-body">
                <i data-lucide="filter" class="w-3.5 h-3.5 text-fg-body-subtle"></i> Filter
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="bg-brand-soft hover:bg-brand/15 text-brand font-bold px-4 py-2 rounded-full text-xs transition-all inline-flex items-center gap-1.5 shrink-0">
                Lihat Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
    
    <!-- Table content -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b border-border-default text-fg-body-subtle font-bold uppercase tracking-wider text-xs">
                    <th class="px-6 py-4 bg-neutral-primary-medium">No. Registrasi</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Pemohon</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Universitas</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Tanggal Masuk</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Status</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium text-right">Aksi</th>
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
                                'bg' => 'bg-warning-soft text-fg-warning border-warning-subtle',
                                'dot' => 'bg-warning'
                            ],
                            'Sedang Diproses' => [
                                'bg' => 'bg-info-soft text-fg-info border-border-info-subtle',
                                'dot' => 'bg-info'
                            ],
                            'Disetujui' => [
                                'bg' => 'bg-success-soft text-fg-success border-success-subtle',
                                'dot' => 'bg-success'
                            ],
                            'Ditolak' => [
                                'bg' => 'bg-danger-soft text-fg-danger border-danger-subtle',
                                'dot' => 'bg-danger'
                            ],
                            default => [
                                'bg' => 'bg-neutral-primary-strong text-fg-body border border-border-default',
                                'dot' => 'bg-fg-body-subtle'
                            ]
                        };
                    @endphp
                    <tr class="hover:bg-neutral-primary-soft/50 transition-colors">
                        <td class="px-6 py-4.5 font-semibold text-brand tracking-wide text-sm">{{ $sub->registration_number }}</td>
                        <td class="px-6 py-4.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-neutral-primary-strong text-fg-heading font-bold text-sm flex items-center justify-center shrink-0 border-border-default shadow-2xs">
                                    {{ strtoupper($initials) }}
                                </div>
                                <span class="font-bold text-lg text-fg-heading">{{ $sub->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4.5 text-fg-body text-base">{{ $sub->university }}</td>
                        <td class="px-6 py-4.5 text-fg-body-subtle text-sm">{{ $sub->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles['bg'] }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $sub->current_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-right">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="btn-secondary btn-sm inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 font-semibold text-xs text-fg-body">
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
            <button class="w-8 h-8 rounded-full border border-border-default hover:bg-neutral-primary-soft flex items-center justify-center text-fg-body-subtle disabled:opacity-50 transition-colors" disabled>
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
            <button class="w-8 h-8 rounded-full bg-brand text-white font-bold text-xs flex items-center justify-center shadow-sm">
                1
            </button>
            <button class="w-8 h-8 rounded-full border border-border-default hover:bg-neutral-primary-soft flex items-center justify-center text-fg-body-subtle disabled:opacity-50 transition-colors" disabled>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>

@endsection
