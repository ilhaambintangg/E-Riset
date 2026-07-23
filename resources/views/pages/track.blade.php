@extends('layouts.public')

@section('content')

@php
$statusConfig = [
    'Menunggu Verifikasi' => ['color' => 'text-warning-strong bg-warning-soft border-border-warning-subtle', 'icon' => 'clock', 'dot' => 'bg-warning'],
    'Sedang Diproses'     => ['color' => 'text-info-strong bg-info-soft border-border-info-subtle', 'icon' => 'loader-2', 'dot' => 'bg-info'],
    'Menentukan Jadwal Wawancara' => ['color' => 'text-info-strong bg-info-soft border-border-info-subtle', 'icon' => 'calendar', 'dot' => 'bg-info'],
    'Pembuatan Surat Keterangan Riset' => ['color' => 'text-success-strong bg-success-soft border-border-success-subtle', 'icon' => 'check-circle-2', 'dot' => 'bg-success'],
    'Disetujui'           => ['color' => 'text-success-strong bg-success-soft border-border-success-subtle', 'icon' => 'check-circle-2', 'dot' => 'bg-success'],
    'Ditolak'             => ['color' => 'text-danger-strong bg-danger-soft border-border-danger-subtle', 'icon' => 'x-circle', 'dot' => 'bg-danger'],
];

$cfg = null;
$isPt = false;
$steps = [];
$currentStepIndex = -1;

if (isset($submission)) {
    $cfg = $statusConfig[$submission->current_status] ?? ['color' => 'text-fg-body bg-neutral-secondary-medium border-border-default-medium', 'icon' => 'clock', 'dot' => 'bg-neutral-tertiary'];
    $isPt = $submission->isPt();
    
    // Define stepper logic dynamically based on PT/PN
    $steps = $isPt
        ? ['Menunggu Verifikasi', 'Menentukan Jadwal Wawancara', 'Pembuatan Surat Keterangan Riset', 'Disetujui']
        : ['Menunggu Verifikasi', 'Sedang Diproses', 'Disetujui'];

    if ($submission->current_status === 'Ditolak') {
        $currentStepIndex = -1; // Ditolak is an exception state
    } else {
        $currentStepIndex = array_search($submission->current_status, $steps);
    }
}
@endphp

