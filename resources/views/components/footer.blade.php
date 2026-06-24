@php
    $setting = \App\Models\WebSetting::first();
@endphp

<footer class="relative border-t pt-[80px] pb-[40px] overflow-hidden z-10" style="background-color: #0F172A; border-color: #1E293B; color: #E5E7EB;">
    <!-- Subtle Background Accent Glows -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -bottom-[20%] -left-[10%] w-[400px] h-[400px] rounded-full blur-[100px]" style="background-color: rgba(30, 58, 138, 0.15);"></div>
        <div class="absolute -top-[20%] -right-[10%] w-[300px] h-[300px] rounded-full blur-[80px]" style="background-color: rgba(245, 158, 11, 0.05);"></div>
    </div>

    <div class="relative z-10 container-standard animate-fade-up">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[48px] mb-[64px]">
            <!-- Column 1: Identitas Sistem -->
            <div class="space-y-[24px]">
                <div class="inline-flex items-center gap-1.5 border rounded-full px-3 py-1 shadow-inner" style="background-color: #1E293B; border-color: rgba(51, 65, 85, 0.5);">
                    <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: #F59E0B;"></div>
                    <span class="text-[10px] font-bold uppercase tracking-wider" style="color: #CBD5E1;">{{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}</span>
                </div>

                <div class="flex items-center gap-[16px] group">
                    <div class="w-[52px] h-[52px] rounded-base flex items-center justify-center shadow-lg border transition-all duration-300 hover:scale-105" style="background-color: #1E3A8A; border-color: rgba(30, 58, 138, 0.5);">
                        <i data-lucide="scale" class="w-[28px] h-[28px]" style="color: #F59E0B;"></i>
                    </div>
                    <div>
                        <h2 class="font-heading font-bold text-[22px] tracking-tight leading-none mb-1.5" style="color: #FFFFFF;">E-RISET</h2>
                        <p class="text-[11px] font-bold uppercase tracking-wider leading-none" style="color: #94A3B8;">Sistem Izin Penelitian Elektronik</p>
                    </div>
                </div>

                <p class="text-[14px] leading-[1.7] max-w-[360px]" style="color: #94A3B8;">
                    E-RISET merupakan sistem pelayanan digital yang digunakan untuk mempermudah proses permohonan izin penelitian secara elektronik pada {{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}.
                </p>

                <!-- Social Media -->
                <div class="flex items-center gap-[12px] pt-[8px]">
                    <a href="https://instagram.com" target="_blank" class="w-9 h-9 rounded-full border flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg social-icon" style="background-color: #1E293B; border-color: rgba(51, 65, 85, 0.5); color: #E2E8F0;" title="Instagram">
                        <i data-lucide="instagram" class="w-[18px] h-[18px]"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="w-9 h-9 rounded-full border flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg social-icon" style="background-color: #1E293B; border-color: rgba(51, 65, 85, 0.5); color: #E2E8F0;" title="Facebook">
                        <i data-lucide="facebook" class="w-[18px] h-[18px]"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" class="w-9 h-9 rounded-full border flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg social-icon" style="background-color: #1E293B; border-color: rgba(51, 65, 85, 0.5); color: #E2E8F0;" title="YouTube">
                        <i data-lucide="youtube" class="w-[18px] h-[18px]"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Informasi Kontak -->
            <div>
                <h3 class="font-heading font-bold mb-[24px] uppercase tracking-wider text-[13px] flex items-center gap-2" style="color: #FFFFFF;">
                    <i data-lucide="info" class="w-4 h-4" style="color: #F59E0B;"></i> Informasi Kontak
                </h3>
                
                <div class="space-y-[16px]">
                    <!-- Address Card -->
                    <div class="border rounded-default p-4 transition-all duration-300 group shadow-xs contact-card" style="background-color: rgba(30, 41, 59, 0.3); border-color: rgba(51, 65, 85, 0.4);">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-sm flex items-center justify-center shrink-0 transition-colors duration-300 card-icon-wrapper" style="background-color: #1E293B;">
                                <i data-lucide="map-pin" class="w-4 h-4" style="color: #F59E0B;"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider mb-1" style="color: #94A3B8;">Alamat Kantor</p>
                                <p class="text-[13px] leading-relaxed" style="color: #CBD5E1;">{{ $setting->alamat ?? 'Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung, Lampung 35214' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone & Email Card -->
                    <div class="border rounded-default p-4 transition-all duration-300 group shadow-xs contact-card" style="background-color: rgba(30, 41, 59, 0.3); border-color: rgba(51, 65, 85, 0.4);">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-sm flex items-center justify-center shrink-0 transition-colors duration-300 card-icon-wrapper" style="background-color: #1E293B;">
                                <i data-lucide="phone" class="w-4 h-4" style="color: #F59E0B;"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider mb-1" style="color: #94A3B8;">Hubungi Kami</p>
                                <p class="text-[13px] leading-relaxed mb-0.5" style="color: #CBD5E1;">Telp: {{ $setting->telepon ?? '(0721) 482436' }}</p>
                                <p class="text-[13px] leading-relaxed" style="color: #CBD5E1;">Email: {{ $setting->email ?? 'info@pt-tanjungkarang.go.id' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="border rounded-default p-4 transition-all duration-300 group shadow-xs contact-card" style="background-color: rgba(30, 41, 59, 0.3); border-color: rgba(51, 65, 85, 0.4);">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-sm flex items-center justify-center shrink-0 transition-colors duration-300 card-icon-wrapper" style="background-color: #1E293B;">
                                <i data-lucide="clock" class="w-4 h-4" style="color: #F59E0B;"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider mb-1" style="color: #94A3B8;">Jam Operasional</p>
                                <p class="text-[13px] leading-relaxed" style="color: #CBD5E1;">Senin - Jumat: 08:00 - 16:30 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Lokasi Kantor -->
            <div class="md:col-span-2 lg:col-span-1">
                <h3 class="font-heading font-bold mb-[24px] uppercase tracking-wider text-[13px] flex items-center gap-2" style="color: #FFFFFF;">
                    <i data-lucide="map" class="w-4 h-4" style="color: #F59E0B;"></i> Lokasi Kantor
                </h3>
                
                <div class="border rounded-default p-4 transition-all duration-300 shadow-sm flex flex-col gap-4" style="background-color: rgba(30, 41, 59, 0.3); border-color: rgba(51, 65, 85, 0.4);">
                    <!-- Responsive Google Maps Embed -->
                    <div class="relative w-full rounded-base overflow-hidden border footer-map-container" style="height: 180px; border-color: rgba(51, 65, 85, 0.5);">
                        @if($setting && $setting->google_maps)
                            {!! $setting->google_maps !!}
                        @else
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.938835848525!2d105.25732131476686!3d-5.42617699606473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da45a3db2be3%3A0xc6c4f034be8b2e1!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1684824000000!5m2!1sen!2sid" 
                                    class="absolute inset-0 w-full h-full border-0 transition-transform duration-500 group-hover:scale-105" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        @endif
                    </div>
                    
                    <!-- Button Buka di Google Maps -->
                    <a href="https://maps.google.com/?q=Pengadilan%20Tinggi%20Tanjung%20Karang%20Bandar%20Lampung" 
                       target="_blank" 
                       class="w-full text-[12px] py-[10px] justify-center flex items-center gap-2 rounded-default border hover:bg-slate-800 transition-colors shadow-sm"
                       style="background-color: #1E293B; border-color: #334155; color: #E5E7EB;">
                        <i data-lucide="external-link" class="w-3.5 h-3.5" style="color: #F59E0B;"></i>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t pt-[32px] flex flex-col md:flex-row justify-between items-center gap-[16px] text-center md:text-left" style="border-color: #1E293B;">
            <div class="text-[13px]" style="color: #94A3B8;">
                &copy; 2026 <strong style="color: #E5E7EB;">E-RISET</strong> — {{ $setting->nama_instansi ?? 'Pengadilan Tinggi Tanjungkarang' }}. Semua Hak Dilindungi.
            </div>
            <div class="text-[13px]" style="color: #64748B;">
                Powered by: <span class="font-semibold" style="color: #F59E0B;">Tim Pengembang E-RISET</span>
            </div>
        </div>
    </div>
</footer>

<style>
    .social-icon {
        transition: all 0.3s ease !important;
    }
    .social-icon:hover {
        background-color: #F59E0B !important;
        color: #0F172A !important;
        border-color: #F59E0B !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 15px -3px rgba(245, 158, 11, 0.3) !important;
    }
    .contact-card {
        transition: all 0.3s ease !important;
    }
    .contact-card:hover {
        background-color: rgba(30, 41, 59, 0.6) !important;
        border-color: #475569 !important;
    }
    .contact-card:hover .card-icon-wrapper {
        background-color: #1E3A8A !important;
    }
    .footer-map-container iframe {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        border: 0 !important;
        transition: transform 0.5s ease !important;
    }
    .footer-map-container:hover iframe {
        transform: scale(1.05);
    }
</style>

