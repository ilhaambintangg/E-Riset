@extends('layouts.public')

@section('content')

<!-- ── HERO SECTION ── -->
<section id="beranda" class="relative section-padding overflow-hidden pt-[160px] pb-[100px]">
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
                        <p class="text-[13px] text-fg-body-subtle font-bold mb-0 bg-brand-softer px-3 py-1 rounded-full border border-brand/10">Asisten Elektronik Izin Penelitianmu</p>
                    </div>
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
                Ikuti langkah-langkah pengisian formulir terintegrasi berikut bersama Si Risi untuk mengajukan izin penelitian Anda.
            </p>
        </div>

        <!-- Custom Roadmap Layout (Organic path connection) -->
        <div class="relative max-w-[1100px] mx-auto px-4">
            
            <!-- Connect Line (Aligned exactly with the circle centers on large screen) -->
            <div class="absolute top-[64px] left-[15%] right-[15%] h-[2px] border-t-2 border-dashed border-brand-alt/30 hidden lg:block z-0"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[28px] relative z-10">
                
                <!-- Step 1: Identitas & Anggota -->
                <div class="card-interactive bg-white/80 border border-border-default/40 backdrop-blur-md rounded-3xl p-[28px] text-center group transition-all duration-300 relative shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.15)] hover:-translate-y-2 hover:border-blue-400 overflow-hidden">
                    
                    <div class="w-[64px] h-[64px] mx-auto rounded-full bg-linear-to-tr from-[#1E3A8A] to-[#3B82F6] text-white flex items-center justify-center mb-[20px] shadow-[0_8px_20px_-6px_rgba(59,130,246,0.5)] group-hover:scale-110 group-hover:rotate-3 duration-300 relative shrink-0">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>
                    
                    <div class="mb-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black bg-blue-500/10 text-blue-600 uppercase tracking-widest">01 · DATA DIRI</span>
                    </div>
                    
                    <h3 class="font-heading font-black text-[19px] text-fg-heading mb-[10px] group-hover:text-blue-600 transition-colors">Identitas & Anggota</h3>
                    <p class="text-[13px] text-fg-body-subtle leading-[1.6]">Isi identitas diri berupa Nama, NIM/NPM, kontak WhatsApp, dan Email. Tambahkan anggota tim jika riset berkelompok.</p>
                </div>

                <!-- Step 2: Detail Riset & Akademik -->
                <div class="card-interactive bg-white/80 border border-border-default/40 backdrop-blur-md rounded-3xl p-[28px] text-center group transition-all duration-300 relative shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(249,115,22,0.15)] hover:-translate-y-2 hover:border-orange-400 overflow-hidden" style="animation-delay: 0.1s;">
                    
                    <div class="w-[64px] h-[64px] mx-auto rounded-full bg-linear-to-tr from-[#EA580C] to-[#F97316] text-white flex items-center justify-center mb-[20px] shadow-[0_8px_20px_-6px_rgba(249,115,22,0.5)] group-hover:scale-110 group-hover:rotate-3 duration-300 relative shrink-0">
                        <i data-lucide="graduation-cap" class="w-7 h-7"></i>
                    </div>
                    
                    <div class="mb-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black bg-orange-500/10 text-orange-600 uppercase tracking-widest">02 · DETAIL RISET</span>
                    </div>
                    
                    <h3 class="font-heading font-black text-[19px] text-fg-heading mb-[10px] group-hover:text-orange-600 transition-colors">Riset & Akademik</h3>
                    <p class="text-[13px] text-fg-body-subtle leading-[1.6]">Lengkapi data universitas/prodi, nomor & tanggal surat pengantar kampus, serta detail judul, lokasi, dan jenis riset.</p>
                </div>

                <!-- Step 3: Unggah Berkas -->
                <div class="card-interactive bg-white/80 border border-border-default/40 backdrop-blur-md rounded-3xl p-[28px] text-center group transition-all duration-300 relative shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.15)] hover:-translate-y-2 hover:border-amber-400 overflow-hidden" style="animation-delay: 0.2s;">
                    
                    <div class="w-[64px] h-[64px] mx-auto rounded-full bg-linear-to-tr from-[#D97706] to-[#F59E0B] text-white flex items-center justify-center mb-[20px] shadow-[0_8px_20px_-6px_rgba(245,158,11,0.5)] group-hover:scale-110 group-hover:rotate-3 duration-300 relative shrink-0">
                        <i data-lucide="upload-cloud" class="w-7 h-7"></i>
                    </div>
                    
                    <div class="mb-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black bg-amber-500/10 text-amber-600 uppercase tracking-widest">03 · DOKUMEN</span>
                    </div>
                    
                    <h3 class="font-heading font-black text-[19px] text-fg-heading mb-[10px] group-hover:text-amber-600 transition-colors">Unggah Berkas</h3>
                    <p class="text-[13px] text-fg-body-subtle leading-[1.6]">Unggah dokumen wajib berformat PDF (Maks. 2 MB) yaitu Surat Pengantar Kampus serta berkas Proposal Penelitian.</p>
                </div>

                <!-- Step 4: Tinjau & Kirim -->
                <div class="card-interactive bg-white/80 border border-border-default/40 backdrop-blur-md rounded-3xl p-[28px] text-center group transition-all duration-300 relative shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(20,184,166,0.15)] hover:-translate-y-2 hover:border-teal-400 overflow-hidden" style="animation-delay: 0.3s;">
                    
                    <div class="w-[64px] h-[64px] mx-auto rounded-full bg-linear-to-tr from-[#0D9488] to-[#14B8A6] text-white flex items-center justify-center mb-[20px] shadow-[0_8px_20px_-6px_rgba(20,184,166,0.5)] group-hover:scale-110 group-hover:rotate-3 duration-300 relative shrink-0">
                        <i data-lucide="send" class="w-7 h-7"></i>
                    </div>
                    
                    <div class="mb-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black bg-teal-500/10 text-teal-600 uppercase tracking-widest">04 · SUBMIT</span>
                    </div>
                    
                    <h3 class="font-heading font-black text-[19px] text-fg-heading mb-[10px] group-hover:text-teal-600 transition-colors">Tinjau & Kirim</h3>
                    <p class="text-[13px] text-fg-body-subtle leading-[1.6]">Periksa ringkasan seluruh data riset Anda, centang pernyataan kebenaran berkas, lalu kirim untuk memperoleh Nomor Registrasi.</p>
                </div>

            </div>
        </div>

        <!-- Pasca Pengajuan Section -->
        <div class="mt-[100px] pt-[80px] animate-fade-up">
            <div class="text-center mb-[48px]">
                <h3 class="font-heading font-black text-[24px] text-fg-heading mb-[10px]">Apa yang Terjadi Setelah Permohonan Dikirim?</h3>
                <p class="text-[14px] text-fg-body-subtle max-w-xl mx-auto font-medium opacity-90">
                    Formulir pengajuan Anda selesai dalam 4 langkah mudah di atas. Setelah dikirim, alur dilanjutkan secara internal oleh Pengadilan Tinggi melalui 2 tahap berikut:
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-[32px] max-w-[900px] mx-auto">
                <!-- Tahap A: Verifikasi Dokumen -->
                <div class="card-interactive bg-white/90 border border-border-default/50 hover:border-brand rounded-3xl p-[32px] flex items-start gap-6 group transition-all duration-300 relative shadow-sm hover:shadow-md hover:-translate-y-1 overflow-hidden">
                    
                    <div class="w-[56px] h-[56px] rounded-2xl bg-linear-to-tr from-brand to-brand-medium text-white flex items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    
                    <div>
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-black bg-brand/10 text-brand uppercase tracking-wider mb-2">TAHAP A</span>
                        <h4 class="font-heading font-black text-[18px] text-fg-heading mb-[8px] group-hover:text-brand transition-colors">Validasi & Verifikasi Berkas</h4>
                        <p class="text-[13px] text-fg-body-subtle leading-[1.6] font-medium">
                            Petugas Pengadilan Tinggi akan memverifikasi kesesuaian data formulir dan berkas PDF Anda. Proses verifikasi dapat dipantau di menu Lacak Permohonan.
                        </p>
                    </div>
                </div>

                <!-- Tahap B: Izin Penelitian Terbit -->
                <div class="card-interactive bg-white/90 border border-border-default/50 hover:border-success rounded-3xl p-[32px] flex items-start gap-6 group transition-all duration-300 relative shadow-sm hover:shadow-md hover:-translate-y-1 overflow-hidden">
                    
                    <div class="w-[56px] h-[56px] rounded-2xl bg-linear-to-tr from-success to-success-strong text-white flex items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    
                    <div>
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-black bg-success-soft text-success-strong uppercase tracking-wider mb-2">TAHAP B</span>
                        <h4 class="font-heading font-black text-[18px] text-fg-heading mb-[8px] group-hover:text-success-strong transition-colors">Penerbitan Surat Izin Resmi</h4>
                        <p class="text-[13px] text-fg-body-subtle leading-[1.6] font-medium">
                            Setelah disetujui, Surat Izin Penelitian elektronik resmi ber-TTD QR Code sah akan diterbitkan secara otomatis dan dapat Anda unduh langsung secara gratis.
                        </p>
                    </div>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-[32px] max-w-[900px] mx-auto">
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
<section class="pt-[100px] pb-[160px] relative overflow-hidden bg-gradient-to-b from-white to-neutral-primary-soft text-center">
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
