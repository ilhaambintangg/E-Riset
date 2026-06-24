@extends('layouts.public')

@section('content')

@php
$statusConfig = [
    'Menunggu Verifikasi' => ['color' => 'text-warning-strong bg-warning-soft border-border-warning-subtle', 'icon' => 'clock', 'dot' => 'bg-warning'],
    'Sedang Diproses'     => ['color' => 'text-info-strong bg-info-soft border-border-info-subtle', 'icon' => 'loader-2', 'dot' => 'bg-info'],
    'Disetujui'           => ['color' => 'text-success-strong bg-success-soft border-border-success-subtle', 'icon' => 'check-circle-2', 'dot' => 'bg-success'],
    'Ditolak'             => ['color' => 'text-danger-strong bg-danger-soft border-border-danger-subtle', 'icon' => 'x-circle', 'dot' => 'bg-danger'],
];

$cfg = null;
if (isset($submission)) {
    $cfg = $statusConfig[$submission->current_status] ?? ['color' => 'text-fg-body bg-neutral-secondary-medium border-border-default-medium', 'icon' => 'clock', 'dot' => 'bg-neutral-tertiary'];
}

// Define stepper logic
$steps = [
    'Menunggu Verifikasi',
    'Sedang Diproses',
    'Disetujui'
];

$currentStepIndex = -1;
if (isset($submission)) {
    if ($submission->current_status === 'Ditolak') {
        $currentStepIndex = -1; // Ditolak is an exception state
    } else {
        $currentStepIndex = array_search($submission->current_status, $steps);
    }
}
@endphp

