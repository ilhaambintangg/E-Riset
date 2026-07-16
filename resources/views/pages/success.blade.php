@extends('layouts.public')

@section('content')

<div class="min-h-screen bg-transparent relative overflow-hidden flex flex-col pt-20">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-br from-brand-alt-soft/10 to-transparent z-0"></div>
    
    <div class="flex items-center justify-center flex-1 py-20 px-4 relative z-10">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white p-10 max-w-lg w-full text-center animate-fade-in-up">
            
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-2 justify-center text-sm font-bold shadow-sm">
                <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                Permohonan berhasil diajukan.
            </div>

            <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-green-500/30">
                <i data-lucide="check-circle-2" class="w-12 h-12 text-white"></i>
            </div>

            <h2 class="text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Permohonan Berhasil Dikirim</h2>
            
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-8 text-left shadow-sm">
                @if(isset($registration_number) && $registration_number)
                <div class="bg-white rounded-xl p-4 border border-amber-200 text-center shadow-sm mb-4">
                    <p class="text-xs text-amber-600 font-bold mb-1.5 uppercase tracking-widest">Nomor Register</p>
                    <p class="text-2xl font-mono font-black text-slate-900 tracking-tight">{{ $registration_number }}</p>
                </div>
                @endif
                <p class="text-sm text-slate-800 leading-relaxed text-center font-medium">
                    Silakan simpan nomor register ini untuk melakukan pelacakan status permohonan.<br>
                    Nomor register juga telah dikirim ke Email Anda.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <a href="/" class="flex items-center justify-center gap-2 bg-gradient-to-r from-brand to-brand-medium hover:from-brand-medium hover:to-brand text-white font-bold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-brand-medium/30">
                    Kembali ke Beranda
                </a>
                <a href="/track?registration_number={{ $registration_number }}" class="flex items-center justify-center gap-2 bg-white text-slate-900 border border-slate-200 font-bold py-3.5 rounded-xl transition-all hover:bg-slate-50">
                    Cek Status Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); scale: 0.95; }
        to { opacity: 1; transform: translateY(0); scale: 1; }
    }
</style>
@endpush

@endsection
