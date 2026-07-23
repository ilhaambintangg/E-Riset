@extends('layouts.admin')

@push('styles')
<style>
    /* Styling to make docx-preview look like real page pages */
    #docx-preview-container {
        font-family: 'Times New Roman', Times, serif;
    }
    #docx-preview-container .docx-wrapper {
        background: transparent !important;
        padding: 0 !important;
    }
    #docx-preview-container .docx {
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
</style>
@endpush

@section('title', 'Template Surat Izin')
@section('header_title', 'Template Surat Izin')

@section('content')

<!-- Load JSZip & Docx-preview from CDN for client-side Word document preview -->
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx-preview@0.1.18/dist/docx-preview.min.js"></script>

<div x-data="{ 
    isPreviewOpen: false, 
    previewTitle: '', 
    isPreviewLoading: false,
    institution_type: 'PT',
    template_type: 'individu',
    
    setUploadCategory(inst, type) {
        this.institution_type = inst;
        this.template_type = type;
        this.$refs.fileInput.focus();
        this.$refs.fileInput.click();
    },
    
    previewTemplate(url, filename) {
        this.previewTitle = filename;
        this.isPreviewOpen = true;
        this.isPreviewLoading = true;
        
        let container = document.getElementById('docx-preview-container');
        if (container) {
            container.innerHTML = '';
        }
        
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load document');
                return response.arrayBuffer();
            })
            .then(arrayBuffer => {
                if (container) {
                    docx.renderAsync(arrayBuffer, container)
                        .then(() => {
                            this.isPreviewLoading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            container.innerHTML = '<div class=\'text-center py-8 text-fg-danger-strong font-medium text-xs\'>Gagal memproses pratinjau dokumen.</div>';
                            this.isPreviewLoading = false;
                        });
                }
            })
            .catch(err => {
                console.error(err);
                if (container) {
                    container.innerHTML = '<div class=\'text-center py-8 text-fg-danger-strong font-medium text-xs\'>Gagal mengunduh berkas template.</div>';
                }
                this.isPreviewLoading = false;
            });
    }
}" class="grid grid-cols-1 lg:grid-cols-4 gap-6 animate-fade-up">

    <!-- Form Upload (Left Panel) -->
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
                        Informasi Otomatisasi
                    </p>
                    <p class="text-[11px] text-fg-brand-strong leading-relaxed m-0 font-medium">
                        Template akan digunakan secara otomatis sesuai tujuan penelitian (PT/PN) dan jenis permohonan (Individu/Kelompok).
                    </p>
                </div>

                @if($errors->any())
                <div class="mb-4 p-3 bg-danger-soft border border-border-danger-subtle rounded-default text-xs text-fg-danger-strong font-medium">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="input-label text-xs">Tujuan Surat</label>
                        <select name="institution_type" x-model="institution_type" required class="input-standard py-2 text-xs bg-white border border-border-default rounded-sm w-full">
                            <option value="PT">Pengadilan Tinggi (PT)</option>
                            <option value="PN">Pengadilan Negeri (PN)</option>
                        </select>
                    </div>
                    <div>
                        <label class="input-label text-xs">Jenis Template</label>
                        <select name="template_type" x-model="template_type" required class="input-standard py-2 text-xs bg-white border border-border-default rounded-sm w-full">
                            <option value="individu">Individu</option>
                            <option value="kelompok">Kelompok</option>
                        </select>
                    </div>
                    <div>
                        <label class="input-label text-xs">Pilih File Template (.docx)</label>
                        <input type="file" name="template" accept=".docx" x-ref="fileInput" required
                               class="w-full text-xs text-fg-body file:mr-3 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-[10px] file:font-bold file:bg-brand-soft file:text-fg-brand hover:file:bg-brand hover:file:text-white cursor-pointer">
                        <p class="text-[10px] text-fg-body-subtle mt-1.5">Format: DOCX | Maks: 5 MB</p>
                    </div>
                    <button type="submit" class="w-full btn-brand btn-base text-xs hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Upload & Aktifkan
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat & Daftar Template (Right Panel) -->
    <div class="lg:col-span-3">
        <div class="card-static overflow-hidden">
            <div class="px-6 py-5 border-b border-border-default bg-neutral-primary-soft">
                <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                    <i data-lucide="file-code" class="w-4 h-4 text-brand-alt"></i> Riwayat Template
                </h3>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-default bg-neutral-primary-soft text-[10px] font-bold uppercase tracking-wider text-fg-body-subtle">
                                <th class="py-3 px-6">Kategori</th>
                                <th class="py-3 px-6">Nama File</th>
                                <th class="py-3 px-6 text-center">Versi</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6">Tanggal Upload</th>
                                <th class="py-3 px-6">Diunggah Oleh</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-default">
                            @forelse($templates as $t)
                                <tr class="hover:bg-neutral-primary-soft/50 transition-colors {{ $t->is_active ? 'bg-brand-softer/10' : '' }}">
                                    <td class="py-3.5 px-6 font-bold text-xs text-fg-heading">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded-sm text-[10px] font-bold {{ $t->institution_type === 'PT' ? 'bg-brand text-white shadow-xs' : 'bg-neutral-tertiary-soft text-fg-body-subtle' }}">
                                                {{ $t->institution_type }}
                                            </span>
                                            <span class="text-fg-body-subtle font-medium">-</span>
                                            <span class="text-fg-heading">{{ ucfirst($t->template_type) }}</span>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-fg-body font-medium truncate max-w-[200px]" title="{{ $t->name }}">
                                        {{ $t->name }}
                                    </td>
                                    <td class="py-3.5 px-6 text-xs font-bold text-fg-body-subtle text-center">
                                        v{{ $t->version }}
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        @if($t->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-soft text-fg-success-strong">
                                                🟢 Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-neutral-secondary-medium text-fg-body-subtle">
                                                ⚪ Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-fg-body-subtle font-medium">
                                        {{ $t->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-3.5 px-6 text-xs text-fg-body font-medium">
                                        {{ $t->uploader->name ?? 'Sistem' }}
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <div class="flex items-center justify-center gap-1">
                                            <!-- Preview -->
                                            <button type="button" 
                                                    @click="previewTemplate('{{ Storage::url($t->file_path) }}', '{{ addslashes($t->name) }}')"
                                                    class="p-1.5 text-fg-body-subtle hover:text-brand hover:bg-brand-soft rounded-default transition-all" 
                                                    title="Pratinjau Template">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            
                                            <!-- Download -->
                                            <a href="{{ route('templates.download', $t->id) }}" 
                                               class="p-1.5 text-fg-body-subtle hover:text-brand hover:bg-brand-soft rounded-default transition-all" 
                                               title="Unduh (.docx)">
                                                <i data-lucide="download" class="w-4 h-4"></i>
                                            </a>

                                            <!-- Aktifkan -->
                                            @if(!$t->is_active)
                                                <form action="{{ route('templates.activate', $t->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="p-1.5 text-fg-body-subtle hover:text-success hover:bg-success-soft rounded-default transition-all" 
                                                            title="Aktifkan Template">
                                                        <i data-lucide="play" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Ganti Template -->
                                            <button type="button"
                                                    @click="setUploadCategory('{{ $t->institution_type }}', '{{ $t->template_type }}')"
                                                    class="p-1.5 text-fg-body-subtle hover:text-brand hover:bg-brand-soft rounded-default transition-all" 
                                                    title="Unggah Versi Baru (Ganti)">
                                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                            </button>

                                            <!-- Hapus -->
                                            <form action="{{ route('templates.destroy', $t->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('{{ $t->is_active ? 'Ini adalah template aktif. Menghapusnya akan otomatis mengaktifkan template cadangan terbaru pada kategori ini (jika ada). Lanjutkan?' : 'Yakin ingin menghapus template ini?' }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-fg-body-subtle hover:text-fg-danger hover:bg-danger-soft rounded-default transition-all" 
                                                        title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-fg-body-subtle text-xs">
                                        <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mx-auto mb-2"></i>
                                        Belum ada template surat yang diunggah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="isPreviewOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fade-in"
         x-cloak
         x-transition
         @keydown.escape.window="isPreviewOpen = false">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col overflow-hidden" 
             @click.away="isPreviewOpen = false">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-border-default flex items-center justify-between bg-neutral-primary-soft">
                <div class="flex items-center gap-2">
                    <i data-lucide="eye" class="w-5 h-5 text-brand"></i>
                    <h3 class="text-sm font-bold text-fg-heading" x-text="'Preview: ' + previewTitle"></h3>
                </div>
                <button @click="isPreviewOpen = false" class="text-fg-body hover:text-fg-heading transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6 overflow-y-auto flex-1 bg-neutral-primary-soft flex justify-center">
                <div x-show="isPreviewLoading" class="flex flex-col items-center justify-center py-20 w-full">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand mb-2"></div>
                    <p class="text-xs text-fg-body-subtle font-medium">Memuat pratinjau template...</p>
                </div>
                <div x-show="!isPreviewLoading" class="w-full max-w-[21cm]">
                    <div id="docx-preview-container" class="bg-white border border-border-default rounded-default shadow-sm p-8 min-h-[300px]"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
