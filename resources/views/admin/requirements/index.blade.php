@extends('layouts.admin')

@section('title', 'Persyaratan Dokumen')
@section('header_title', 'Persyaratan Dokumen')

@section('content')

<div x-data="{ showModal: false, isEdit: false, form: { id: '', name: '', description: '', is_required: 1, order: 0 } }">
    
    <!-- Action Bar & Table Content (with animation) -->
    <div class="animate-fade-up">
    
    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-xs text-fg-body-subtle">Kelola persyaratan dokumen yang wajib diunggah oleh pemohon riset.</p>
        <button @click="isEdit = false; form = { id: '', name: '', description: '', is_required: 1, order: {{ count($requirements) + 1 }} }; showModal = true" 
                class="btn-brand btn-sm shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Persyaratan
        </button>
    </div>

    <!-- Table Card -->
    <div class="card-static overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-neutral-primary-soft text-fg-body-subtle font-bold uppercase tracking-wider text-[10px] border-b border-border-default">
                    <tr>
                        <th class="px-6 py-4">Nama Dokumen</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Sifat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default text-fg-body font-medium bg-white">
                    @forelse($requirements as $r)
                        <tr class="hover:bg-neutral-primary-soft transition-colors">
                            <td class="px-6 py-4 font-bold text-fg-heading">{{ $r->name }}</td>
                            <td class="px-6 py-4 max-w-xs truncate text-fg-body-subtle" title="{{ $r->description }}">{{ $r->description }}</td>
                            <td class="px-6 py-4">
                                @if($r->is_required)
                                    <span class="badge-danger">Wajib</span>
                                @else
                                    <span class="badge-neutral">Opsional</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button @click="isEdit = true; form = { id: '{{ $r->id }}', name: '{{ addslashes($r->name) }}', description: '{{ addslashes($r->description) }}', is_required: {{ $r->is_required }} }; showModal = true" 
                                        class="inline-flex items-center justify-center w-8 h-8 text-fg-body-subtle hover:text-fg-brand hover:bg-brand-soft rounded-default transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('requirements.destroy', $r->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus persyaratan ini?');">
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
                            <td colspan="4" class="px-6 py-12 text-center text-fg-body-subtle bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-neutral-tertiary mb-2"></i>
                                    <p class="font-semibold">Belum ada data persyaratan.</p>
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
                
                <form :action="isEdit ? '{{ url('admin/requirements') }}/' + form.id : '{{ route('requirements.store') }}'" method="POST" class="flex flex-col max-h-[90vh] m-0">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Header -->
                    <div class="flex justify-between items-center px-6 py-5 border-b border-border-default shrink-0">
                        <h3 class="text-h4 text-fg-heading m-0" x-text="isEdit ? 'Edit Persyaratan' : 'Tambah Persyaratan'"></h3>
                        <button type="button" @click="showModal = false" class="text-fg-body-subtle hover:text-fg-heading p-1.5 rounded-full hover:bg-neutral-primary-medium transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Scrollable Body -->
                    <div class="p-6 overflow-y-auto flex-1 space-y-4">
                        <div>
                            <label class="input-label text-xs">Nama Dokumen</label>
                            <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Proposal Penelitian" class="input-standard">
                        </div>
                        <div>
                            <label class="input-label text-xs">Deskripsi</label>
                            <textarea name="description" x-model="form.description" rows="3" placeholder="Tuliskan instruksi pengunggahan..." class="input-standard resize-none"></textarea>
                        </div>
                        <div>
                            <label class="input-label text-xs">Sifat Dokumen</label>
                            <select name="is_required" x-model="form.is_required" class="input-standard bg-white">
                                <option value="1">Wajib</option>
                                <option value="0">Opsional</option>
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
