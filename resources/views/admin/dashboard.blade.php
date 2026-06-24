@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Statistik')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-up">
    <!-- Stat Cards -->
    <div class="card-interactive p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-base bg-brand-soft flex items-center justify-center shrink-0">
            <i data-lucide="file-text" class="w-7 h-7 text-fg-brand"></i>
        </div>
        <div>
            <p class="text-[11px] font-bold text-fg-body-subtle uppercase tracking-widest mb-1">Total Permohonan</p>
            <p class="text-3xl font-bold text-fg-heading font-heading">{{ $stats['total'] ?? 0 }}</p>
        </div>
    </div>

    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-warning">
        <div class="w-14 h-14 rounded-base bg-warning-soft flex items-center justify-center shrink-0">
            <i data-lucide="clock" class="w-7 h-7 text-fg-warning"></i>
        </div>
        <div>
            <p class="text-[11px] font-bold text-fg-warning uppercase tracking-widest mb-1">Menunggu</p>
            <p class="text-3xl font-bold text-fg-heading font-heading">{{ $stats['pending'] ?? 0 }}</p>
        </div>
    </div>

    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-info">
        <div class="w-14 h-14 rounded-base bg-info-soft flex items-center justify-center shrink-0">
            <i data-lucide="loader" class="w-7 h-7 text-fg-info animate-spin"></i>
        </div>
        <div>
            <p class="text-[11px] font-bold text-fg-info uppercase tracking-widest mb-1">Diproses</p>
            <p class="text-3xl font-bold text-fg-heading font-heading">{{ $stats['processing'] ?? 0 }}</p>
        </div>
    </div>

    <div class="card-interactive p-6 flex items-center gap-5 border-l-4 border-l-success">
        <div class="w-14 h-14 rounded-base bg-success-soft flex items-center justify-center shrink-0">
            <i data-lucide="check-circle-2" class="w-7 h-7 text-fg-success"></i>
        </div>
        <div>
            <p class="text-[11px] font-bold text-fg-success uppercase tracking-widest mb-1">Disetujui</p>
            <p class="text-3xl font-bold text-fg-heading font-heading">{{ $stats['approved'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="card-static overflow-hidden animate-fade-up" style="animation-delay: 0.1s;">
    <div class="px-6 py-5 border-b border-border-default flex items-center justify-between bg-white">
        <h2 class="text-h4 text-fg-heading flex items-center gap-2 m-0">
            <i data-lucide="clock" class="w-5 h-5 text-brand-alt"></i> Permohonan Terbaru
        </h2>
        <a href="{{ route('admin.submissions.index') }}" class="text-xs font-bold text-fg-body-subtle hover:text-fg-brand flex items-center gap-1 transition-colors bg-neutral-primary-medium px-3 py-1.5 rounded-sm border border-border-default">
            Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-neutral-primary-soft text-fg-body-subtle font-bold uppercase tracking-wider text-[10px] border-b border-border-default">
                <tr>
                    <th class="px-6 py-4">No. Registrasi</th>
                    <th class="px-6 py-4">Pemohon</th>
                    <th class="px-6 py-4">Universitas</th>
                    <th class="px-6 py-4">Tanggal Masuk</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                @forelse($recentSubmissions as $sub)
                    <tr class="hover:bg-neutral-primary-soft transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-fg-brand">{{ $sub->registration_number }}</td>
                        <td class="px-6 py-4">{{ $sub->name }}</td>
                        <td class="px-6 py-4">{{ $sub->university }}</td>
                        <td class="px-6 py-4 text-fg-body-subtle">{{ $sub->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = match($sub->current_status) {
                                    'Menunggu Verifikasi' => 'badge-warning',
                                    'Sedang Diproses' => 'badge-info',
                                    'Disetujui' => 'badge-success',
                                    'Ditolak' => 'badge-danger',
                                    default => 'badge-neutral'
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">
                                {{ $sub->current_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="btn-secondary btn-sm inline-flex">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-fg-body-subtle bg-white">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mb-3"></i>
                                <p class="font-semibold">Belum ada data permohonan masuk.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
