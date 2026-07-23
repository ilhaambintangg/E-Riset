@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('header_title', 'Pengaturan Website')

@section('content')


<div class="max-w-4xl animate-fade-up">
    <div class="card-static overflow-hidden">
        <div class="px-6 py-5 border-b border-border-default bg-neutral-primary-soft">
            <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                <i data-lucide="settings" class="w-4 h-4 text-brand-alt"></i> Informasi Instansi
            </h3>
            <p class="text-xs text-fg-body-subtle mt-1 mb-0">Data ini akan ditampilkan di halaman publik (footer dan kontak).</p>
        </div>
        
        <div class="p-6 bg-white">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="input-label">Nama Instansi</label>
                        <input type="text" name="nama_instansi" value="{{ old('nama_instansi', $setting->nama_instansi) }}" required 
                               class="input-standard">
                    </div>
                    
                    <div>
                        <label class="input-label">Kode Nomor Surat (LETTER_CODE)</label>
                        <input type="text" name="letter_code" value="{{ old('letter_code', $setting->letter_code) }}" required 
                               class="input-standard" placeholder="Contoh: PAN.04/SKET.HM2.1.4">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="input-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" required 
                                  class="input-standard resize-none">{{ old('alamat', $setting->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="input-label">Email Instansi</label>
                        <input type="email" name="email" value="{{ old('email', $setting->email) }}" required 
                               class="input-standard">
                    </div>

                    <div>
                        <label class="input-label">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $setting->telepon) }}" required 
                               class="input-standard">
                    </div>

                    <div class="md:col-span-2">
                        <label class="input-label">Website Resmi</label>
                        <input type="url" name="website" value="{{ old('website', $setting->website) }}" required 
                               class="input-standard">
                    </div>

                    <div class="md:col-span-2">
                        <label class="input-label">Embed Google Maps (Iframe)</label>
                        <textarea name="google_maps" rows="4" required 
                                  class="input-standard font-mono text-xs text-fg-body-subtle resize-none">{{ old('google_maps', $setting->google_maps) }}</textarea>
                        <p class="text-[10px] text-fg-body-subtle mt-2 mb-0">Salin kode HTML iframe dari Google Maps &gt; Bagikan &gt; Sematkan Peta.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-border-default flex justify-end">
                    <button type="submit" class="btn-brand btn-base text-xs hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
