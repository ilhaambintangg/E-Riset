@extends('layouts.public')

@section('content')

<div class="min-h-screen bg-slate-50 relative overflow-hidden flex flex-col pt-20">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-br from-amber-100/50 to-transparent z-0"></div>
    
    <div class="flex items-center justify-center flex-1 py-20 px-4 relative z-10">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white p-10 max-w-lg w-full text-center animate-fade-in-up">
            
            <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-green-500/30">
                <i data-lucide="check-circle-2" class="w-12 h-12 text-white"></i>
            </div>

            <h2 class="text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Permohonan Terkirim!</h2>
            
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-8 text-left shadow-sm">
                <p class="text-sm text-slate-800 leading-relaxed mb-4">
                    <strong>Permohonan izin penelitian berhasil dikirim.</strong><br />
                    Silakan menunggu proses verifikasi dari petugas Pengadilan Tinggi Tanjungkarang.
                </p>
                
                @if(isset($registration_number) && $registration_number)
                <div class="bg-white rounded-xl p-4 border border-amber-200 text-center shadow-sm">
                    <p class="text-xs text-amber-600 font-bold mb-1.5 uppercase tracking-widest">Nomor Registrasi Anda</p>
                    <p class="text-2xl font-mono font-black text-slate-900 tracking-tight">{{ $registration_number }}</p>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Simpan nomor ini untuk mengecek status permohonan Anda.</p>
                </div>
                @endif
            </div>

            <div class="flex flex-col gap-3">
                <a href="/" class="flex items-center justify-center gap-2 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-700 hover:to-slate-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-slate-900/30">
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