<div class="min-h-screen bg-neutral-primary relative overflow-hidden flex flex-col pt-[100px] sm:pt-[120px] pb-[80px]">
    <!-- Abstract pattern -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-50" style="background-image: radial-gradient(var(--color-border-default) 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="container-standard max-w-[800px] relative z-10">
        <!-- Header -->
        <div class="text-center mb-[48px] animate-fade-up">
            <div class="inline-flex items-center gap-[8px] bg-brand-softer border border-brand-subtle px-[16px] py-[8px] rounded-full mb-[16px] shadow-sm">
                <i data-lucide="search" class="w-[16px] h-[16px] text-brand"></i>
                <span class="text-brand font-bold text-[12px] tracking-widest uppercase">E-RISET TRACKER</span>
            </div>
            <h1 class="font-heading font-bold text-h2 text-fg-heading mb-[12px]">Lacak Status Permohonan</h1>
            <p class="text-[16px] text-fg-body opacity-90">Masukkan nomor registrasi yang Anda terima setelah pengajuan</p>
        </div>

        <!-- Search Form -->
        <form action="/track" method="GET" class="bg-white rounded-[24px] shadow-lg border border-border-default p-[32px] sm:p-[48px] mb-[40px] animate-fade-up" style="animation-delay: 0.1s;">
            <label class="font-bold text-[14px] text-fg-heading uppercase tracking-wider mb-[12px] block">
                Nomor Registrasi
            </label>
            <div class="flex flex-col sm:flex-row gap-[16px]">
                <div class="relative flex-1 group">
                    <i data-lucide="hash" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[20px] h-[20px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                    <input
                        type="text"
                        name="registration_number"
                        value="{{ request('registration_number') }}"
                        class="input-standard pl-[48px] w-full font-mono uppercase text-[16px] placeholder:normal-case placeholder:font-sans placeholder:text-[15px]"
                        placeholder="Contoh: ERS-2025-00001"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="btn-brand h-[56px] px-[40px] flex items-center justify-center gap-[12px] text-[16px] shadow-md hover:shadow-lg transition-all"
                >
                    <i data-lucide="search" class="w-[20px] h-[20px]"></i>
                    Lacak
                </button>
            </div>
            @if(isset($error))
                <div class="flex items-start gap-[12px] mt-[24px] bg-danger-soft border border-border-danger-subtle rounded-[12px] px-[20px] py-[16px] text-danger-strong text-[14px] font-medium animate-fade-in shadow-sm">
                    <i data-lucide="alert-circle" class="w-[20px] h-[20px] mt-[2px] shrink-0 text-danger"></i>
                    {{ $error }}
                </div>
            @endif
        </form>

        <!-- Result -->
        @if(isset($submission))
            <div class="bg-white rounded-[24px] shadow-lg border border-border-default overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
                <!-- Status Header -->
                <div class="p-[32px] sm:p-[40px] border-b border-border-default relative overflow-hidden bg-neutral-primary-soft">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-multiply"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-[24px]">
                        <div>
                            <p class="text-[12px] text-fg-body-subtle font-bold mb-[8px] uppercase tracking-widest">Nomor Registrasi</p>
                            <p class="font-mono font-black text-[32px] text-fg-heading tracking-tight leading-none">{{ $submission->registration_number }}</p>
                        </div>
                        <div class="flex items-center gap-[12px] px-[24px] py-[12px] rounded-full border border-[2px] font-bold text-[16px] shadow-sm {{ $cfg['color'] }}">
                            <div class="w-[12px] h-[12px] rounded-full {{ $cfg['dot'] }} animate-pulse"></div>
                            {{ $submission->current_status }}
                        </div>
                    </div>
                </div>

                <!-- Stepper Timeline -->
                @if($submission->current_status !== 'Ditolak')
                <div class="px-[32px] sm:px-[40px] pt-[40px] pb-[16px] border-b border-border-default">
                    <h3 class="font-heading font-bold text-[18px] text-fg-heading mb-[32px]">Progress Permohonan</h3>
                    <div class="relative flex justify-between items-center w-full max-w-[600px] mx-auto">
                        <!-- Connecting Line Background -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-[4px] bg-neutral-secondary-strong rounded-full -z-10"></div>
                        <!-- Connecting Line Active -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-[4px] bg-brand rounded-full -z-10 transition-all duration-700 ease-out" style="width: {{ $currentStepIndex === 0 ? '0%' : ($currentStepIndex === 1 ? '50%' : '100%') }}"></div>

                        @foreach($steps as $index => $stepName)
                            <div class="flex flex-col items-center gap-[12px] bg-white relative z-0">
                                @if($index < $currentStepIndex)
                                    <!-- Completed Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-brand text-white flex items-center justify-center shadow-md border-[4px] border-white">
                                        <i data-lucide="check" class="w-[24px] h-[24px]"></i>
                                    </div>
                                    <span class="text-[12px] font-bold text-fg-heading absolute -bottom-[24px] whitespace-nowrap">{{ $stepName }}</span>
                                @elseif($index === $currentStepIndex)
                                    <!-- Current Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-white text-brand border-[4px] border-brand flex items-center justify-center shadow-md animate-pulse">
                                        <i data-lucide="{{ $index === 0 ? 'clock' : ($index === 1 ? 'loader-2' : 'check-circle-2') }}" class="w-[24px] h-[24px] {{ $index === 1 ? 'animate-spin' : '' }}"></i>
                                    </div>
                                    <span class="text-[12px] font-bold text-brand absolute -bottom-[24px] whitespace-nowrap">{{ $stepName }}</span>
                                @else
                                    <!-- Future Step -->
                                    <div class="w-[48px] h-[48px] rounded-full bg-neutral-primary-soft text-neutral-tertiary-strong border-[4px] border-white flex items-center justify-center shadow-sm">
                                        <span class="font-bold text-[16px]">{{ $index + 1 }}</span>
                                    </div>
                                    <span class="text-[12px] font-bold text-fg-body-subtle absolute -bottom-[24px] whitespace-nowrap">{{ $stepName }}</span>
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
                            <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                                <i data-lucide="file-text" class="w-[16px] h-[16px] text-brand"></i>
                            </div>
                            Detail Permohonan
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                            <div class="bg-neutral-primary-soft rounded-[16px] p-[24px] border border-border-default">
                                <p class="text-[12px] text-fg-body-subtle font-bold uppercase tracking-wider mb-[4px]">Nama Pemohon</p>
                                <p class="text-[15px] text-fg-heading font-bold">{{ $submission->name ?? '-' }}</p>
                            </div>
                            <div class="bg-neutral-primary-soft rounded-[16px] p-[24px] border border-border-default">
                                <p class="text-[12px] text-fg-body-subtle font-bold uppercase tracking-wider mb-[4px]">Institusi</p>
                                <p class="text-[15px] text-fg-heading font-bold">{{ $submission->university ?? '-' }}</p>
                            </div>
                            <div class="bg-neutral-primary-soft rounded-[16px] p-[24px] border border-border-default sm:col-span-2">
                                <p class="text-[12px] text-fg-body-subtle font-bold uppercase tracking-wider mb-[4px]">Judul Penelitian</p>
                                <p class="text-[15px] text-fg-heading font-medium leading-[1.6]">{{ $submission->title ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Izin (Jika Disetujui) -->
                    @if($submission->current_status === 'Disetujui')
                        <div class="bg-success-soft border border-border-success-subtle rounded-[16px] p-[32px] text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-[24px] shadow-sm">
                            <div>
                                <h3 class="text-[18px] font-bold text-success-strong mb-[8px] flex items-center justify-center sm:justify-start gap-[8px]">
                                    <i data-lucide="check-circle-2" class="w-[24px] h-[24px]"></i> Surat Izin Tersedia
                                </h3>
                                <p class="text-[14px] text-success-strong opacity-90 font-medium leading-[1.6]">
                                    Permohonan izin Anda telah disetujui. Unduh surat izin elektronik resmi ber-TTD sekarang.
                                </p>
                            </div>
                            <a href="/api/public/submissions/{{ $submission->registration_number }}/download-permit" target="_blank"
                               class="btn-success btn-lg shadow-md hover:shadow-lg">
                                <i data-lucide="download" class="w-[20px] h-[20px]"></i>
                                Unduh (PDF)
                            </a>
                        </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($submission->admin_notes)
                        <div class="bg-warning-soft border border-border-warning-subtle rounded-[16px] p-[24px] flex gap-[16px]">
                            <i data-lucide="info" class="w-[24px] h-[24px] text-warning-strong shrink-0"></i>
                            <div>
                                <p class="font-bold mb-[4px] uppercase tracking-wider text-[12px] text-warning-strong">Catatan dari Petugas</p>
                                <p class="text-[15px] text-fg-heading leading-[1.6]">{{ $submission->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status History -->
                    @if($submission->statusLogs && $submission->statusLogs->count() > 0)
                        <div x-data="{ open: false }" class="pt-[24px] border-t border-border-default">
                            <button @click="open = !open" class="flex items-center justify-between w-full p-[16px] rounded-[12px] bg-neutral-primary hover:bg-neutral-primary-soft transition-colors focus:outline-none">
                                <span class="text-[14px] font-bold text-fg-heading uppercase tracking-wider">Riwayat Status</span>
                                <div class="w-[32px] h-[32px] rounded-full bg-white border border-border-default flex items-center justify-center">
                                    <i x-show="!open" data-lucide="chevron-down" class="w-[16px] h-[16px] text-fg-body"></i>
                                    <i x-show="open" x-cloak data-lucide="chevron-up" class="w-[16px] h-[16px] text-fg-body"></i>
                                </div>
                            </button>
                            <div x-show="open" x-collapse x-cloak class="mt-[24px] px-[16px]">
                                <div class="space-y-[24px] relative before:absolute before:inset-0 before:ml-[11px] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-[2px] before:bg-border-default">
                                    @foreach($submission->statusLogs as $log)
                                        @php
                                            $logCfg = $statusConfig[$log->status] ?? $statusConfig['Menunggu Verifikasi'];
                                        @endphp
                                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                            <div class="flex items-center justify-center w-[24px] h-[24px] rounded-full border-[4px] border-white {{ $logCfg['dot'] }} shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10"></div>
                                            <div class="w-[calc(100%-32px)] md:w-[calc(50%-40px)] bg-neutral-primary-soft p-[16px] rounded-[12px] border border-border-default shadow-xs">
                                                <div class="flex items-center justify-between mb-[4px]">
                                                    <p class="font-bold text-[14px] text-fg-heading">{{ $log->status }}</p>
                                                    <span class="text-[11px] text-fg-body-subtle font-medium">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                                                </div>
                                                @if($log->notes)
                                                    <p class="text-[13px] text-fg-body mt-[8px] leading-[1.5]">{{ $log->notes }}</p>
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
        <div class="mt-[48px] bg-neutral-primary-soft rounded-[24px] p-[32px] sm:p-[40px] text-[15px] text-fg-body border border-border-default shadow-sm animate-fade-up" style="animation-delay: 0.3s;">
            <p class="font-heading font-bold mb-[16px] flex items-center gap-[12px] text-fg-heading text-[18px]">
                <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                    <i data-lucide="info" class="w-[16px] h-[16px] text-brand"></i>
                </div>
                Informasi Layanan
            </p>
            <div class="pl-[44px]">
                <ul class="list-disc space-y-[12px] opacity-90 leading-[1.6]">
                    <li>Nomor registrasi dikirimkan melalui halaman konfirmasi dan email saat Anda selesai mengisi formulir.</li>
                    <li>Format registrasi: <code class="font-mono bg-white border border-border-default px-[8px] py-[2px] rounded-[6px] text-fg-heading shadow-xs text-[13px] font-bold">ERS-YYYY-XXXXX</code></li>
                    <li>Proses verifikasi dan penerbitan izin memakan waktu estimasi <strong>3–5 hari kerja</strong>.</li>
                    <li>Jika permohonan Anda <span class="text-danger font-bold">Ditolak</span>, perbaiki dokumen sesuai catatan petugas dan ajukan permohonan baru.</li>
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
