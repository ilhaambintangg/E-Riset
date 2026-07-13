@extends('layouts.public')

@section('content')

<!-- ── HERO SECTION ── -->
<section id="tentang" class="relative section-padding overflow-hidden pt-[160px] pb-[100px]">
    <!-- Modern Playful Grid & Shapes -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -right-[5%] w-[500px] h-[500px] bg-brand-alt-soft/30 rounded-full blur-[80px] animate-risi-float"></div>
        <div class="absolute top-[30%] -left-[10%] w-[400px] h-[400px] bg-brand-soft/20 rounded-full blur-[80px] animate-risi-float" style="animation-delay: 2.5s;"></div>
        <!-- Decorative grid -->
        <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(rgba(255,255,255,0.4) 1.5px, transparent 1.5px); background-size: 32px 32px;"></div>
    </div>

    <div class="relative z-10 container-standard flex flex-col lg:flex-row items-center gap-[64px]">
        <!-- Text Content -->
        <div class="flex-1 text-center lg:text-left animate-fade-up">
            <div class="inline-flex items-center gap-2 bg-white/10 border-2 border-white/20 rounded-full px-[18px] py-[8px] mb-[32px] shadow-2xs">
                <div class="w-[10px] h-[10px] bg-brand-alt rounded-full animate-ping"></div>
                <span class="text-white text-[12px] font-extrabold tracking-widest uppercase">{{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}</span>
            </div>

            <h1 class="text-h1 text-white mx-auto lg:mx-0 max-w-[800px] font-black leading-tight">
                Layanan Izin Riset & <br class="hidden md:block" />
                <span class="text-brand-alt decoration-brand-alt decoration-wavy underline underline-offset-8">
                    Penelitian Elektronik
                </span>
            </h1>

            <p class="text-lead text-white mx-auto lg:mx-0 mb-[40px] max-w-[620px] font-semibold drop-shadow-[0_1.5px_2px_rgba(0,0,0,0.4)]">
                Selamat datang di <span class="text-brand-alt font-black">E-Riset</span>. Kami membantu mempermudah pengajuan izin penelitian secara online, resmi, dan transparan bersama <span class="text-brand-alt font-black">Si Risi</span>, asisten pintar riset Anda!
            </p>

            <div class="flex flex-col sm:flex-row gap-[16px] justify-center lg:justify-start items-center">
                <a href="/register-permit" class="btn-brand btn-lg bg-brand-alt border-brand-alt hover:bg-brand-alt-strong hover:border-brand-alt-strong shadow-md hover:shadow-lg rounded-full">
                    Ajukan Penelitian
                    <i data-lucide="file-plus" class="w-[22px] h-[22px]"></i>
                </a>
                <a href="/track" class="btn-secondary btn-lg rounded-full">
                    Lacak Permohonan
                    <i data-lucide="search" class="w-[22px] h-[22px]"></i>
                </a>
            </div>
        </div>

        <!-- Interactive Animated Mascot & Stats -->
        <div class="flex-1 w-full max-w-[500px] lg:max-w-none animate-fade-in" style="animation-delay: 0.3s;">
            <div class="relative w-full max-w-[460px] mx-auto">
                <!-- Mascot Container (Si Risi Owl floating) -->
                <div class="relative bg-white border-[4px] border-brand rounded-base p-[32px] shadow-lg flex flex-col items-center">
                    
                    <!-- SVG Si Risi Mascot -->
                    <div class="mb-4 relative">
                        <!-- Glasses Light reflection overlay -->
                        <div class="absolute top-[25px] left-[35px] w-6 h-6 bg-white/20 rounded-full pointer-events-none"></div>
                        <svg class="w-[180px] h-[180px] animate-risi-float" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <!-- Feet -->
                          <circle cx="42" cy="88" r="6" fill="#E76F51" />
                          <circle cx="58" cy="88" r="6" fill="#E76F51" />
                          <!-- Tail -->
                          <path d="M40 80 L50 90 L60 80 Z" fill="#0A2240" />
                          <!-- Body -->
                          <rect x="25" y="32" width="50" height="52" rx="25" fill="#143A66" stroke="#0A2240" stroke-width="4"/>
                          <!-- Belly -->
                          <rect x="34" y="52" width="32" height="26" rx="13" fill="#FFFDF6" stroke="#0A2240" stroke-width="2"/>
                          <path d="M40 60 C43 65, 47 65, 50 60 C53 65, 57 65, 60 60" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>
                          <!-- Left Wing (waving) -->
                          <g class="animate-wing-wave-left">
                            <path d="M25 48 C12 45, 10 32, 18 22" fill="#143A66" stroke="#0A2240" stroke-width="4" stroke-linejoin="round" />
                          </g>
                          <!-- Right Wing (holding gavel) -->
                          <path d="M75 52 C85 55, 88 65, 76 72" fill="#143A66" stroke="#0A2240" stroke-width="4" />
                          <!-- Gavel -->
                          <g transform="translate(74, 58) rotate(20)">
                            <rect x="0" y="0" width="6" height="22" rx="2" fill="#F4A261" stroke="#0A2240" stroke-width="2"/>
                            <rect x="-8" y="-4" width="22" height="8" rx="2" fill="#E76F51" stroke="#0A2240" stroke-width="2"/>
                          </g>
                          <!-- Head Bob group -->
                          <g class="animate-head-bob">
                             <!-- Head -->
                             <rect x="22" y="14" width="56" height="42" rx="21" fill="#143A66" stroke="#0A2240" stroke-width="4"/>
                             <!-- Ears -->
                             <path d="M22 18 L12 6 L30 15 Z" fill="#0A2240" stroke="#0A2240" stroke-width="2" />
                             <path d="M78 18 L88 6 L70 15 Z" fill="#0A2240" stroke="#0A2240" stroke-width="2" />
                             <!-- Eyes (Glasses frame) -->
                             <circle cx="37" cy="32" r="11" fill="white" stroke="#F4A261" stroke-width="4"/>
                             <circle cx="63" cy="32" r="11" fill="white" stroke="#F4A261" stroke-width="4"/>
                             <path d="M48 32 L52 32" stroke="#F4A261" stroke-width="4" stroke-linecap="round"/>
                             <!-- Pupils (Blinking group) -->
                             <g class="animate-risi-blink">
                                <circle cx="37" cy="32" r="4.5" fill="#0A2240"/>
                                <circle cx="63" cy="32" r="4.5" fill="#0A2240"/>
                                <circle cx="35" cy="30" r="1.5" fill="white"/>
                                <circle cx="61" cy="30" r="1.5" fill="white"/>
                             </g>
                             <!-- Beak -->
                             <polygon points="50,35 45,42 55,42" fill="#E76F51" stroke="#0A2240" stroke-width="2"/>
                             <!-- Graduation Cap -->
                             <path d="M28 10 L50 1 L72 10 L50 19 Z" fill="#0A2240" stroke="#0A2240" stroke-width="2" />
                             <rect x="47" y="11" width="6" height="6" fill="#F4A261" />
                             <path d="M53 14 L60 21" stroke="#F4A261" stroke-width="2" stroke-linecap="round" />
                          </g>
                        </svg>
                    </div>

                    <div class="text-center">
                        <p class="font-heading font-black text-[18px] text-fg-heading mb-1">"Hai! Aku Si Risi"</p>
                        <p class="text-[13px] text-fg-body-subtle font-bold mb-4 bg-brand-softer px-3 py-1 rounded-full border border-brand/10">Asisten Elektronik Izin Penelitianmu</p>
                    </div>

                    <!-- Live Stats Widget -->
                    <div class="w-full grid grid-cols-3 gap-2.5 mt-2 border-t-2 border-border-default pt-4">
                        <div class="bg-neutral-primary-medium p-2.5 rounded-sm text-center border-2 border-border-default hover:scale-105 transition-transform duration-300">
                            <p class="text-[10px] font-extrabold text-fg-body-subtle uppercase tracking-wider mb-1">Masuk</p>
                            <p class="font-heading font-black text-[22px] text-brand leading-none">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                        <div class="bg-success-soft p-2.5 rounded-sm text-center border-2 border-border-success/30 hover:scale-105 transition-transform duration-300">
                            <p class="text-[10px] font-extrabold text-success-strong uppercase tracking-wider mb-1">Setuju</p>
                            <p class="font-heading font-black text-[22px] text-success-strong leading-none">{{ $stats['approved'] ?? 0 }}</p>
                        </div>
                        <div class="bg-warning-soft p-2.5 rounded-sm text-center border-2 border-border-warning/30 hover:scale-105 transition-transform duration-300">
                            <p class="text-[10px] font-extrabold text-warning-strong uppercase tracking-wider mb-1">Proses</p>
                            <p class="font-heading font-black text-[22px] text-warning-strong leading-none">{{ $stats['processing'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mascot Dialog Bubble -->
                <div class="absolute -top-[30px] -left-[40px] bg-brand-alt-soft border-[3px] border-brand-alt text-brand-alt-strong px-4 py-2.5 rounded-[20px] rounded-bl-none shadow-md font-bold text-[13px] animate-risi-float max-w-[160px]" style="animation-delay: 1s;">
                    Cepat & 100% Gratis lho! ✨
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── ALUR PERMOHONAN (Roadmap Visual) ── -->
<section id="alur" class="section-padding bg-gradient-to-b from-transparent to-white relative overflow-hidden">
    <div class="container-standard">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand-alt font-extrabold uppercase tracking-widest text-[12px] mb-[8px] block">Prosedur Layanan</span>
            <h2 class="text-h2 text-fg-heading mb-[16px] font-black">4 Langkah Mudah Pengajuan</h2>
            <p class="text-lead text-fg-body max-w-2xl mx-auto opacity-95">
                Ikuti alur jalan setapak penelitian berikut bersama Si Risi sampai surat izin terbit otomatis.
            </p>
        </div>

        <!-- Custom Roadmap Layout (Organic path connection) -->
        <div class="relative max-w-[1000px] mx-auto px-4">
            
            <!-- Connect Line (Only visible on large screen) -->
            <div class="absolute top-[90px] left-[10%] right-[10%] h-[6px] border-t-4 border-dashed border-brand-alt/40 doodle-path hidden lg:block z-0"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[32px] relative z-10">
                
                <!-- Step 1: Isi Data -->
                <div class="card-interactive p-[28px] text-center border-3 border-border-default group hover:border-brand-alt">
                    <div class="w-[84px] h-[84px] mx-auto rounded-full bg-brand-softer border-3 border-brand flex items-center justify-center mb-[20px] group-hover:scale-110 duration-300 relative">
                        <!-- Mascot icon SVG (Si Risi Writing) -->
                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="50" cy="50" r="40" fill="#FFFDF6" stroke="#0A2240" stroke-width="2"/>
                          <rect x="35" y="30" width="30" height="40" rx="4" fill="#D3E0F7" stroke="#0A2240" stroke-width="2"/>
                          <!-- Quill pencil -->
                          <path d="M65 25 L50 45 L45 55 L55 50 L75 30 Z" fill="#F4A261" stroke="#0A2240" stroke-width="2" />
                          <circle cx="45" cy="40" r="6" fill="#0A2240" />
                          <path d="M41 40 L49 40" stroke="white" stroke-width="1.5"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-7 h-7 bg-brand-alt text-white rounded-full flex items-center justify-center font-black text-[14px] border-2 border-white">1</span>
                    </div>
                    <h3 class="font-heading font-black text-[18px] text-fg-heading mb-[8px]">Isi Data Diri</h3>
                    <p class="text-[14px] text-fg-body-subtle leading-[1.6]">Lengkapi formulir pendaftaran, data pemohon, institusi, dan rencana riset secara terperinci.</p>
                </div>

                <!-- Step 2: Upload Dokumen -->
                <div class="card-interactive p-[28px] text-center border-3 border-border-default group hover:border-brand-alt" style="animation-delay: 0.15s;">
                    <div class="w-[84px] h-[84px] mx-auto rounded-full bg-brand-alt-soft border-3 border-brand-alt flex items-center justify-center mb-[20px] group-hover:scale-110 duration-300 relative">
                        <!-- Mascot icon SVG (Si Risi Uploading) -->
                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="50" cy="50" r="40" fill="#FFFDF6" stroke="#F4A261" stroke-width="2"/>
                          <!-- Cloud -->
                          <path d="M35 55 C30 55 28 48 34 44 C34 35 48 32 54 38 C62 34 70 42 66 50 C72 52 70 60 62 60 L38 60 Z" fill="#D3E0F7" stroke="#0A2240" stroke-width="2"/>
                          <!-- Arrow Up -->
                          <path d="M50 62 L50 48 M50 48 L46 52 M50 48 L54 52" stroke="#E76F51" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-7 h-7 bg-brand-alt text-white rounded-full flex items-center justify-center font-black text-[14px] border-2 border-white">2</span>
                    </div>
                    <h3 class="font-heading font-black text-[18px] text-fg-heading mb-[8px]">Upload Berkas</h3>
                    <p class="text-[14px] text-fg-body-subtle leading-[1.6]">Unggah berkas PDF wajib seperti Surat Pengantar dari Kampus/Instansi dan Proposal Penelitian.</p>
                </div>

                <!-- Step 3: Verifikasi -->
                <div class="card-interactive p-[28px] text-center border-3 border-border-default group hover:border-brand-alt" style="animation-delay: 0.3s;">
                    <div class="w-[84px] h-[84px] mx-auto rounded-full bg-info-soft border-3 border-info flex items-center justify-center mb-[20px] group-hover:scale-110 duration-300 relative">
                        <!-- Mascot icon SVG (Si Risi Verifying) -->
                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="50" cy="50" r="40" fill="#FFFDF6" stroke="#00796B" stroke-width="2"/>
                          <circle cx="45" cy="45" r="14" stroke="#0A2240" stroke-width="3" fill="none"/>
                          <path d="M55 55 L70 70" stroke="#0A2240" stroke-width="4" stroke-linecap="round"/>
                          <circle cx="45" cy="45" r="5" fill="#E76F51"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-7 h-7 bg-brand-alt text-white rounded-full flex items-center justify-center font-black text-[14px] border-2 border-white">3</span>
                    </div>
                    <h3 class="font-heading font-black text-[18px] text-fg-heading mb-[8px]">Verifikasi Admin</h3>
                    <p class="text-[14px] text-fg-body-subtle leading-[1.6]">Petugas Pengadilan Tinggi akan mengecek kelengkapan data. Pantau status berkala di menu Lacak.</p>
                </div>

                <!-- Step 4: Surat Terbit -->
                <div class="card-interactive p-[28px] text-center border-3 border-border-default group hover:border-brand-alt" style="animation-delay: 0.45s;">
                    <div class="w-[84px] h-[84px] mx-auto rounded-full bg-success-soft border-3 border-success flex items-center justify-center mb-[20px] group-hover:scale-110 duration-300 relative">
                        <!-- Mascot icon SVG (Si Risi Celebrating) -->
                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="50" cy="50" r="40" fill="#FFFDF6" stroke="#2E7D32" stroke-width="2"/>
                          <!-- Ribbon certificate -->
                          <rect x="36" y="28" width="28" height="38" rx="2" fill="#FEF3C7" stroke="#0A2240" stroke-width="2"/>
                          <circle cx="50" cy="42" r="5" fill="#E76F51"/>
                          <path d="M48 45 L45 58 L50 54 L55 58 L52 45 Z" fill="#F4A261" stroke="#0A2240" stroke-width="1.5"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-7 h-7 bg-brand-alt text-white rounded-full flex items-center justify-center font-black text-[14px] border-2 border-white">4</span>
                    </div>
                    <h3 class="font-heading font-black text-[18px] text-fg-heading mb-[8px]">Izin Penelitian Terbit</h3>
                    <p class="text-[14px] text-fg-body-subtle leading-[1.6]">Surat Izin Penelitian elektronik resmi ber-TTD QR-Code sah diterbitkan dan langsung dapat diunduh.</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ── PERSYARATAN ADMINISTRASI (Memo-Styled Cards) ── -->
<section id="persyaratan" class="section-padding bg-gradient-to-b from-white to-neutral-primary-soft">
    <div class="container-standard">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand-alt font-extrabold uppercase tracking-widest text-[12px] mb-[8px] block">Dokumen Wajib</span>
            <h2 class="text-h2 text-fg-heading mb-[16px] font-black">Persyaratan Administrasi</h2>
            <p class="text-lead text-fg-body max-w-2xl mx-auto opacity-95">
                Siapkan berkas-berkas digital (format PDF) berikut ini untuk diunggah pada formulir pendaftaran.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[32px]">
            @forelse($requirements as $req)
                <!-- Memo Style Card with custom pin accent -->
                <div class="bg-white border-2 border-border-default rounded-base p-[32px] relative shadow-2xs hover:shadow-xs transition-all duration-300 hover:-rotate-1">
                    <!-- Pin decoration -->
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-brand-alt border-[3px] border-white shadow-2xs"></div>
                    
                    <div class="flex items-start gap-[16px] mt-2">
                        <div class="w-[48px] h-[48px] rounded-[16px] bg-brand-softer text-brand flex items-center justify-center shrink-0 border-2 border-brand/20">
                            <i data-lucide="file-text" class="w-[24px] h-[24px]"></i>
                        </div>
                        <div>
                            <h3 class="text-[18px] font-heading font-black text-fg-heading mb-[8px] leading-tight">{{ $req->name }}</h3>
                            <p class="text-[14px] text-fg-body-subtle mb-[20px] leading-[1.6]">{{ $req->description }}</p>
                            
                            @if($req->is_required)
                                <span class="badge-danger font-extrabold uppercase tracking-wider text-[10px]">Wajib Dilampirkan</span>
                            @else
                                <span class="badge-neutral font-extrabold uppercase tracking-wider text-[10px]">Opsional</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-fg-body-subtle p-[60px] bg-white rounded-base border-3 border-dashed border-border-default-strong font-bold">
                    Belum ada data persyaratan administrasi yang dikonfigurasi.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── FAQ (Bubbly Balloon Dialogs) ── -->
<section id="faq" class="section-padding bg-gradient-to-b from-neutral-primary-soft to-white">
    <div class="container-standard max-w-[800px]">
        <div class="text-center mb-[80px] animate-fade-up">
            <span class="text-brand-alt font-extrabold uppercase tracking-widest text-[12px] mb-[8px] block">Pusat Bantuan</span>
            <h2 class="text-h2 text-fg-heading mb-[16px] font-black">Pertanyaan Seputar E-Riset</h2>
            <p class="text-lead text-fg-body opacity-95">Berikut adalah jawaban dari pertanyaan yang paling sering ditanyakan kepada Si Risi.</p>
        </div>

        <div class="flex flex-col gap-[20px]">
            @forelse($faqs as $index => $faq)
                <div x-data="{ open: false }" 
                     class="bg-neutral-primary-medium border-2 border-border-default rounded-base overflow-hidden transition-all duration-300" 
                     :class="{ 'border-brand-alt shadow-xs bg-white': open }">
                    
                    <button @click="open = !open" class="w-full flex items-center justify-between px-[28px] py-[22px] text-left focus:outline-none cursor-pointer">
                        <span class="text-[16px] font-heading font-black pr-[20px] leading-[1.4] transition-colors duration-200" 
                              :class="{ 'text-brand-alt-strong': open, 'text-fg-heading': !open }">
                              {{ $faq->question }}
                        </span>
                        
                        <div class="w-[36px] h-[36px] rounded-full bg-white border-2 border-border-default flex items-center justify-center shrink-0 transition-all duration-300" 
                             :class="{ 'bg-brand-alt text-white border-brand-alt rotate-180': open }">
                            <i data-lucide="chevron-down" class="w-[20px] h-[20px]"></i>
                        </div>
                    </button>
                    
                    <div x-show="open" x-collapse x-cloak>
                        <div class="px-[28px] pb-[28px] pt-[4px] text-[15px] text-fg-body leading-[1.7] border-t-2 border-border-default/40">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-[40px] text-center text-fg-body-subtle bg-neutral-primary-soft rounded-base border-3 border-border-default border-dashed font-bold">
                    Belum ada Data Tanya Jawab.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── CTA BANNER (Playful Court Banner) ── -->
<section class="pt-[100px] pb-[160px] relative overflow-hidden bg-neutral-primary-soft text-center">
    <!-- Bubble Pattern -->
    <div class="absolute inset-0 opacity-[0.4] pointer-events-none" style="background-image: radial-gradient(var(--color-border-default) 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
    
    <div class="relative z-10 container-standard max-w-[800px] animate-fade-up">
        <!-- Floating Gavel Icon -->
        <div class="w-[72px] h-[72px] bg-brand rounded-full border-[3px] border-brand-alt/30 flex items-center justify-center mx-auto mb-[28px] shadow-lg animate-risi-float">
            <i data-lucide="file-badge" class="w-[36px] h-[36px] text-brand-alt"></i>
        </div>
        
        <h2 class="font-heading font-black text-[38px] sm:text-[44px] text-fg-heading mb-[20px] leading-tight">Siap Mengajukan Izin Penelitian?</h2>
        <p class="text-[17px] sm:text-[19px] text-fg-body opacity-95 mb-[48px] max-w-[620px] mx-auto leading-[1.7] font-medium">
            Pengurusan administrasi permohonan izin penelitian kini dapat dilakukan dari mana saja, kapan saja, dan dijamin gratis.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-[16px] justify-center items-center">
            <a href="/register-permit" class="btn-brand btn-lg bg-brand-alt border-brand-alt text-white hover:bg-brand-alt-strong hover:border-brand-alt-strong shadow-lg rounded-full font-black px-[40px] py-[16px]">
                Mulai Pengajuan
                <i data-lucide="arrow-right-circle" class="w-[22px] h-[22px] animate-pulse"></i>
            </a>
        </div>
    </div>

    <!-- Layer 1: Orange Wave -->
    <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden leading-none z-0 translate-y-[4px]">
        <svg class="relative block w-full h-[60px] sm:h-[80px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,50 C300,110 900,20 1200,80 L1200,120 L0,120 Z" fill="#F4A261"></path>
        </svg>
    </div>
    <!-- Layer 2: Navy Wave -->
    <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden leading-none z-10">
        <svg class="relative block w-full h-[60px] sm:h-[80px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,60 C300,120 900,30 1200,90 L1200,120 L0,120 Z" fill="#0a2240"></path>
        </svg>
    </div>
</section>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
