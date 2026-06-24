@extends('layouts.public')

@section('content')

<!-- ── HERO ── -->
<section class="relative section-padding bg-neutral-primary overflow-hidden pt-[140px] border-b border-border-default-subtle">
    <!-- Modern Gradient Background -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -right-[5%] w-[600px] h-[600px] bg-brand-soft/40 rounded-full blur-[100px] animate-float opacity-70"></div>
        <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] bg-brand-alt-soft/40 rounded-full blur-[80px] animate-float opacity-70" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 container-standard flex flex-col lg:flex-row items-center gap-[64px]">
        <!-- Text Content -->
        <div class="flex-1 text-center lg:text-left animate-fade-up">
            <div class="inline-flex items-center gap-2 bg-brand-softer border border-border-brand-subtle rounded-full px-[16px] py-[8px] mb-[32px] shadow-sm">
                <div class="w-[8px] h-[8px] bg-brand rounded-full animate-pulse"></div>
                <span class="text-brand-strong text-[13px] font-bold tracking-widest uppercase">{{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}</span>
            </div>

            <h1 class="text-hero text-fg-heading mx-auto lg:mx-0 max-w-[800px]">
                Layanan Izin Riset & <br class="hidden md:block" />
                <span class="text-brand">
                    Penelitian Elektronik
                </span>
            </h1>

            <p class="text-lead text-fg-body mx-auto lg:mx-0 mb-[48px] opacity-90 max-w-[600px]">
                Akses layanan pengajuan izin penelitian yang resmi, terpadu, dan transparan. Wujudkan birokrasi peradilan yang <strong class="font-bold text-fg-heading">Cepat, Mudah, dan Akuntabel</strong>.
            </p>

            <div class="flex flex-col sm:flex-row gap-[16px] justify-center lg:justify-start items-center">
                <a href="/register-permit" class="btn-brand btn-lg shadow-md hover:shadow-lg">
                    Ajukan Penelitian
                    <i data-lucide="file-plus" class="w-[20px] h-[20px] ml-1"></i>
                </a>
                <a href="/track" class="btn-secondary btn-lg">
                    Lacak Permohonan
                    <i data-lucide="search" class="w-[20px] h-[20px] ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Illustration / Stats Widget -->
        <div class="hidden lg:block flex-1 animate-fade-in relative" style="animation-delay: 0.3s;">
            <div class="relative w-full max-w-[500px] mx-auto">
                <div class="absolute inset-0 bg-gradient-to-tr from-brand to-brand-medium rounded-[32px] transform rotate-3 scale-105 opacity-10"></div>
                
                <div class="relative bg-white rounded-[24px] shadow-xl border border-border-default p-[32px]">
                    <div class="flex items-center justify-between mb-[24px] pb-[16px] border-b border-border-default-subtle">
                        <div class="flex items-center gap-3">
                            <div class="w-[40px] h-[40px] bg-brand-softer rounded-full flex items-center justify-center">
                                <i data-lucide="activity" class="w-[20px] h-[20px] text-brand"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-fg-heading text-[16px]">Live Stats</h3>
                                <p class="text-[12px] text-fg-body-subtle">Total Permohonan E-Riset</p>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-success-soft text-success-strong rounded-full text-[12px] font-bold">Real-time</div>
                    </div>

                    <div class="grid grid-cols-2 gap-[16px]">
                        <div class="bg-neutral-primary-soft p-[16px] rounded-base border border-border-default-subtle">
                            <p class="text-[13px] text-fg-body-subtle font-medium mb-[8px]">Total Pengajuan</p>
                            <p x-data="{ count: 0, target: {{ $stats['total'] ?? 0 }} }" x-init="let i = 0; let interval = setInterval(() => { if(i < target) { count = ++i; } else { clearInterval(interval); } }, 20);" class="font-heading font-bold text-[32px] text-fg-heading" x-text="count">0</p>
                        </div>
                        <div class="bg-success-soft/50 p-[16px] rounded-base border border-border-success-subtle">
                            <p class="text-[13px] text-fg-body-subtle font-medium mb-[8px]">Disetujui</p>
                            <p x-data="{ count: 0, target: {{ $stats['approved'] ?? 0 }} }" x-init="let i = 0; let interval = setInterval(() => { if(i < target) { count = ++i; } else { clearInterval(interval); } }, 20);" class="font-heading font-bold text-[32px] text-success-strong" x-text="count">0</p>
                        </div>
                        <div class="bg-warning-soft/50 p-[16px] rounded-base border border-border-warning-subtle col-span-2 flex items-center justify-between">
                            <div>
                                <p class="text-[13px] text-fg-body-subtle font-medium mb-[4px]">Dalam Proses</p>
                                <p x-data="{ count: 0, target: {{ $stats['processing'] ?? 0 }} }" x-init="let i = 0; let interval = setInterval(() => { if(i < target) { count = ++i; } else { clearInterval(interval); } }, 20);" class="font-heading font-bold text-[24px] text-warning-strong" x-text="count">0</p>
                            </div>
                            <div class="w-[48px] h-[48px] bg-white rounded-full flex items-center justify-center shadow-sm">
                                <i data-lucide="clock" class="w-[20px] h-[20px] text-warning-strong"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Badge -->
                <div class="absolute -bottom-[20px] -left-[20px] bg-white px-[20px] py-[16px] rounded-[16px] shadow-lg border border-border-default animate-float flex items-center gap-[12px]" style="animation-delay: 1.5s;">
                    <div class="w-[12px] h-[12px] bg-success rounded-full border-2 border-white shadow-sm"></div>
                    <span class="font-bold text-[14px] text-fg-heading">Sistem Aktif 24/7</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── ALUR PERMOHONAN ── -->
<section id="alur" class="section-padding bg-white border-b border-border-default-subtle relative overflow-hidden">
    <div class="container-standard">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand font-bold uppercase tracking-widest text-[12px] mb-[8px] block">Prosedur Layanan</span>
            <h2 class="text-h2 text-fg-heading mb-[16px]">Alur Permohonan Riset</h2>
            <p class="text-lead text-fg-body max-w-2xl mx-auto opacity-90">
                Ikuti 4 langkah mudah berikut untuk mendapatkan izin penelitian secara elektronik tanpa harus datang ke kantor Pengadilan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[24px]">
            <!-- Step 1 -->
            <div class="card-interactive p-[32px] text-center relative group">
                <div class="w-[64px] h-[64px] mx-auto rounded-[16px] bg-brand-softer flex items-center justify-center mb-[24px] group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="edit-3" class="w-[32px] h-[32px] text-brand"></i>
                </div>
                <div class="absolute top-[24px] right-[24px] text-[48px] font-heading font-black text-neutral-tertiary-medium opacity-30 select-none">1</div>
                <h3 class="font-heading font-bold text-[18px] text-fg-heading mb-[12px]">Isi Data Diri</h3>
                <p class="text-[14px] text-fg-body leading-[1.6]">Lengkapi formulir pendaftaran dengan identitas pemohon dan rincian penelitian secara akurat.</p>
            </div>
            
            <!-- Step 2 -->
            <div class="card-interactive p-[32px] text-center relative group">
                <div class="w-[64px] h-[64px] mx-auto rounded-[16px] bg-brand-alt-soft flex items-center justify-center mb-[24px] group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="upload-cloud" class="w-[32px] h-[32px] text-brand-alt-strong"></i>
                </div>
                <div class="absolute top-[24px] right-[24px] text-[48px] font-heading font-black text-neutral-tertiary-medium opacity-30 select-none">2</div>
                <h3 class="font-heading font-bold text-[18px] text-fg-heading mb-[12px]">Upload Dokumen</h3>
                <p class="text-[14px] text-fg-body leading-[1.6]">Unggah berkas persyaratan wajib seperti Surat Pengantar Kampus dan Proposal Riset dalam format PDF.</p>
            </div>

            <!-- Step 3 -->
            <div class="card-interactive p-[32px] text-center relative group">
                <div class="w-[64px] h-[64px] mx-auto rounded-[16px] bg-warning-soft flex items-center justify-center mb-[24px] group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="search" class="w-[32px] h-[32px] text-warning-strong"></i>
                </div>
                <div class="absolute top-[24px] right-[24px] text-[48px] font-heading font-black text-neutral-tertiary-medium opacity-30 select-none">3</div>
                <h3 class="font-heading font-bold text-[18px] text-fg-heading mb-[12px]">Verifikasi Petugas</h3>
                <p class="text-[14px] text-fg-body leading-[1.6]">Admin pengadilan akan memverifikasi kelengkapan berkas Anda. Pantau status melalui fitur Lacak.</p>
            </div>

            <!-- Step 4 -->
            <div class="card-interactive p-[32px] text-center relative group">
                <div class="w-[64px] h-[64px] mx-auto rounded-[16px] bg-success-soft flex items-center justify-center mb-[24px] group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="check-circle" class="w-[32px] h-[32px] text-success-strong"></i>
                </div>
                <div class="absolute top-[24px] right-[24px] text-[48px] font-heading font-black text-neutral-tertiary-medium opacity-30 select-none">4</div>
                <h3 class="font-heading font-bold text-[18px] text-fg-heading mb-[12px]">Surat Terbit</h3>
                <p class="text-[14px] text-fg-body leading-[1.6]">Jika disetujui, Surat Izin Penelitian elektronik ber-TTD sah dapat diunduh langsung dari sistem.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── PERSYARATAN ── -->
<section id="persyaratan" class="section-padding bg-neutral-primary-soft border-b border-border-default-subtle">
    <div class="container-standard">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand font-bold uppercase tracking-widest text-[12px] mb-[8px] block">Dokumen Wajib</span>
            <h2 class="text-h2 text-fg-heading mb-[16px]">Persyaratan Administrasi</h2>
            <p class="text-lead text-fg-body max-w-2xl mx-auto opacity-90">
                Pastikan Anda telah menyiapkan dokumen digital (PDF) berikut sebelum memulai proses pengajuan izin.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">
            @forelse($requirements as $req)
                <div class="card-static p-[32px] border-l-[4px] {{ $req->is_required ? 'border-l-brand' : 'border-l-neutral-tertiary-medium' }}">
                    <div class="flex items-start gap-[16px]">
                        <div class="w-[40px] h-[40px] rounded-[10px] bg-brand-softer text-brand flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-[20px] h-[20px]"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-heading font-bold text-fg-heading mb-[8px]">{{ $req->name }}</h3>
                            <p class="text-[14px] text-fg-body-subtle mb-[16px] leading-[1.5]">{{ $req->description }}</p>
                            
                            @if($req->is_required)
                                <span class="badge-danger">Wajib Dilampirkan</span>
                            @else
                                <span class="badge-neutral">Opsional</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-fg-body-subtle p-[40px] bg-white rounded-base border border-dashed border-border-default-strong">Belum ada data persyaratan administrasi yang dikonfigurasi.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── FAQ (Accordion Component) ── -->
<section id="faq" class="section-padding bg-white border-b border-border-default-subtle">
    <div class="container-standard max-w-[800px]">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand font-bold uppercase tracking-widest text-[12px] mb-[8px] block">Pusat Bantuan</span>
            <h2 class="text-h2 text-fg-heading mb-[16px]">Pertanyaan Seputar E-Riset</h2>
            <p class="text-lead text-fg-body opacity-90">Kumpulan pertanyaan yang sering diajukan beserta panduan solusinya.</p>
        </div>

        <div class="flex flex-col gap-[16px]">
            @forelse($faqs as $index => $faq)
                <div x-data="{ open: false }" class="bg-neutral-primary-soft border border-border-default rounded-[16px] overflow-hidden transition-shadow duration-300" :class="{ 'shadow-md border-brand-subtle': open }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-[24px] py-[20px] text-left focus:outline-none">
                        <span class="text-[16px] font-heading font-bold text-fg-heading pr-[24px] leading-[1.4]" :class="{ 'text-brand': open }">{{ $faq->question }}</span>
                        <div class="w-[32px] h-[32px] rounded-full bg-white border border-border-default flex items-center justify-center shrink-0 transition-all duration-300" :class="{ 'bg-brand text-white border-brand': open }">
                            <i data-lucide="plus" x-show="!open" class="w-[16px] h-[16px]"></i>
                            <i data-lucide="minus" x-show="open" x-cloak class="w-[16px] h-[16px]"></i>
                        </div>
                    </button>
                    <div x-show="open" x-collapse x-cloak>
                        <div class="px-[24px] pb-[24px] pt-[4px] text-[15px] text-fg-body leading-[1.7] opacity-90">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-[32px] text-center text-fg-body-subtle bg-neutral-primary-soft rounded-[16px] border border-border-default border-dashed">Belum ada Data Tanya Jawab.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── CTA Banner ── -->
<section class="py-[100px] relative overflow-hidden bg-brand-strong text-center">
    <!-- Abstract pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#FFFFFF 1px, transparent 1px); background-size: 24px 24px;"></div>
    
    <div class="relative z-10 container-standard max-w-[800px] animate-fade-up">
        <div class="w-[64px] h-[64px] bg-brand-medium rounded-[16px] flex items-center justify-center mx-auto mb-[24px] shadow-lg">
            <i data-lucide="file-badge" class="w-[32px] h-[32px] text-white"></i>
        </div>
        <h2 class="font-heading font-bold text-[36px] text-white mb-[24px] leading-[1.2]">Siap Mengajukan Izin Penelitian?</h2>
        <p class="text-[18px] text-brand-softer opacity-90 mb-[48px] max-w-[600px] mx-auto leading-[1.6]">
            Wujudkan kemudahan riset dan penelitian Anda dengan sistem administrasi peradilan yang transparan, profesional, dan gratis.
        </p>
        <div class="flex flex-col sm:flex-row gap-[16px] justify-center items-center">
            <a href="/register-permit" class="btn-warning btn-lg shadow-lg flex items-center gap-2">
                Mulai Ajukan Sekarang 
                <i data-lucide="arrow-right" class="w-[20px] h-[20px]"></i>
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
