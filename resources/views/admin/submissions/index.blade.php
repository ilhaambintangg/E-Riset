@extends('layouts.admin')

@section('title', 'Daftar Permohonan Masuk')
@section('header_title', 'Permohonan Masuk')

@section('content')

<div class="card-static overflow-hidden flex flex-col h-full animate-fade-up bg-white">
    
    <!-- Toolbar -->
    <div class="p-6 border-b border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        
        <!-- Filter Status -->
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex flex-1 sm:flex-none items-center gap-3">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="relative w-full sm:w-64">
                <select name="status" onchange="this.form.submit()" class="w-full bg-white border border-border-default rounded-full px-5 py-2.5 text-xs text-fg-body font-semibold focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none cursor-pointer transition-all appearance-none pr-10">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Sedang Diproses" {{ request('status') === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle pointer-events-none"></i>
            </div>
        </form>

        <!-- Search -->
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex-1 sm:max-w-md">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, no registrasi, universitas..." 
                       class="w-full pl-11 pr-10 py-2.5 bg-neutral-primary-soft border border-transparent rounded-full text-xs font-semibold focus:outline-none focus:border-brand-alt focus:ring-2 focus:ring-brand-alt/20 transition-all placeholder:text-fg-body-subtle text-fg-body">
                @if(request('search'))
                    <a href="{{ route('admin.submissions.index', ['status' => request('status')]) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-fg-body-subtle hover:text-fg-danger">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b border-border-default text-fg-body-subtle font-bold uppercase tracking-wider text-xs">
                    <th class="px-6 py-4 bg-neutral-primary-medium">No. Registrasi / Tanggal</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Informasi Pemohon</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Penelitian</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium">Status</th>
                    <th class="px-6 py-4 bg-neutral-primary-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                @forelse($submissions as $sub)
                    @php
                        // Get initials for avatar
                        $initials = collect(explode(' ', $sub->name))
                            ->map(fn($n) => substr($n, 0, 1))
                            ->take(2)
                            ->join('');

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
                        <td class="px-6 py-4.5">
                            <p class="font-semibold text-brand tracking-wide text-sm mb-0.5">{{ $sub->registration_number }}</p>
                            <p class="text-xs text-fg-body-subtle">{{ $sub->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-neutral-primary-strong text-fg-heading font-bold text-sm flex items-center justify-center shrink-0 border border-border-default shadow-2xs">
                                    {{ strtoupper($initials) }}
                                </div>
                                <div>
                                    <span class="font-bold text-lg text-fg-heading block leading-tight">{{ $sub->name }}</span>
                                    <span class="text-xs text-fg-body-subtle mt-0.5 block"><i data-lucide="building-2" class="w-3.5 h-3.5 inline mr-0.5 text-brand"></i> {{ $sub->university }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4.5">
                            <p class="text-fg-body text-sm line-clamp-2 max-w-xs leading-relaxed font-medium" title="{{ $sub->title }}">{{ $sub->title }}</p>
                        </td>
                        <td class="px-6 py-4.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles['bg'] }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $sub->current_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-right">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="btn-secondary btn-sm inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 font-semibold text-xs text-fg-body" title="Lihat Detail">
                                Detail <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-fg-body-subtle"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-fg-body-subtle bg-white">
                            <div class="flex flex-col items-center justify-center py-6">
                                <div class="w-14 h-14 bg-neutral-primary-soft rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="inbox" class="w-7 h-7 text-neutral-tertiary"></i>
                                </div>
                                <p class="text-sm font-bold text-fg-heading">Tidak ada data ditemukan</p>
                                <p class="text-xs text-fg-body-subtle mt-1">Belum ada permohonan yang sesuai dengan kriteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($submissions->hasPages())
        <div class="px-6 py-4.5 border-t border-border-default bg-neutral-primary-soft flex justify-end">
            {{ $submissions->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
