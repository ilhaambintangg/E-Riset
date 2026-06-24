@extends('layouts.admin')

@section('title', 'Data Panitera')
@section('header_title', 'Data Panitera')

@section('content')

<div x-data="{ showModal: false, isEdit: false, form: { id: '', nama_panitera: '', nip: '', jabatan: '', status_aktif: 1 } }">
    
    <!-- Action Bar & Table Content (with animation) -->
    <div class="animate-fade-up">
    
    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-xs text-fg-body-subtle">Kelola data pejabat penandatangan surat izin riset resmi.</p>
        <button @click="isEdit = false; form = { id: '', nama_panitera: '', nip: '', jabatan: '', status_aktif: 1 }; showModal = true" 
                class="btn-brand btn-sm shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Panitera
        </button>
    </div>

    <!-- Table Card -->
    <div class="card-static overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-neutral-primary-soft text-fg-body-subtle font-bold uppercase tracking-wider text-[10px] border-b border-border-default">
                    <tr>
                        <th class="px-6 py-4">Nama Panitera</th>
                        <th class="px-6 py-4">NIP</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                    @forelse($paniteras as $p)
                        <tr class="hover:bg-neutral-primary-soft transition-colors">
                            <td class="px-6 py-4 font-bold text-fg-heading">{{ $p->nama_panitera }}</td>
                            <td class="px-6 py-4 font-mono text-fg-brand">{{ $p->nip }}</td>
                            <td class="px-6 py-4">{{ $p->jabatan }}</td>
                            <td class="px-6 py-4">
                                @if($p->status_aktif)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-neutral">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button @click="isEdit = true; form = { id: '{{ $p->id }}', nama_panitera: '{{ addslashes($p->nama_panitera) }}', nip: '{{ $p->nip }}', jabatan: '{{ addslashes($p->jabatan) }}', status_aktif: {{ $p->status_aktif }} }; showModal = true" 
                                        class="inline-flex items-center justify-center w-8 h-8 text-fg-body-subtle hover:text-fg-brand hover:bg-brand-soft rounded-default transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('panitera.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-fg-body-subtle hover:text-fg-danger hover:bg-danger-soft rounded-default transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-fg-body-subtle bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mb-2"></i>
                                    <p class="font-semibold">Belum ada data panitera.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-neutral-secondary-strong/80 backdrop-blur-sm" @click="showModal = false"></div>

            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-lg bg-white shadow-xl rounded-base border border-border-default my-8 text-left transition-all transform flex flex-col max-h-[90vh] overflow-hidden">
                
                <form :action="isEdit ? '{{ url('admin/panitera') }}/' + form.id : '{{ route('panitera.store') }}'" method="POST" class="flex flex-col max-h-[90vh] m-0">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-5 border-b border-border-default shrink-0">
                        <h3 class="text-h4 text-fg-heading m-0" x-text="isEdit ? 'Edit Panitera' : 'Tambah Panitera'"></h3>
                        <button type="button" @click="showModal = false" class="text-fg-body-subtle hover:text-fg-heading p-1.5 rounded-full hover:bg-neutral-primary-medium transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Scrollable Body -->
                    <div class="p-6 overflow-y-auto flex-1 space-y-4">
                        <div>
                            <label class="input-label text-xs">Nama Lengkap</label>
                            <input type="text" name="nama_panitera" x-model="form.nama_panitera" required placeholder="Contoh: Drs. H. Nama Panitera, S.H., M.H." class="input-standard">
                        </div>
                        <div>
                            <label class="input-label text-xs">NIP</label>
                            <input type="text" name="nip" x-model="form.nip" required placeholder="Contoh: 196504121990031002" class="input-standard">
                        </div>
                        <div>
                            <label class="input-label text-xs">Jabatan</label>
                            <input type="text" name="jabatan" x-model="form.jabatan" required placeholder="Contoh: Panitera Muda Hukum" class="input-standard">
                        </div>
                        <div>
                            <label class="input-label text-xs">Status Pejabat</label>
                            <select name="status_aktif" x-model="form.status_aktif" class="input-standard bg-white">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-5 border-t border-border-default flex justify-end gap-3 shrink-0">
                        <button type="button" @click="showModal = false" class="btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn-brand btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
