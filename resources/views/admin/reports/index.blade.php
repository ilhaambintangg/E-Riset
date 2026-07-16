@extends('layouts.admin')

@section('title', 'Laporan Statistik')
@section('header_title', 'Laporan Statistik')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-fade-up">
    <p class="text-xs text-fg-body-subtle">Statistik permohonan izin riset yang masuk berdasarkan tahun pilihan.</p>
    
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-3">
        <label for="year" class="text-xs font-bold text-fg-heading">Pilih Tahun:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="border border-border-default-medium rounded-default px-4 py-2 text-xs text-fg-body font-medium focus:ring-2 focus:ring-brand-soft focus:border-brand outline-none bg-white cursor-pointer transition-all">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- Laporan Export Panel -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 animate-fade-up" style="animation-delay: 0.05s;">
    <!-- Unduh Laporan Bulanan Card -->
    <div class="card-static p-5 bg-white border border-border-default flex flex-col justify-between">
        <div>
            <h4 class="text-xs font-bold text-fg-heading uppercase tracking-wider mb-2 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-brand-alt"></i> Unduh Laporan Bulanan
            </h4>
            <p class="text-xs text-fg-body-subtle leading-relaxed mb-4">Unduh rekapitulasi data permohonan, persetujuan, penolakan, dan draf proses per bulan tertentu.</p>
        </div>
        <form action="{{ route('admin.reports.exportMonthly') }}" method="GET" target="_blank" class="space-y-4 w-full">
            <input type="hidden" name="year" value="{{ $year }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5">Pilih Bulan</label>
                    <select name="month" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                        @php
                            $monthsIndo = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $currMonth = date('n');
                        @endphp
                        @foreach($monthsIndo as $num => $name)
                            <option value="{{ $num }}" {{ $num == $currMonth ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5">Universitas / Instansi</label>
                    <select name="university" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                        <option value="">Semua Universitas</option>
                        @foreach($universities as $uni)
                            <option value="{{ $uni->name }}">{{ $uni->name }}</option>
                        @endforeach
                        <option value="Lainnya">Lainnya (Tulis Manual)</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-border-default-subtle">
                <button type="submit" name="format" value="excel" class="btn-secondary btn-xs py-2 px-3.5 flex items-center gap-1.5 font-bold hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-success"></i> Excel
                </button>
                <button type="submit" name="format" value="pdf" class="btn-brand btn-xs py-2 px-3.5 flex items-center gap-1.5 font-bold hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Unduh Laporan Tahunan Card -->
    <div class="card-static p-5 bg-white border border-border-default flex flex-col justify-between">
        <div>
            <h4 class="text-xs font-bold text-fg-heading uppercase tracking-wider mb-2 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-4 h-4 text-brand-alt"></i> Unduh Laporan Tahunan
            </h4>
            <p class="text-xs text-fg-body-subtle leading-relaxed mb-4">Unduh rekapitulasi data statistik tahunan berisi akumulasi permohonan per bulan untuk tahun terpilih.</p>
        </div>
        <form action="{{ route('admin.reports.exportYearly') }}" method="GET" target="_blank" class="space-y-4 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5">Pilih Tahun</label>
                    <select name="year" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5">Universitas / Instansi</label>
                    <select name="university" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                        <option value="">Semua Universitas</option>
                        @foreach($universities as $uni)
                            <option value="{{ $uni->name }}">{{ $uni->name }}</option>
                        @endforeach
                        <option value="Lainnya">Lainnya (Tulis Manual)</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-border-default-subtle">
                <button type="submit" name="format" value="excel" class="btn-secondary btn-xs py-2 px-3.5 flex items-center gap-1.5 font-bold hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-success"></i> Excel
                </button>
                <button type="submit" name="format" value="pdf" class="btn-brand btn-xs py-2 px-3.5 flex items-center gap-1.5 font-bold hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                </button>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-up" style="animation-delay: 0.1s;">
    <!-- Chart: Monthly Submissions -->
    <div class="lg:col-span-2 card-static p-6 bg-white">
        <h3 class="text-sm font-bold text-fg-heading mb-6 flex items-center gap-2 m-0">
            <i data-lucide="bar-chart-3" class="w-4 h-4 text-brand-alt"></i> Tren Permohonan Tahun {{ $year }}
        </h3>
        <div class="h-80 w-full relative">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Chart: Status Distribution -->
    <div class="lg:col-span-1 card-static p-6 bg-white flex flex-col">
        <h3 class="text-sm font-bold text-fg-heading mb-6 flex items-center gap-2 m-0">
            <i data-lucide="pie-chart" class="w-4 h-4 text-brand-alt"></i> Distribusi Status
        </h3>
        <div class="h-60 w-full relative flex items-center justify-center">
            <canvas id="statusChart"></canvas>
        </div>
        
        <div class="mt-auto pt-6 space-y-3 border-t border-border-default-subtle">
            @php
                $colors = [
                    'Menunggu Verifikasi' => 'bg-warning',
                    'Sedang Diproses' => 'bg-info',
                    'Disetujui' => 'bg-success',
                    'Ditolak' => 'bg-danger',
                ];
                $totalStatus = array_sum($statusStats);
            @endphp
            
            @foreach($statusStats as $label => $count)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $colors[$label] ?? 'bg-neutral-quaternary' }}"></div>
                        <span class="font-medium text-fg-body-subtle">{{ $label }}</span>
                    </div>
                    <span class="font-bold text-fg-heading">{{ $count }} <span class="text-[10px] text-fg-body-subtle font-normal ml-1">({{ $totalStatus > 0 ? round(($count/$totalStatus)*100) : 0 }}%)</span></span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Laporan & Ringkasan Detail Section -->
