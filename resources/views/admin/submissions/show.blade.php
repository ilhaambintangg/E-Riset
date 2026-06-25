@extends('layouts.admin')

@section('title', 'Detail Permohonan')
@section('header_title')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.submissions.index') }}" class="p-2 -ml-2 rounded-lg text-fg-body-subtle hover:text-fg-heading hover:bg-neutral-primary-medium transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    Detail Permohonan
</div>
@endsection

@section('content')

@php
    $statusConfig = [
        'Menunggu Verifikasi' => ['color' => 'text-fg-warning bg-warning-soft border-border-warning-subtle', 'icon' => 'clock', 'dot' => 'bg-warning'],
        'Sedang Diproses'     => ['color' => 'text-fg-info bg-info-soft border-border-info-subtle', 'icon' => 'loader', 'dot' => 'bg-info'],
        'Disetujui'           => ['color' => 'text-fg-success-strong bg-success-soft border-border-success-subtle', 'icon' => 'check-circle-2', 'dot' => 'bg-success'],
        'Ditolak'             => ['color' => 'text-fg-danger-strong bg-danger-soft border-border-danger-subtle', 'icon' => 'x-circle', 'dot' => 'bg-danger'],
    ];
    $cfg = $statusConfig[$submission->current_status] ?? ['color' => 'text-fg-body bg-neutral-tertiary-soft border-border-default', 'icon' => 'clock', 'dot' => 'bg-neutral-quaternary'];
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-fade-up">
    <!-- Left Column: Tabbed Details -->
    <div class="xl:col-span-2 space-y-6" x-data="{ activeTab: 'pemohon' }">
        
        <!-- Tabs Header -->
        <div class="flex border-b border-border-default bg-white rounded-t-base">
            <button @click="activeTab = 'pemohon'" 
                    :class="activeTab === 'pemohon' ? 'border-brand text-fg-brand font-bold bg-white' : 'border-transparent text-fg-body-subtle hover:text-fg-heading hover:bg-neutral-primary-medium'" 
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-all flex items-center gap-2 rounded-tl-base focus:outline-none">
                <i data-lucide="user" class="w-4 h-4"></i> Data Diri & Penelitian
            </button>
            <button @click="activeTab = 'dokumen'" 
                    :class="activeTab === 'dokumen' ? 'border-brand text-fg-brand font-bold bg-white' : 'border-transparent text-fg-body-subtle hover:text-fg-heading hover:bg-neutral-primary-medium'" 
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-all flex items-center gap-2 focus:outline-none">
                <i data-lucide="file-text" class="w-4 h-4"></i> Dokumen Lampiran & Preview
            </button>
        </div>

        <!-- Tab Content Card -->
        <div class="bg-white border border-t-0 border-border-default rounded-b-base shadow-sm p-6 sm:p-8">
            <!-- Tab 1: Profile and Research -->
            <div x-show="activeTab === 'pemohon'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-widest mb-1">Nomor Registrasi</p>
                        <h2 class="text-2xl font-bold text-fg-brand font-mono tracking-tight">{{ $submission->registration_number }}</h2>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border {{ $cfg['color'] }} font-bold text-xs self-start sm:self-center">
                        <div class="w-2.5 h-2.5 rounded-full {{ $cfg['dot'] }} animate-pulse"></div>
                        {{ $submission->current_status }}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Nama Lengkap Pemohon</p>
                        <p class="text-base font-bold text-fg-heading">{{ $submission->name }}</p>
                        <p class="text-sm text-fg-body-subtle mt-0.5 font-mono">NIM/NIDN: {{ $submission->nim }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Hubungi Pemohon</p>
                        <p class="text-sm font-semibold text-fg-heading flex items-center gap-2 mb-1">
                            <i data-lucide="mail" class="w-4 h-4 text-fg-body-subtle"></i> {{ $submission->email }}
                        </p>
                        <p class="text-sm font-semibold text-fg-heading flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-fg-body-subtle"></i> {{ $submission->phone }}
                        </p>
                    </div>
                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Institusi & Program Studi</p>
                        <p class="text-sm font-bold text-fg-heading"><i data-lucide="building-2" class="w-4 h-4 inline mr-1 text-brand-alt"></i> {{ $submission->university }}</p>
                        <p class="text-xs text-fg-body-subtle mt-1.5 ml-5">
                            Fakultas: <span class="font-semibold text-fg-body">{{ $submission->faculty }}</span> | 
                            Prodi: <span class="font-semibold text-fg-body">{{ $submission->study_program }}</span>
                        </p>
                    </div>
                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Surat Pengantar Kampus</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Nomor Surat Pengantar</p>
                                <p class="text-xs font-semibold text-fg-heading">{{ $submission->reference_letter_number ?? '-' }}</p>
                            </div>
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Tanggal Surat Pengantar</p>
                                <p class="text-xs font-semibold text-fg-heading">
                                    {{ $submission->reference_letter_date ? \Carbon\Carbon::parse($submission->reference_letter_date)->translatedFormat('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Tujuan Surat</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Jabatan Penerima</p>
                                <p class="text-xs font-semibold text-fg-heading">{{ $submission->recipient_position ?? '-' }}</p>
                            </div>
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Kota Tujuan</p>
                                <p class="text-xs font-semibold text-fg-heading">{{ $submission->destination_city ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Judul & Tujuan Penelitian</p>
                        <p class="text-sm font-bold text-fg-heading">{{ $submission->research_title ?? $submission->title }}</p>
                        <p class="text-xs text-fg-body-subtle mt-1">Jenis Penelitian: <span class="font-bold text-fg-heading">{{ $submission->research_type ?? 'Skripsi' }}</span></p>
                        <div class="mt-2 bg-neutral-primary-soft p-4 rounded-default border border-border-default text-xs text-fg-body leading-relaxed whitespace-pre-line">
                            {{ $submission->purpose }}
                        </div>
                    </div>

                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-1">Lokasi & Waktu Penelitian</p>
                        <div class="flex flex-col sm:flex-row gap-4 mt-2">
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default flex-1">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Tempat Penelitian</p>
                                <p class="text-xs font-semibold text-fg-heading">{{ $submission->research_location ?? $submission->location }}</p>
                            </div>
                            <div class="bg-neutral-primary-soft px-4 py-3 rounded-default border border-border-default flex-1">
                                <p class="text-[10px] text-fg-body-subtle font-bold uppercase mb-1">Rentang Tanggal</p>
                                <p class="text-xs font-semibold text-fg-heading">
                                    {{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($submission->end_date)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($submission->members && $submission->members->count() > 0)
                    <div class="sm:col-span-2 border-t border-border-default-subtle pt-6">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-2">Anggota Penelitian Kelompok</p>
                        <div class="overflow-x-auto border border-border-default rounded-default">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-neutral-primary-soft border-b border-border-default text-xs font-bold text-fg-heading">
                                        <th class="p-3">No</th>
                                        <th class="p-3">Nama Anggota</th>
                                        <th class="p-3">NIM / NPM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submission->members as $index => $m)
                                    <tr class="border-b border-border-default last:border-0 hover:bg-neutral-primary-soft/50 text-xs">
                                        <td class="p-3 font-semibold text-fg-heading">{{ $index + 1 }}</td>
                                        <td class="p-3 text-fg-heading font-medium">{{ $m->member_name }}</td>
                                        <td class="p-3 text-fg-body-subtle font-mono">{{ $m->member_npm }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tab 2: Document Lists & Preview -->
            @php
                $suratPengantar = $submission->documents->where('document_type', 'Surat Pengantar Kampus')->first();
                $proposal = $submission->documents->where('document_type', 'Proposal Penelitian')->first();
                $defaultPreview = '';
                if ($suratPengantar && Storage::disk('public')->exists($suratPengantar->file_path)) {
                    $defaultPreview = Storage::url($suratPengantar->file_path);
                } elseif ($proposal && Storage::disk('public')->exists($proposal->file_path)) {
                    $defaultPreview = Storage::url($proposal->file_path);
                }
            @endphp
            <div x-show="activeTab === 'dokumen'" x-cloak x-data="{ previewPdf: '{{ $defaultPreview }}' }" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    <!-- Left: Document Buttons -->
                    <div class="lg:col-span-2 space-y-4">
                        <p class="text-[10px] font-bold text-fg-body-subtle uppercase tracking-wider mb-2">Dokumen Terlampir</p>
                        
                        @if($suratPengantar)
                        <div :class="previewPdf === '{{ Storage::url($suratPengantar->file_path) }}' ? 'border-brand bg-brand-softer' : 'border-border-default bg-white'"
                             class="border rounded-default p-4 space-y-3 transition-all shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-sm bg-danger-soft text-fg-danger flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-fg-heading truncate">Surat Pengantar Kampus</p>
                                    <span class="text-[10px] text-fg-body-subtle truncate block">{{ $suratPengantar->file_name }}</span>
                                </div>
                            </div>
                            @if(Storage::disk('public')->exists($suratPengantar->file_path))
                            <div class="flex gap-2">
                                <button type="button" @click="previewPdf = '{{ Storage::url($suratPengantar->file_path) }}'" 
                                        class="flex-1 btn-brand btn-sm text-[11px] py-1.5 justify-center">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Preview
                                </button>
                                <a href="{{ Storage::url($suratPengantar->file_path) }}" download class="flex-1 btn-secondary btn-sm text-[11px] py-1.5 justify-center border-border-default text-fg-body hover:bg-neutral-primary-soft">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                </a>
                            </div>
                            @else
                            <div class="bg-danger-soft border border-border-danger-subtle p-3 rounded-default flex items-center gap-2">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-fg-danger-strong shrink-0"></i>
                                <span class="text-[11px] font-bold text-fg-danger-strong">Berkas tidak ditemukan di server</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($proposal)
                        <div :class="previewPdf === '{{ Storage::url($proposal->file_path) }}' ? 'border-brand bg-brand-softer' : 'border-border-default bg-white'"
                             class="border rounded-default p-4 space-y-3 transition-all shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-sm bg-brand-soft text-fg-brand flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-fg-heading truncate">Proposal Penelitian</p>
                                    <span class="text-[10px] text-fg-body-subtle truncate block">{{ $proposal->file_name }}</span>
                                </div>
                            </div>
                            @if(Storage::disk('public')->exists($proposal->file_path))
                            <div class="flex gap-2">
                                <button type="button" @click="previewPdf = '{{ Storage::url($proposal->file_path) }}'" 
                                        class="flex-1 btn-brand btn-sm text-[11px] py-1.5 justify-center">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Preview
                                </button>
                                <a href="{{ Storage::url($proposal->file_path) }}" download class="flex-1 btn-secondary btn-sm text-[11px] py-1.5 justify-center border-border-default text-fg-body hover:bg-neutral-primary-soft">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                </a>
                            </div>
                            @else
                            <div class="bg-danger-soft border border-border-danger-subtle p-3 rounded-default flex items-center gap-2">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-fg-danger-strong shrink-0"></i>
                                <span class="text-[11px] font-bold text-fg-danger-strong">Berkas tidak ditemukan di server</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if(!$suratPengantar && !$proposal)
                            <div class="text-center py-6 text-fg-body-subtle border border-dashed border-border-default rounded-default bg-neutral-primary-soft">
                                <i data-lucide="inbox" class="w-8 h-8 text-neutral-tertiary mx-auto mb-2"></i>
                                <p class="text-xs m-0">Tidak ada dokumen diunggah.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Iframe Preview Panel -->
                    <div class="lg:col-span-3 border border-border-default rounded-default overflow-hidden h-[450px] bg-neutral-primary-soft flex flex-col items-center justify-center relative shadow-sm">
                        <template x-if="previewPdf">
                            <iframe :src="previewPdf" class="w-full h-full border-0"></iframe>
                        </template>
                        <template x-if="!previewPdf">
                            <div class="text-center p-6 text-fg-body-subtle">
                                <i data-lucide="file" class="w-12 h-12 mx-auto mb-2 text-neutral-tertiary"></i>
                                <p class="font-medium text-xs">Pilih dokumen di sebelah kiri untuk melihat preview PDF</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Actions & Logs -->
    <div class="space-y-6">
        
        <!-- Status Update Card -->
        <div class="card-static overflow-hidden" x-data="{ status: '{{ $submission->current_status }}' }">
            <div class="px-6 py-5 border-b border-border-default bg-neutral-primary-soft">
                <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                    <i data-lucide="settings" class="w-4 h-4 text-brand-alt"></i> Proses Permohonan
                </h3>
            </div>
            <div class="p-6">
                @if($errors->any())
                <div class="mb-4 p-3 bg-danger-soft border border-border-danger-subtle rounded-default text-xs text-fg-danger-strong font-medium">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.submissions.status', $submission->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="input-label text-xs">Ubah Status</label>
                        <select name="status" x-model="status" class="input-standard py-2.5 text-xs bg-neutral-primary-soft">
                            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                            <option value="Sedang Diproses">Sedang Diproses (Buat Surat)</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- Required for 'Sedang Diproses' -->
                    <div x-show="status === 'Sedang Diproses'" x-collapse x-cloak>
                        <div class="p-4 bg-brand-softer border border-border-brand-subtle rounded-default space-y-4">
                            <p class="text-xs font-bold text-fg-brand flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5 text-brand-alt"></i>
                                Memilih status ini akan otomatis menghasilkan draf Surat Izin (Word) menggunakan template.
                            </p>
                            <div>
                                <label class="block text-xs font-bold text-fg-brand-strong mb-1.5">Pilih Panitera / Penandatangan</label>
                                <select name="panitera_id" class="w-full border border-border-brand-subtle rounded-sm px-3 py-2 text-xs text-fg-heading font-medium focus:ring-2 focus:ring-brand-soft outline-none bg-white">
                                    <option value="">-- Pilih --</option>
                                    @foreach($paniteras as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_panitera }} - {{ $p->jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-fg-brand-strong mb-1.5">Tanggal Surat</label>
                                <input type="date" name="letter_date" value="{{ date('Y-m-d') }}" class="w-full border border-border-brand-subtle rounded-sm px-3 py-2 text-xs text-fg-heading font-medium focus:ring-2 focus:ring-brand-soft outline-none bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Required for 'Disetujui' -->
                    <div x-show="status === 'Disetujui'" x-collapse x-cloak>
                        <div class="p-4 bg-success-soft border border-border-success-subtle rounded-default space-y-4">
                            <p class="text-xs font-bold text-fg-success-strong flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                                Upload file PDF surat izin resmi yang telah ditandatangani dan dicap.
                            </p>
                            <div>
                                <label class="block text-xs font-bold text-fg-success-strong mb-1.5">Upload Surat Izin (PDF)</label>
                                <input type="file" name="permit_file" accept=".pdf" class="w-full text-xs text-fg-body file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border-0 file:text-[10px] file:font-bold file:bg-success file:text-white hover:file:bg-success-strong cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="input-label text-xs">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Tulis catatan atau instruksi revisi untuk pemohon..." class="input-standard text-xs bg-neutral-primary-soft resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand hover:bg-brand-medium text-white font-bold py-3.5 rounded-default shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-xs">
                        Simpan Perubahan
                        <i data-lucide="save" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Download/Generate Letter Box -->
                @if($submission->current_status === 'Sedang Diproses')
                <div class="mt-6 pt-6 border-t border-border-default">
                    <p class="text-xs font-bold text-fg-heading mb-3">Draf Surat Izin (Word)</p>
                    <a href="{{ route('admin.submissions.download', $submission->id) }}" target="_blank" class="w-full btn-brand btn-sm text-xs py-3 justify-center">
                        <i data-lucide="download" class="w-4 h-4"></i> Download Draf Surat
                    </a>
                </div>
                @endif
                
                @if($submission->current_status === 'Disetujui' && $submission->permit_file_path)
                <div class="mt-6 pt-6 border-t border-border-default">
                    <p class="text-xs font-bold text-fg-heading mb-3">Surat Izin Resmi (PDF)</p>
                    <a href="{{ Storage::url($submission->permit_file_path) }}" target="_blank" class="w-full btn-brand btn-sm text-xs py-3 justify-center bg-success hover:bg-success-strong focus:ring-success-soft">
                        <i data-lucide="file-check" class="w-4 h-4"></i> Lihat Surat Izin Resmi
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- History Log Card -->
        <div class="card-static overflow-hidden">
            <div class="px-6 py-5 border-b border-border-default">
                <h3 class="text-sm font-bold text-fg-heading flex items-center gap-2 m-0">
                    <i data-lucide="history" class="w-4 h-4 text-brand-alt"></i> Riwayat Proses
                </h3>
            </div>
            <div class="p-6">
                @if($submission->statusLogs && $submission->statusLogs->count() > 0)
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-[2px] before:bg-border-default">
                        @foreach($submission->statusLogs as $log)
                            @php
                                $logCfg = $statusConfig[$log->status] ?? ['color' => 'text-fg-body bg-neutral-tertiary-soft border-border-default', 'icon' => 'clock', 'dot' => 'bg-neutral-quaternary'];
                            @endphp
                            <div class="relative">
                                <!-- Dot indicator -->
                                <div class="absolute -left-[22px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white {{ $logCfg['dot'] }} shadow-xs"></div>
                                
                                <div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-fg-heading">{{ $log->status }}</p>
                                        <time class="text-[9px] font-bold text-fg-body-subtle">{{ $log->created_at->format('d/m H:i') }}</time>
                                    </div>
                                    @if($log->notes)
                                        <p class="text-xs text-fg-body-subtle mt-1 bg-neutral-primary-soft p-2.5 rounded-sm border border-border-default-subtle leading-relaxed">{{ $log->notes }}</p>
                                    @endif
                                    <p class="text-[9px] text-fg-body-subtle mt-1">Oleh: {{ $log->admin->name ?? 'Sistem' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-fg-body-subtle text-center">Belum ada riwayat proses.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
