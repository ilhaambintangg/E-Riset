@extends('layouts.public')

@section('content')

<div class="min-h-screen bg-transparent relative overflow-hidden flex flex-col pt-20">
    <!-- Decorative Floating Ambient Light Blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-brand-soft/15 blur-[120px] pointer-events-none animate-blob"></div>
    <div class="absolute bottom-[10%] right-[-10%] w-[45vw] h-[45vw] rounded-full bg-brand-alt-soft/25 blur-[130px] pointer-events-none animate-blob animation-delay-2000"></div>
    <div class="absolute top-[30%] right-[20%] w-[35vw] h-[35vw] rounded-full bg-emerald-100/10 blur-[100px] pointer-events-none animate-blob animation-delay-4000"></div>
    
    <div class="flex items-center justify-center flex-1 py-20 px-4 relative z-10">
        <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-xl shadow-brand/5 border border-white/60 p-10 max-w-lg w-full text-center relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-brand/10 hover:border-white/80 animate-fade-in-up">
            
            <!-- Success Status Pill Badge -->
            <div class="inline-flex items-center gap-2.5 px-6 py-2.5 mb-8 bg-gradient-to-r from-success to-success-strong text-white rounded-full text-xs sm:text-sm font-black tracking-widest uppercase shadow-md shadow-success/25 relative overflow-hidden animate-fade-in-up delay-100 border border-success-medium/30">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                </span>
                Permohonan Berhasil Diajukan
            </div>

            <!-- Graduation Celebratory Mascot (Si Risi) -->
            <div class="animate-fade-in-up delay-200 mb-6">
                <svg class="w-24 h-24 mx-auto animate-risi-float" viewBox="0 0 100 100" fill="none">
                    <!-- Left Wing (Celebrating/Waving) -->
                    <path d="M12 55 C6 50 10 40 18 45" stroke="#0A2240" stroke-width="3" fill="#143A66" stroke-linejoin="round" class="animate-wing-wave-left" />
                    <!-- Right Wing (Celebrating/Waving) -->
                    <path d="M88 55 C94 50 90 40 82 45" stroke="#0A2240" stroke-width="3" fill="#143A66" stroke-linejoin="round" class="animate-wing-wave-right" />
                    
                    <!-- Body/Head -->
                    <circle cx="50" cy="58" r="32" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                    
                    <!-- Eyes (White part) -->
                    <circle cx="38" cy="58" r="9" fill="white" stroke="#F4A261" stroke-width="2.5"/>
                    <circle cx="62" cy="58" r="9" fill="white" stroke="#F4A261" stroke-width="2.5"/>
                    
                    <!-- Eye Pupils (Blinking Group) -->
                    <g class="animate-risi-blink">
                        <circle cx="38" cy="58" r="4" fill="#0A2240"/>
                        <circle cx="62" cy="58" r="4" fill="#0A2240"/>
                        <circle cx="40" cy="56" r="1.5" fill="white"/> <!-- eye highlight -->
                        <circle cx="64" cy="56" r="1.5" fill="white"/> <!-- eye highlight -->
                    </g>
                    
                    <!-- Beak -->
                    <polygon points="50,61 46,67 54,67" fill="#E76F51" stroke="#0A2240" stroke-width="2"/>
                    
                    <!-- Graduation Cap (Mortarboard) -->
                    <!-- Cap Base -->
                    <path d="M36,36 L36,42 C36,46 64,46 64,42 L64,36" fill="#0A2240" stroke="#041021" stroke-width="2.5"/>
                    <!-- Diamond Top -->
                    <polygon points="50,22 78,31 50,40 22,31" fill="#041021" stroke="#0A2240" stroke-width="2"/>
                    <!-- Tassel -->
                    <path d="M50,31 L72,34 L72,46" stroke="#F4A261" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <circle cx="72" cy="48" r="3.5" fill="#E76F51"/>
                </svg>
            </div>

            <!-- Title -->
            <h2 class="text-3xl font-heading font-black text-brand mb-3 tracking-tight leading-tight animate-fade-in-up delay-400">
                Permohonan Berhasil Dikirim
            </h2>
            
            <!-- Register Number Box -->
            <div class="bg-gradient-to-br from-brand-alt-soft/50 to-brand-alt-soft/10 border border-brand-alt/25 rounded-2xl p-5 mb-8 text-left shadow-2xs hover:shadow-xs transition-shadow duration-300 animate-fade-in-up delay-500">
                @if(isset($registration_number) && $registration_number)
                <div class="bg-white rounded-xl p-4 border border-brand-alt/20 text-center shadow-2xs relative overflow-hidden group/register transition-all duration-300 hover:border-brand-alt/50 hover:shadow-sm mb-4">
                    <!-- Decorative corner accent -->
                    <div class="absolute top-0 right-0 w-8 h-8 bg-brand-alt/5 rounded-bl-full pointer-events-none transition-all duration-300 group-hover/register:bg-brand-alt/10 group-hover/register:scale-110"></div>
                    
                    <p class="text-xs text-brand-alt-strong font-black mb-1 uppercase tracking-widest">Nomor Register</p>
                    
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <!-- The Registration Number -->
                        <p id="regNumber" class="text-2xl font-mono font-black text-brand tracking-wide select-all">{{ $registration_number }}</p>
                        
                        <!-- Copy Button -->
                        <button type="button" onclick="copyRegisterNumber()" class="p-1.5 rounded-lg bg-neutral-primary-soft/30 hover:bg-brand-alt/20 text-brand-alt-strong hover:text-brand transition-all duration-200 cursor-pointer group/copy tooltip-trigger relative" title="Salin Nomor">
                            <i data-lucide="copy" class="w-4 h-4 transition-transform duration-200 group-hover/copy:scale-105 active:scale-95"></i>
                            <!-- Tooltip -->
                            <span id="copyTooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2.5 px-2.5 py-1 text-[11px] text-white bg-slate-900/90 rounded-md opacity-0 pointer-events-none transition-all duration-200 scale-90 whitespace-nowrap font-sans font-bold shadow-sm z-30">
                                Salin Nomor
                            </span>
                        </button>
                    </div>
                </div>
                @endif
                <p class="text-sm text-fg-body leading-relaxed text-center font-medium">
                    Silakan simpan nomor register ini untuk melakukan pelacakan status permohonan.<br>
                    Nomor register juga telah dikirim ke Email Anda.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 animate-fade-in-up delay-600">
                <a href="/" class="flex items-center justify-center gap-2.5 bg-gradient-to-r from-brand to-brand-medium hover:from-brand-medium hover:to-brand text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-brand-medium/15 hover:-translate-y-0.5 active:translate-y-0 group/home cursor-pointer">
                    <i data-lucide="home" class="w-5 h-5 transition-transform duration-300 group-hover/home:-translate-y-0.5"></i>
                    Kembali ke Beranda
                </a>
                <a href="/track?registration_number={{ $registration_number }}" class="flex items-center justify-center gap-2.5 bg-white text-brand font-bold py-4 rounded-xl transition-all duration-300 border border-neutral-primary-strong/30 hover:border-brand-medium hover:bg-brand-softer/30 hover:-translate-y-0.5 active:translate-y-0 shadow-2xs hover:shadow-sm group/track cursor-pointer">
                    <i data-lucide="search" class="w-5 h-5 transition-transform duration-300 group-hover/track:scale-105"></i>
                    Cek Status Sekarang
                    <i data-lucide="arrow-right" class="w-4 h-4 opacity-70 transition-transform duration-300 group-hover/track:translate-x-0.5"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom Animations for entry and interactive states */
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }
    .delay-600 { animation-delay: 600ms; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Ambient Blobs floating animation */
    @keyframes blob-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.08); }
        66% { transform: translate(-20px, 20px) scale(0.95); }
    }
    .animate-blob {
        animation: blob-float 15s ease-in-out infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2.5s;
    }
    .animation-delay-4000 {
        animation-delay: 5s;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyRegisterNumber() {
        const regText = document.getElementById('regNumber').innerText;
        navigator.clipboard.writeText(regText).then(() => {
            const tooltip = document.getElementById('copyTooltip');
            tooltip.innerText = 'Nomor Tersalin! ✓';
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateX(-50%) scale(100%)';
            
            setTimeout(() => {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'translateX(-50%) scale(90%)';
                // Reset text after fade out
                setTimeout(() => {
                    tooltip.innerText = 'Salin Nomor';
                }, 200);
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
        });
    }

    // Initialize tooltip hover event listeners manually for precise control
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.querySelector('.tooltip-trigger');
        const tooltip = document.getElementById('copyTooltip');
        if (btn && tooltip) {
            btn.addEventListener('mouseenter', () => {
                if (tooltip.innerText === 'Salin Nomor') {
                    tooltip.style.opacity = '1';
                    tooltip.style.transform = 'translateX(-50%) scale(100%)';
                }
            });
            btn.addEventListener('mouseleave', () => {
                if (tooltip.innerText === 'Salin Nomor') {
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'translateX(-50%) scale(90%)';
                }
            });
        }
    });
</script>
@endpush

@endsection
