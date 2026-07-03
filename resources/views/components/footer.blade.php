@php
    $setting = \App\Models\WebSetting::first();
@endphp

<footer class="relative border-t-[8px] border-brand-alt pt-[80px] pb-[40px] overflow-hidden z-10 bg-brand text-white">
    <!-- Subtle Background Accent Glows -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -bottom-[20%] -left-[10%] w-[400px] h-[400px] rounded-full blur-[100px]" style="background-color: rgba(244, 162, 97, 0.1);"></div>
        <div class="absolute -top-[20%] -right-[10%] w-[300px] h-[300px] rounded-full blur-[80px]" style="background-color: rgba(244, 162, 97, 0.05);"></div>
    </div>

    <div class="relative z-10 container-standard animate-fade-up">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[48px] mb-[64px]">
            <!-- Column 1: Identitas Sistem -->
            <div class="space-y-[24px]">
                <div class="inline-flex items-center gap-1.5 border-2 border-brand-medium rounded-full px-4 py-1.5 shadow-inner bg-brand-medium/50">
                    <div class="w-2.5 h-2.5 rounded-full bg-brand-alt animate-ping"></div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-soft">{{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}</span>
                </div>

                <div class="flex items-center gap-[16px]">
                    <!-- Waving Risi Mascot -->
                    <div class="shrink-0">
                        <svg class="w-[72px] h-[72px] animate-risi-float" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="40" cy="85" r="5" fill="#E76F51" />
                          <circle cx="60" cy="85" r="5" fill="#E76F51" />
                          <rect x="25" y="30" width="50" height="55" rx="25" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                          <rect x="35" y="50" width="30" height="30" rx="15" fill="#FFFDF6"/>
                          <path d="M25 45 C15 45, 12 55, 25 65" fill="#143A66" stroke="#0A2240" stroke-width="3" />
                          <g class="animate-wing-wave-right">
                             <path d="M75 45 C85 45, 88 35, 80 25" fill="#143A66" stroke="#0A2240" stroke-width="3" />
                          </g>
                          <g class="animate-head-bob">
                             <rect x="23" y="15" width="54" height="40" rx="20" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                             <path d="M23 20 L15 10 L30 18 Z" fill="#0A2240" />
                             <path d="M77 20 L85 10 L70 18 Z" fill="#0A2240" />
                             <circle cx="38" cy="32" r="10" fill="white" stroke="#F4A261" stroke-width="3"/>
                             <circle cx="62" cy="32" r="10" fill="white" stroke="#F4A261" stroke-width="3"/>
                             <path d="M48 32 L52 32" stroke="#F4A261" stroke-width="3"/>
                             <g class="animate-risi-blink">
                                <circle cx="38" cy="32" r="4" fill="#0A2240"/>
                                <circle cx="62" cy="32" r="4" fill="#0A2240"/>
                                <circle cx="36" cy="30" r="1.5" fill="white"/>
                                <circle cx="60" cy="30" r="1.5" fill="white"/>
                             </g>
                             <polygon points="50,34 46,40 54,40" fill="#E76F51"/>
                             <path d="M30 10 L50 2 L70 10 L50 18 Z" fill="#0A2240" />
                             <rect x="47" y="12" width="6" height="6" fill="#F4A261" />
                             <path d="M53 15 L58 22" stroke="#F4A261" stroke-width="2" />
                          </g>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-heading font-black text-[24px] tracking-tight leading-none mb-1 text-white">E-RISET</h2>
                        <p class="text-[11px] font-extrabold uppercase tracking-wider leading-none text-brand-alt">Izin Penelitian Online</p>
                    </div>
                </div>

                <p class="text-[14px] leading-[1.7] max-w-[360px] text-brand-soft">
                    E-RISET merupakan sistem pelayanan digital yang digunakan untuk mempermudah proses permohonan izin penelitian secara elektronik pada {{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}.
                </p>

                <!-- Social Media -->
                <div class="flex items-center gap-3 pt-2">

                    <a href="https://instagram.com"
                    target="_blank"
                    class="w-10 h-10 rounded-full bg-brand-medium border-2 border-brand-medium flex items-center justify-center text-white transition-all hover:bg-brand-alt hover:border-brand-alt hover:-translate-y-1 hover:shadow-lg"
                    title="Instagram">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>

                    <a href="https://facebook.com"
                    target="_blank"
                    class="w-10 h-10 rounded-full bg-brand-medium border-2 border-brand-medium flex items-center justify-center text-white transition-all hover:bg-brand-alt hover:border-brand-alt hover:-translate-y-1 hover:shadow-lg"
                    title="Facebook">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </a>

                    <a href="https://youtube.com"
                    target="_blank"
                    class="w-10 h-10 rounded-full bg-brand-medium border-2 border-brand-medium flex items-center justify-center text-white transition-all hover:bg-brand-alt hover:border-brand-alt hover:-translate-y-1 hover:shadow-lg"
                    title="YouTube">
                        <i class="fa-brands fa-youtube text-lg"></i>
                    </a>

                </div>
            </div>

            <!-- Column 2: Informasi Kontak -->
            <div class="space-y-6">
                <h3 class="font-heading font-black uppercase tracking-wider text-[13px] flex items-center gap-2 text-white border-b-2 border-brand-medium pb-2">
                    <i data-lucide="info" class="w-4 h-4 text-brand-alt animate-pulse"></i> Informasi Kontak
                </h3>
                
                <div class="space-y-[16px]">
                    <!-- Address Card -->
                    <div class="border-2 rounded-default p-4 transition-all duration-300 bg-brand-medium/30 border-brand-medium hover:bg-brand-medium/50 group">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center shrink-0 border border-brand-medium">
                                <i data-lucide="map-pin" class="w-4 h-4 text-brand-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-wider mb-1 text-brand-alt">Alamat Kantor</p>
                                <p class="text-[13px] leading-relaxed text-brand-soft">{{ $setting->alamat ?? 'Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung, Lampung 35214' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone & Email Card -->
                    <div class="border-2 rounded-default p-4 transition-all duration-300 bg-brand-medium/30 border-brand-medium hover:bg-brand-medium/50 group">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center shrink-0 border border-brand-medium">
                                <i data-lucide="phone" class="w-4 h-4 text-brand-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-wider mb-1 text-brand-alt">Hubungi Kami</p>
                                <p class="text-[13px] leading-relaxed mb-0.5 text-brand-soft font-mono">Telp: {{ $setting->telepon ?? '(0721) 482436' }}</p>
                                <p class="text-[13px] leading-relaxed text-brand-soft font-mono">Email: {{ $setting->email ?? 'info@pt-tanjungkarang.go.id' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="border-2 rounded-default p-4 transition-all duration-300 bg-brand-medium/30 border-brand-medium hover:bg-brand-medium/50 group">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center shrink-0 border border-brand-medium">
                                <i data-lucide="clock" class="w-4 h-4 text-brand-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-wider mb-1 text-brand-alt">Jam Operasional</p>
                                <p class="text-[13px] leading-relaxed text-brand-soft">Senin - Jumat: 08:00 - 16:30 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Lokasi Kantor -->
            <div class="md:col-span-2 lg:col-span-1 space-y-6">
                <h3 class="font-heading font-black uppercase tracking-wider text-[13px] flex items-center gap-2 text-white border-b-2 border-brand-medium pb-2">
                    <i data-lucide="map" class="w-4 h-4 text-brand-alt"></i> Lokasi Kantor
                </h3>
                
                <div class="border-2 rounded-default p-4 bg-brand-medium/30 border-brand-medium flex flex-col gap-4">
                    <!-- Responsive Google Maps Embed -->
                    <div class="relative w-full rounded-[14px] overflow-hidden border-2 border-brand-medium h-[180px]">
                        @if($setting && $setting->google_maps)
                            {!! $setting->google_maps !!}
                        @else
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.938835848525!2d105.25732131476686!3d-5.42617699606473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da45a3db2be3%3A0xc6c4f034be8b2e1!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1684824000000!5m2!1sen!2sid" 
                                    class="absolute inset-0 w-full h-full border-0 transition-transform duration-500 hover:scale-105" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        @endif
                    </div>
                    
                    <!-- Button Buka di Google Maps -->
                    <a href="https://maps.google.com/?q=Pengadilan%20Tinggi%20Tanjung%20Karang%20Bandar%20Lampung" 
                       target="_blank" 
                       class="w-full text-[12px] py-[12px] justify-center flex items-center gap-2 rounded-full border-2 border-brand-medium bg-brand hover:bg-brand-medium transition-all shadow-sm font-bold text-white hover:text-brand-alt">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t-2 border-brand-medium pt-[32px] flex flex-col md:flex-row justify-between items-center gap-[16px] text-center md:text-left">
            <div class="text-[13px] text-brand-soft">
                &copy; 2026 <strong class="text-white">E-RISET</strong> — {{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}. Semua Hak Dilindungi.
            </div>
            <div class="text-[13px] text-brand-soft/60">
                Powered by: <span class="font-extrabold text-brand-alt">Tim Pengembang E-RISET</span>
            </div>
        </div>
    </div>
</footer>
