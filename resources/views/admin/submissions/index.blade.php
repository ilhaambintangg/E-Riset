@extends('layouts.admin')

@section('title', 'Daftar Permohonan Masuk')
@section('header_title', 'Permohonan Masuk')

@section('content')

<div class="card-static overflow-hidden flex flex-col h-full animate-fade-up">
    
    <!-- Toolbar -->
    <div class="p-6 border-b border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        
        <!-- Filter Status -->
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex flex-1 sm:flex-none items-center gap-3">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="status" onchange="this.form.submit()" class="border border-border-default-medium rounded-default px-4 py-2.5 text-sm text-fg-body font-medium focus:ring-2 focus:ring-brand-soft focus:border-brand outline-none w-full sm:w-64 bg-neutral-primary-soft hover:bg-neutral-primary-medium cursor-pointer transition-colors">
                <option value="">Semua Status</option>
                <option value="Menunggu Verifikasi" {{ request('status') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Sedang Diproses" {{ request('status') === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>

        <!-- Search -->
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex-1 sm:max-w-md">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, no registrasi, universitas..." 
                       class="w-full pl-11 pr-10 py-2.5 border border-border-default-medium rounded-default text-sm font-medium focus:ring-2 focus:ring-brand-soft focus:border-brand outline-none bg-neutral-primary-soft placeholder:text-fg-body-subtle transition-all">
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
        <table class="w-full text-sm text-left">
            <thead class="bg-neutral-primary-soft text-fg-body-subtle font-bold uppercase tracking-wider text-[10px] border-b border-border-default">
                <tr>
                    <th class="px-6 py-4">No. Registrasi / Tanggal</th>
                    <th class="px-6 py-4">Informasi Pemohon</th>
                    <th class="px-6 py-4">Penelitian</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                @forelse($submissions as $sub)
                    <tr class="hover:bg-neutral-primary-soft transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-mono font-bold text-fg-brand mb-1">{{ $sub->registration_number }}</p>
                            <p class="text-xs text-fg-body-subtle">{{ $sub->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-fg-heading">{{ $sub->name }}</p>
                            <p class="text-xs text-fg-body-subtle mt-0.5"><i data-lucide="building-2" class="w-3 h-3 inline mr-1 text-brand-alt"></i> {{ $sub->university }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-fg-body line-clamp-2 max-w-xs">{{ $sub->title }}</p>
                        </td>
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
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="btn-secondary btn-sm inline-flex" title="Lihat Detail">
                                Detail <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-fg-body-subtle bg-white">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-neutral-primary-soft rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="inbox" class="w-8 h-8 text-neutral-tertiary"></i>
                                </div>
                                <p class="text-base font-bold text-fg-heading">Tidak ada data ditemukan</p>
                                <p class="text-sm mt-1">Belum ada permohonan yang sesuai dengan kriteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($submissions->hasPages())
        <div class="px-6 py-4 border-t border-border-default bg-neutral-primary-soft">
            {{ $submissions->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
