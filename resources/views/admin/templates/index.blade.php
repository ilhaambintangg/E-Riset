@extends('layouts.admin')

@section('title', 'Template Surat Izin')
@section('header_title', 'Template Surat Izin')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-up">
    <!-- Form Upload -->
    <div class="lg:col-span-1">
        <div class="card-static overflow-hidden">
            <div class="px-6 py-5 border-b border-border-default bg-neutral-primary-soft">
                <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                    <i data-lucide="upload-cloud" class="w-4 h-4 text-brand-alt"></i> Upload Template Baru
                </h3>
            </div>
            <div class="p-6">
                <div class="mb-5 p-4 bg-brand-softer border border-border-brand-subtle rounded-default space-y-2">
                    <p class="text-xs font-bold text-fg-brand flex items-start gap-2 m-0">
                        <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                        Format yang didukung: .docx
                    </p>
                    <p class="text-[11px] text-fg-brand-strong leading-relaxed m-0 font-medium">Sistem menggunakan PHPWord. Gunakan variabel template seperti <code>${nama}</code>, <code>${nim}</code>, <code>${universitas}</code>, dan <code>${tujuan_penelitian}</code> di file Word Anda agar otomatis terisi data permohonan.</p>
                </div>

                <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="input-label text-xs">Jenis Template</label>
                        <select name="type" required class="w-full text-xs text-fg-body border border-border-default rounded-sm py-2 px-3 focus:outline-none focus:border-brand">
                            <option value="individu">Individu</option>
                            <option value="kelompok">Kelompok</option>
                        </select>
                    </div>
                    <div>
                        <label class="input-label text-xs">Pilih File Template (.docx)</label>
                        <input type="file" name="template" accept=".docx" required
                               class="w-full text-xs text-fg-body file:mr-3 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-[10px] file:font-bold file:bg-brand-soft file:text-fg-brand hover:file:bg-brand hover:file:text-white cursor-pointer">
                    </div>
                    <button type="submit" class="w-full btn-brand btn-base text-xs hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Upload & Aktifkan
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Template -->
    <div class="lg:col-span-2">
        <div class="card-static overflow-hidden h-full">
            <div class="px-6 py-5 border-b border-border-default bg-neutral-primary-soft">
                <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                    <i data-lucide="file-code" class="w-4 h-4 text-brand-alt"></i> Riwayat Template
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($templates as $t)
                        <div class="flex items-center justify-between p-4 rounded-default border {{ $t->is_active ? 'border-border-brand bg-brand-softer' : 'border-border-default bg-neutral-primary-soft' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-default {{ $t->is_active ? 'bg-brand text-white shadow-md' : 'bg-neutral-tertiary-soft text-fg-body-subtle' }} flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-fg-heading mb-1">
                                        {{ basename($t->file_path) }}
                                        <span class="{{ $t->type === 'kelompok' ? 'badge-info' : 'badge-neutral' }} ml-2 py-0.5">{{ ucfirst($t->type) }}</span>
                                        @if($t->is_active)
                                            <span class="badge-success ml-2 py-0.5">Aktif</span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-fg-body-subtle">Diunggah: {{ $t->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('templates.download', $t->id) }}" class="p-2 text-fg-body-subtle hover:text-fg-brand hover:bg-brand-soft rounded-default transition-colors" title="Download Template">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                                    <form action="{{ route('templates.destroy', $t->id) }}" method="POST" 
                                          onsubmit="return confirm('{{ $t->is_active ? 'Ini adalah template aktif. Yakin ingin menghapusnya? (Template cadangan lain akan otomatis aktif jika ada)' : 'Yakin ingin menghapus template ini?' }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-fg-body-subtle hover:text-fg-danger hover:bg-danger-soft rounded-default transition-colors" title="Hapus Template">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mx-auto mb-2"></i>
                            <p class="text-fg-body-subtle text-xs m-0">Belum ada template surat yang diunggah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