<div class="min-h-screen bg-transparent relative overflow-hidden flex flex-col pt-[120px] sm:pt-[140px] pb-[80px]">
    <!-- Abstract pattern background -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-20" style="background-image: radial-gradient(rgba(255, 255, 255, 0.3) 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>

    <div class="container-standard max-w-[800px] relative z-10">
        <!-- Header -->
        <div class="text-center mb-[48px] animate-fade-up">
            <div class="inline-flex items-center gap-[8px] bg-white/10 border border-white/20 px-[18px] py-[8px] rounded-lg mb-[16px] shadow-2xs">
                <i data-lucide="search" class="w-[16px] h-[16px] text-brand-alt"></i>
                <span class="text-white font-extrabold text-[12px] tracking-widest uppercase">E-RISET LACAK</span>
            </div>
            <h1 class="font-heading font-black text-h2 text-white mb-[12px]">Lacak Status Permohonan</h1>
            <p class="text-[16px] text-white/85 font-medium opacity-90">Masukkan nomor registrasi untuk melihat progress izin penelitian Anda.</p>
        </div>

        <!-- Search Form & Detective Mascot -->
        <div class="bg-white rounded-base shadow-md border-2 border-border-default p-[24px] sm:p-[40px] mb-[40px] animate-fade-up flex flex-col md:flex-row items-center gap-8 relative overflow-hidden" style="animation-delay: 0.1s;">
            <!-- Detective Mascot SVG -->
            <div class="shrink-0 bg-neutral-primary-medium border-2 border-border-default rounded-base p-4 flex flex-col items-center">
                <svg class="w-[110px] h-[110px] animate-risi-float" viewBox="0 0 100 100" fill="none">
                  <circle cx="42" cy="85" r="5" fill="#E76F51" />
                  <circle cx="58" cy="85" r="5" fill="#E76F51" />
                  <rect x="25" y="32" width="50" height="52" rx="25" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                  <rect x="34" y="52" width="32" height="26" rx="13" fill="#FFFDF6" stroke="#0A2240" stroke-width="2"/>
                  <path d="M25 48 C15 50, 12 60, 24 68" fill="#143A66" stroke="#0A2240" stroke-width="3" />
                  <path d="M75 52 C83 50, 85 62, 76 68" fill="#143A66" stroke="#0A2240" stroke-width="3" />
                  <!-- Magnifying Glass -->
                  <g transform="translate(72, 58) rotate(45)">
                     <rect x="0" y="0" width="4" height="15" fill="#F4A261" stroke="#0A2240" stroke-width="1.5"/>
                     <circle cx="2" cy="-4" r="8" fill="white" fill-opacity="0.2" stroke="#0A2240" stroke-width="2"/>
                  </g>
                  <g class="animate-head-bob">
                     <rect x="22" y="14" width="56" height="42" rx="21" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                     <!-- Detective Cap -->
                     <path d="M18 18 C25 6, 75 6, 82 18 Z" fill="#7F7457" stroke="#0A2240" stroke-width="2.5"/>
                     <path d="M15 18 L85 18" stroke="#0A2240" stroke-width="4" stroke-linecap="round"/>
                     <circle cx="50" cy="8" r="3" fill="#F4A261" stroke="#0A2240" stroke-width="1.5"/>
                     <circle cx="37" cy="32" r="11" fill="white" stroke="#F4A261" stroke-width="3"/>
                     <circle cx="63" cy="32" r="11" fill="white" stroke="#F4A261" stroke-width="3"/>
                     <path d="M48 32 L52 32" stroke="#F4A261" stroke-width="3"/>
                     <g class="animate-risi-blink">
                        <circle cx="37" cy="32" r="4.5" fill="#0A2240"/>
                        <circle cx="63" cy="32" r="4.5" fill="#0A2240"/>
                        <circle cx="35" cy="30" r="1.5" fill="white"/>
                        <circle cx="61" cy="30" r="1.5" fill="white"/>
                     </g>
                     <polygon points="50,35 45,42 55,42" fill="#E76F51" stroke="#0A2240" stroke-width="1.5"/>
                  </g>
                </svg>
                <span class="text-[11px] font-extrabold uppercase text-brand mt-2 bg-brand-softer border border-brand/20 px-2 py-0.5 rounded-lg">Detektif Risi</span>
            </div>

            <!-- Search Form inputs -->
            <form action="/track" method="GET" class="flex-1 w-full">
                <label class="font-extrabold text-[12px] text-fg-heading uppercase tracking-wider mb-[8px] block">
                    Masukkan Nomor Registrasi
                </label>
                <div class="flex flex-col sm:flex-row gap-[12px]">
                    <div class="relative flex-1 group">
                        <i data-lucide="hash" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[20px] h-[20px] text-fg-body-subtle group-focus-within:text-brand-alt transition-colors"></i>
                        <input
                            type="text"
                            name="registration_number"
                            value="{{ request('registration_number') }}"
                            class="input-standard pl-[48px] w-full font-mono uppercase text-[15px] placeholder:normal-case placeholder:font-sans placeholder:text-[14px]"
                            placeholder="Contoh: ERS-2025-00001"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        class="btn-brand h-[52px] px-[32px] rounded-default flex items-center justify-center gap-[10px] text-[15px] font-bold shadow-xs hover:shadow-sm"
                    >
                        <i data-lucide="search" class="w-[18px] h-[18px]"></i>
                        Cari
                    </button>
                </div>
                @if(isset($error))
                    <div class="flex items-start gap-[12px] mt-[20px] bg-danger-soft border-2 border-danger rounded-lg px-[16px] py-[12px] text-danger-strong text-[13px] font-bold animate-fade-in shadow-2xs">
                        <i data-lucide="alert-circle" class="w-[18px] h-[18px] mt-[1px] shrink-0 text-danger animate-bounce"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endif
            </form>
        </div>

        <!-- Result Card -->
        @if(isset($submission))
            <div class="bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
                <!-- Status Header -->
                <div class="p-[32px] sm:p-[40px] border-b border-slate-100 relative overflow-hidden text-white" style="background-color: #0A2240;">
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-[20px]">
                        <div>
                            <p class="text-[11px] text-slate-300 font-extrabold mb-[6px] uppercase tracking-widest leading-none">Nomor Registrasi Anda</p>
                            <p class="font-mono font-black text-[28px] sm:text-[34px] text-white tracking-tight leading-none">{{ $submission->registration_number }}</p>
                        </div>
                        <div class="flex items-center gap-[10px] px-[20px] py-[10px] rounded-xl bg-white font-extrabold text-[14px] shadow-sm border border-slate-100" style="color: #0A2240;">
                            <div class="w-[8px] h-[8px] rounded-full {{ $cfg['dot'] }} animate-ping"></div>
                            {{ $submission->current_status }}
                        </div>
                    </div>
                </div>

                <!-- Timeline Progress Stepper -->
                @if($submission->current_status !== 'Ditolak')
                <div class="px-[32px] sm:px-[40px] pt-[40px] pb-[44px] border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-heading font-black text-[16px] text-fg-heading mb-[24px] uppercase tracking-wider">Progress Permohonan</h3>
                    <div class="relative flex justify-between items-start w-full max-w-[560px] mx-auto mb-[8px]">
                        <!-- Connecting Line Background -->
                        <div class="absolute left-0 top-[24px] -translate-y-1/2 w-full h-[4px] bg-slate-200 rounded-full -z-10"></div>
                        <!-- Connecting Line Active -->
                        <div class="absolute left-0 top-[24px] -translate-y-1/2 h-[4px] bg-gradient-to-r from-brand to-brand-alt rounded-full -z-10 transition-all duration-700 ease-out" style="width: {{ count($steps) > 1 ? ($currentStepIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>

                        @foreach($steps as $index => $stepName)
                            <div class="flex flex-col items-center gap-[8px] relative text-center">
                                @if($index < $currentStepIndex)
                                    <!-- Completed Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-brand text-white flex items-center justify-center shadow-md border-[4px] border-white z-10 animate-fade-in">
                                        <i data-lucide="check" class="w-[20px] h-[20px]"></i>
                                    </div>
                                    <span class="text-[12px] font-bold text-slate-500 whitespace-nowrap">{{ $stepName }}</span>
                                @elseif($index === $currentStepIndex)
                                    <!-- Current Active Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-white text-brand border-[4px] border-brand flex items-center justify-center shadow-lg z-10">
                                        <i data-lucide="{{ $index === 0 ? 'clock' : ($index === 1 ? 'loader-2' : 'check-circle-2') }}" class="w-[20px] h-[20px] {{ $index === 1 ? 'animate-spin' : '' }}"></i>
                                    </div>
                                    <span class="text-[12px] font-black text-brand whitespace-nowrap">{{ $stepName }}</span>
                                @else
                                    <!-- Future Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-slate-100 text-slate-400 border-[4px] border-white flex items-center justify-center shadow-sm z-10">
                                        <span class="font-extrabold text-[14px]">{{ $index + 1 }}</span>
                                    </div>
                                    <span class="text-[12px] font-semibold text-slate-400 whitespace-nowrap">{{ $stepName }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Submission Detail -->
                <div class="p-[32px] sm:p-[40px] space-y-[32px]">
                    <div>
                        <h3 class="font-heading font-bold text-[18px] text-fg-heading flex items-center gap-[12px] mb-[24px]">
                            <div class="w-[36px] h-[36px] bg-brand-softer rounded-xl flex items-center justify-center border border-brand-subtle">
                                <i data-lucide="file-text" class="w-[18px] h-[18px] text-brand"></i>
                            </div>
                            Detail Permohonan
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px]">
                            <div class="bg-white rounded-xl p-[24px] border border-slate-100 shadow-soft hover:shadow-md transition-all duration-300">
                                <p class="text-[11px] text-slate-400 font-extrabold uppercase tracking-wider mb-[6px]">Nama Pemohon</p>
                                <p class="text-[16px] text-fg-heading font-bold">{{ $submission->name ?? '-' }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-[24px] border border-slate-100 shadow-soft hover:shadow-md transition-all duration-300">
                                <p class="text-[11px] text-slate-400 font-extrabold uppercase tracking-wider mb-[6px]">Institusi</p>
                                <p class="text-[16px] text-fg-heading font-bold">{{ $submission->university ?? '-' }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-[24px] border border-slate-100 shadow-soft hover:shadow-md transition-all duration-300 sm:col-span-2">
                                <p class="text-[11px] text-slate-400 font-extrabold uppercase tracking-wider mb-[6px]">Judul Penelitian</p>
                                <p class="text-[16px] text-fg-heading font-semibold leading-[1.6]">{{ $submission->title ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Wawancara (Jika ada) -->
                    @if($submission->interview_date && $submission->current_status === 'Menentukan Jadwal Wawancara')
                        <div class="bg-blue-50/80 border border-blue-100 rounded-xl p-[32px] text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-[24px] shadow-sm mb-4 animate-fade-in">
                            <div class="flex-1">
                                <h3 class="text-[18px] font-bold text-blue-800 mb-[8px] flex items-center justify-center sm:justify-start gap-[10px]">
                                    <i data-lucide="calendar" class="w-[24px] h-[24px]"></i> Jadwal Wawancara Dijadwalkan
                                </h3>
                                <p class="text-[14px] text-blue-700 font-medium leading-[1.6]">
                                    Wawancara penelitian Anda telah dijadwalkan pada hari <strong class="text-blue-900">{{ $submission->interview_date->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</strong>. Silakan hadir tepat waktu di lokasi penelitian.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Surat Izin / Keterangan (Jika Disetujui / Pembuatan Surat Keterangan Riset) -->
                    @if($submission->current_status === 'Disetujui' || ($submission->isPt() && $submission->current_status === 'Pembuatan Surat Keterangan Riset'))
                        <div class="bg-emerald-50/80 border border-emerald-100 rounded-xl p-[32px] text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-[24px] shadow-sm">
                            <div>
                                <h3 class="text-[18px] font-bold text-emerald-800 mb-[8px] flex items-center justify-center sm:justify-start gap-[10px]">
                                    <i data-lucide="check-circle-2" class="w-[24px] h-[24px]"></i> Surat Resmi Tersedia
                                </h3>
                                <p class="text-[14px] text-emerald-700 font-medium leading-[1.6]">
                                    Permohonan Anda telah disetujui / diproses. Unduh dokumen surat resmi elektronik Anda sekarang.
                                </p>
                            </div>
                            <a href="/api/public/submissions/{{ $submission->registration_number }}/download-permit" target="_blank"
                               class="btn-success btn-lg shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                                <i data-lucide="download" class="w-[20px] h-[20px]"></i>
                                Unduh Dokumen
                            </a>
                        </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($submission->admin_notes)
                        <div class="bg-amber-50/80 border border-amber-100 rounded-xl p-[24px] flex gap-[16px] shadow-sm">
                            <i data-lucide="info" class="w-[24px] h-[24px] text-amber-600 shrink-0"></i>
                            <div>
                                <p class="font-bold mb-[4px] uppercase tracking-wider text-[11px] text-amber-800">Catatan dari Petugas</p>
                                <p class="text-[15px] text-slate-700 leading-[1.6]">{{ $submission->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status History -->
                    @if($submission->statusLogs && $submission->statusLogs->count() > 0)
                        <div x-data="{ open: false }" class="pt-[32px] border-t border-slate-100">
                            <button @click="open = !open" class="flex items-center justify-between w-full p-[20px] rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/50 transition-colors focus:outline-none">
                                <span class="text-[13px] font-bold text-fg-heading uppercase tracking-wider">Riwayat Status</span>
                                <div class="w-[32px] h-[32px] rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-2xs">
                                    <i x-show="!open" data-lucide="chevron-down" class="w-[16px] h-[16px] text-fg-body"></i>
                                    <i x-show="open" x-cloak data-lucide="chevron-up" class="w-[16px] h-[16px] text-fg-body"></i>
                                </div>
                            </button>
                            <div x-show="open" x-collapse x-cloak class="mt-[32px] px-[20px]">
                                <div class="space-y-[32px] relative before:absolute before:inset-0 before:ml-[11px] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-[2px] before:bg-slate-200">
                                    @foreach($submission->statusLogs as $log)
                                        @php
                                            $logCfg = $statusConfig[$log->status] ?? $statusConfig['Menunggu Verifikasi'];
                                        @endphp
                                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                            <div class="flex items-center justify-center w-[24px] h-[24px] rounded-full border-[4px] border-white {{ $logCfg['dot'] }} shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10"></div>
                                            <div class="w-[calc(100%-32px)] md:w-[calc(50%-40px)] bg-slate-50 p-[20px] rounded-xl border border-slate-100 shadow-2xs">
                                                <div class="flex items-center justify-between mb-[6px] gap-4">
                                                    <p class="font-bold text-[14px] text-fg-heading">{{ $log->status }}</p>
                                                    <span class="text-[11px] text-slate-400 font-medium shrink-0">{{ $log->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                </div>
                                                @if($log->notes)
                                                    <p class="text-[13px] text-slate-600 mt-[8px] leading-[1.6] bg-white p-3 rounded-lg border border-slate-100">{{ $log->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Info Box -->
        <div class="mt-[48px] bg-white rounded-xl p-[32px] sm:p-[40px] text-[15px] text-slate-800 border-2 border-border-default shadow-md animate-fade-up" style="animation-delay: 0.3s;">
            <div class="font-heading font-bold mb-[20px] flex items-center gap-[12px] text-brand text-[18px]">
                <div class="w-[36px] h-[36px] bg-brand-softer rounded-xl flex items-center justify-center border border-brand-subtle">
                    <i data-lucide="info" class="w-[18px] h-[18px] text-brand"></i>
                </div>
                <span>Informasi Layanan</span>
            </div>
            <div class="pl-[44px]">
                <ul class="list-disc space-y-[12px] leading-[1.6]">
                    <li class="text-slate-700 font-medium">Nomor registrasi dikirimkan melalui halaman konfirmasi dan email saat Anda selesai mengisi formulir.</li>
                    <li class="text-slate-700 font-medium">Format registrasi: <code class="font-mono bg-white border border-slate-100 px-[8px] py-[2px] rounded-[6px] text-fg-heading shadow-xs text-[13px] font-bold">ERS-YYYY-XXXXX</code></li>
                    <li class="text-slate-700 font-medium">Proses verifikasi dan penerbitan izin memakan waktu estimasi <strong>3–5 hari kerja</strong>.</li>
                    <li class="text-slate-700 font-medium">Jika permohonan Anda <span class="text-danger font-bold">Ditolak</span>, perbaiki dokumen sesuai catatan petugas dan ajukan permohonan baru.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@endsection