<div class="mt-8 card-static p-6 bg-white border border-border-default animate-fade-up" style="animation-delay: 0.15s;">
    <h3 class="text-sm font-bold text-fg-heading mb-6 flex items-center gap-2 m-0 border-b border-border-default-subtle pb-4">
        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-brand-alt"></i> Detail & Ringkasan Laporan
    </h3>

    <!-- Filter Form -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 pb-6 border-b border-border-default-subtle">
        <input type="hidden" name="year" value="{{ $year }}">
        
        <div>
            <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5 tracking-wider">Universitas / Instansi</label>
            <select name="university" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                <option value="">Semua Universitas</option>
                @foreach($universities as $uni)
                    <option value="{{ $uni->name }}" {{ request('university') == $uni->name ? 'selected' : '' }}>{{ $uni->name }}</option>
                @endforeach
                <option value="Lainnya" {{ request('university') == 'Lainnya' ? 'selected' : '' }}>Lainnya (Input Manual)</option>
            </select>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5 tracking-wider">Status Permohonan</label>
            <select name="status" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white cursor-pointer">
                <option value="">Semua Status</option>
                <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5 tracking-wider">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-fg-body-subtle uppercase mb-1.5 tracking-wider">Tanggal Selesai</label>
            <div class="flex gap-2">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-border-default rounded-default px-3 py-2 text-xs text-fg-body font-medium outline-none focus:ring-2 focus:ring-brand-soft bg-white">
                <button type="submit" class="btn-brand btn-xs px-4 flex items-center justify-center font-bold">
                    Filter
                </button>
                @if(request()->anyFilled(['university', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('admin.reports.index', ['year' => $year]) }}" class="btn-secondary btn-xs px-3 flex items-center justify-center font-bold">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Ringkasan Laporan (Stats grid) -->
    <div class="mb-8">
        <h4 class="text-xs font-bold text-fg-heading uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-4 h-4 text-brand-alt"></i> Ringkasan Laporan
        </h4>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <div class="bg-brand-softer border border-border-brand-subtle p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-brand font-bold uppercase mb-1.5 tracking-wider">Total Permohonan</p>
                <p class="text-xl font-black text-fg-brand">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">UNILA</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['unila'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">UBL</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['ubl'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">Teknokrat</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['teknokrat'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">Darmajaya</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['darmajaya'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">Mitra Indo</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['mitra'] }}</p>
            </div>
            <div class="bg-neutral-primary-soft border border-border-default p-4 rounded-xl text-center shadow-sm">
                <p class="text-[9px] text-fg-body-subtle font-bold uppercase mb-1.5 tracking-wider">Lainnya</p>
                <p class="text-xl font-bold text-fg-heading">{{ $summary['lainnya'] }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto border border-border-default rounded-xl">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-neutral-primary-soft border-b border-border-default">
                    <th class="p-3 font-bold text-fg-heading uppercase text-center w-[5%]">No</th>
                    <th class="p-3 font-bold text-fg-heading uppercase w-[15%]">No. Registrasi</th>
                    <th class="p-3 font-bold text-fg-heading uppercase w-[20%]">Nama Pemohon</th>
                    <th class="p-3 font-bold text-fg-heading uppercase w-[25%]">Universitas / Instansi</th>
                    <th class="p-3 font-bold text-fg-heading uppercase w-[15%]">Program Studi</th>
                    <th class="p-3 font-bold text-fg-heading uppercase text-center w-[10%]">Status</th>
                    <th class="p-3 font-bold text-fg-heading uppercase text-center w-[10%]">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-default-subtle">
                @forelse($submissions as $index => $sub)
                    <tr class="hover:bg-neutral-primary-soft/50 transition-colors">
                        <td class="p-3 text-center text-fg-body-subtle">{{ $index + 1 }}</td>
                        <td class="p-3 font-mono font-bold text-fg-brand">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="hover:underline">
                                {{ $sub->registration_number }}
                            </a>
                        </td>
                        <td class="p-3 font-semibold text-fg-heading">{{ $sub->name }}</td>
                        <td class="p-3 text-fg-body">{{ $sub->university }}</td>
                        <td class="p-3 text-fg-body-subtle">{{ $sub->study_program }}</td>
                        <td class="p-3 text-center">
                            @php
                                $badges = [
                                    'Menunggu Verifikasi' => 'bg-warning-soft text-fg-warning border-border-warning-subtle',
                                    'Sedang Diproses' => 'bg-info-soft text-fg-info border-border-info-subtle',
                                    'Disetujui' => 'bg-success-soft text-fg-success-strong border-border-success-subtle',
                                    'Ditolak' => 'bg-danger-soft text-fg-danger-strong border-border-danger-subtle',
                                ];
                                $badgeStyle = $badges[$sub->current_status] ?? 'bg-neutral-primary-soft text-fg-body-subtle';
                            @endphp
                            <span class="inline-block px-2.5 py-0.5 rounded-full border text-[10px] font-bold uppercase tracking-wider {{ $badgeStyle }}">
                                {{ $sub->current_status }}
                            </span>
                        </td>
                        <td class="p-3 text-center text-fg-body-subtle">{{ $sub->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-fg-body-subtle italic">Tidak ada data permohonan yang sesuai dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = @json($monthlyStats);
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month.substring(0, 3)), // Jan, Feb, Mar...
                datasets: [{
                    label: 'Jumlah Permohonan',
                    data: monthlyData.map(d => d.count),
                    backgroundColor: 'rgba(30, 58, 138, 0.85)', // Navy Blue (#1E3A8A)
                    borderColor: 'rgb(30, 58, 138)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Data for Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($statusStats);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#F59E0B', // Amber
                        '#0284C7', // Info / Blue
                        '#16A34A', // Success / Green
                        '#DC2626'  // Danger / Red
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush

@endsection
