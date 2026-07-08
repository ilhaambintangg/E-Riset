@extends('layouts.admin')

@section('title', 'Permohonan Masuk')
@section('header_title', 'Permohonan Masuk')
@section('header_subtitle', 'Kelola dan tinjau semua berkas permohonan yang baru masuk ke sistem.')

@section('header_actions')
<div class="flex items-center gap-3">
    <!-- Collapsible filter button -->
    <button @click="showFilters = !showFilters" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold rounded-lg text-xs transition-all shadow-sm cursor-pointer">
        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-slate-500"></i> Filter
    </button>
    
    <!-- Export Data button -->
    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-brand to-brand-medium hover:from-brand-medium hover:to-brand text-white font-semibold rounded-lg text-xs transition-all shadow-sm cursor-pointer">
        <i data-lucide="download" class="w-4 h-4"></i> Export Data
    </a>
</div>
@endsection

@section('content')
<div x-data="{ showFilters: false }" class="w-full">
    
    <!-- Collapsible Filter Panel -->
    <div x-show="showFilters" x-collapse class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm mb-5" x-cloak>
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <!-- Filter Status Form -->
            <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="flex flex-col gap-1 w-full sm:w-64">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Permohonan</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 font-semibold focus:ring-2 focus:ring-slate-900/5 focus:border-slate-400 outline-none cursor-pointer appearance-none pr-10">
                            <option value="">Semua Status</option>
                            <option value="Menunggu Verifikasi" {{ request('status') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi (Review)</option>
                            <option value="Sedang Diproses" {{ request('status') === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses (Revisi)</option>
                            <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui (Lolos Verifikasi)</option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak (Dokumen Kurang)</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                
                @if(request('status') || request('search'))
                    <div class="flex flex-col gap-1 self-end">
                        <a href="{{ route('admin.submissions.index') }}" class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-100 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col mb-6">
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-separate border-spacing-0">
                <thead>
                    <tr class="text-white font-semibold uppercase tracking-wider text-xs bg-gradient-to-r from-[#0a2240] to-[#143a66]">
                        <th class="px-6 py-4.5 rounded-tl-xl border-b border-slate-200/80">ID Permohonan</th>
                        <th class="px-6 py-4.5 border-b border-slate-200/80">Nama Pemohon</th>
                        <th class="px-6 py-4.5 border-b border-slate-200/80">Kategori</th>
                        <th class="px-6 py-4.5 border-b border-slate-200/80">Status</th>
                        <th class="px-6 py-4.5 text-center rounded-tr-xl border-b border-slate-200/80">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                    @forelse($submissions as $sub)
                        @php
                            // Determine Category/Research Type fallback
                            $category = $sub->research_type ?: ($sub->university ?: 'Perdata Umum');
                            
                            // Map statuses to match mockup exactly
                            $statusMap = match($sub->current_status) {
                                'Disetujui' => [
                                    'label' => 'Lolos Verifikasi',
                                    'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'dot' => 'bg-emerald-500'
                                ],
                                'Sedang Diproses' => [
                                    'label' => 'Proses Revisi',
                                    'bg' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'dot' => 'bg-amber-500'
                                ],
                                'Menunggu Verifikasi' => [
                                    'label' => 'Menunggu Review',
                                    'bg' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'dot' => 'bg-amber-500'
                                ],
                                'Ditolak' => [
                                    'label' => 'Dokumen Kurang',
                                    'bg' => 'bg-red-50 text-red-700 border-red-100',
                                    'dot' => 'bg-red-500'
                                ],
                                default => [
                                    'label' => $sub->current_status,
                                    'bg' => 'bg-slate-50 text-slate-600 border-slate-200',
                                    'dot' => 'bg-slate-400'
                                ]
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <!-- ID Permohonan -->
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-500 text-xs font-mono leading-none">{{ $sub->registration_number }}</span>
                            </td>
                            <!-- Nama Pemohon & Email -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-sm text-slate-800 leading-tight">{{ $sub->name }}</span>
                                    <span class="text-xs text-slate-400 font-medium mt-0.5">{{ $sub->email }}</span>
                                </div>
                            </td>
                            <!-- Kategori -->
                            <td class="px-6 py-4 text-slate-600 text-[13px] font-medium">
                                {{ $category }}
                            </td>
                            <!-- Status Badges with Dot -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusMap['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusMap['dot'] }}"></span>
                                    {{ $statusMap['label'] }}
                                </span>
                            </td>
                            <!-- Actions (Eye & Download) -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- View Detail -->
                                    <a href="{{ route('admin.submissions.show', $sub->id) }}" class="text-slate-400 hover:text-slate-800 transition-colors" title="Lihat Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Download Permit File -->
                                    @if($sub->current_status === 'Disetujui')
                                        <a href="{{ route('admin.submissions.download', $sub->id) }}" class="text-slate-400 hover:text-slate-800 transition-colors" title="Unduh Izin">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        <span class="text-slate-200 cursor-not-allowed" title="Menunggu Persetujuan">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400 bg-white">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="inbox" class="w-7 h-7 text-slate-300"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">Tidak ada data ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada permohonan yang sesuai dengan kriteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer / Pagination -->
        @if($submissions->hasPages() || $submissions->total() > 0)
            <div class="px-6 py-4.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white text-xs text-slate-500">
                <div>
                    Menampilkan <strong>{{ $submissions->firstItem() ?? 0 }}</strong> sampai <strong>{{ $submissions->lastItem() ?? 0 }}</strong> dari <strong>{{ $submissions->total() }}</strong> entri
                </div>
                
                @if($submissions->hasPages())
                    <div class="flex items-center gap-1">
                        {{-- Previous Page Link --}}
                        @if ($submissions->onFirstPage())
                            <span class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </span>
                        @else
                            <a href="{{ $submissions->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif

                        {{-- Page Links --}}
                        @foreach ($submissions->getUrlRange(1, $submissions->lastPage()) as $page => $url)
                            @if ($page == $submissions->currentPage())
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#0a2240] to-[#143a66] text-white font-bold flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 font-semibold transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($submissions->hasMorePages())
                            <a href="{{ $submissions->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300 cursor-not-allowed">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Dynamic Summary Cards (Bottom of the page) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <!-- Total Masuk -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 text-blue-600">
                <i data-lucide="inbox" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Masuk</p>
                <p class="text-2xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Terverifikasi -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm flex items-center">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0 text-emerald-600">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Terverifikasi</p>
                <p class="text-2xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['approved'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm flex items-center">
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 text-amber-600">
                <i data-lucide="hourglass" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-extrabold text-slate-900 leading-none mt-1">{{ ($stats['pending'] ?? 0) + ($stats['processing'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Ditolak/Revisi -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm flex items-center">
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center shrink-0 text-red-600">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Ditolak/Revisi</p>
                <p class="text-2xl font-extrabold text-slate-900 leading-none mt-1">{{ $stats['rejected'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
