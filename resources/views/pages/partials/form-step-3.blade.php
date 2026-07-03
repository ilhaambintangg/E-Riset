<!-- STEP 3: Dokumen Persyaratan -->
<div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px] space-y-[24px]">
    <h2 class="text-h4 text-fg-heading mb-[8px] flex items-center gap-[12px]">
        <div class="w-[32px] h-[32px] bg-warning-soft rounded-[10px] flex items-center justify-center border border-border-warning-subtle">
            <i data-lucide="upload" class="w-[16px] h-[16px] text-warning-strong"></i>
        </div>
        Dokumen Administrasi
    </h2>
    <p class="text-[13px] text-fg-body-subtle mb-[24px] ml-[44px] font-medium">
        Format PDF wajib diunggah · Maksimal 2 MB per file
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
        <!-- Surat Pengantar -->
        <div>
            <label class="input-label">Surat Pengantar dari Kampus <span class="text-fg-danger">*</span></label>
            <label class="flex items-center gap-[16px] cursor-pointer border-2 border-dashed rounded-[16px] p-[20px] transition-all duration-300 group"
                   :class="files.surat_pengantar_kampus ? 'border-brand bg-brand-softer' : errors.surat_pengantar_kampus ? 'border-danger bg-danger-soft' : 'border-border-default-strong hover:border-brand bg-neutral-primary hover:bg-neutral-primary-soft'">
                <div class="w-[48px] h-[48px] rounded-[12px] flex items-center justify-center shrink-0 transition-colors"
                     :class="files.surat_pengantar_kampus ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-medium text-fg-body group-hover:bg-brand-softer group-hover:text-brand'">
                    <i data-lucide="file-check-2" x-show="files.surat_pengantar_kampus" class="w-[24px] h-[24px]"></i>
                    <i data-lucide="upload-cloud" x-show="!files.surat_pengantar_kampus" class="w-[24px] h-[24px]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <template x-if="files.surat_pengantar_kampus">
                        <div>
                            <p class="text-[14px] text-brand-strong font-bold truncate mb-[2px]" x-text="files.surat_pengantar_kampus.name"></p>
                            <p class="text-[12px] text-brand font-medium" x-text="(files.surat_pengantar_kampus.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                        </div>
                    </template>
                    <template x-if="!files.surat_pengantar_kampus">
                        <div>
                            <p class="text-[14px] text-fg-heading font-bold mb-[2px] group-hover:text-brand transition-colors">Pilih File PDF</p>
                            <p class="text-[12px] text-fg-body-subtle">Maks. 2 MB</p>
                        </div>
                    </template>
                </div>
                <template x-if="files.surat_pengantar_kampus">
                    <button @click.prevent="clearFile('surat_pengantar_kampus')" class="text-fg-body hover:text-danger bg-white shadow-sm p-[8px] rounded-full transition-colors shrink-0">
                        <i data-lucide="trash-2" class="w-[16px] h-[16px]"></i>
                    </button>
                </template>
                <input type="file" accept=".pdf" @change="handleFileChange($event, 'surat_pengantar_kampus')" class="hidden" />
            </label>
            <p x-show="errors.surat_pengantar_kampus" class="text-[12px] text-fg-danger mt-[8px] font-medium animate-fade-in" x-text="errors.surat_pengantar_kampus"></p>
        </div>

        <!-- Proposal Penelitian -->
        <div>
            <label class="input-label">Proposal Penelitian <span class="text-fg-danger">*</span></label>
            <label class="flex items-center gap-[16px] cursor-pointer border-2 border-dashed rounded-[16px] p-[20px] transition-all duration-300 group"
                   :class="files.proposal_penelitian ? 'border-brand bg-brand-softer' : errors.proposal_penelitian ? 'border-danger bg-danger-soft' : 'border-border-default-strong hover:border-brand bg-neutral-primary hover:bg-neutral-primary-soft'">
                <div class="w-[48px] h-[48px] rounded-[12px] flex items-center justify-center shrink-0 transition-colors"
                     :class="files.proposal_penelitian ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-medium text-fg-body group-hover:bg-brand-softer group-hover:text-brand'">
                    <i data-lucide="file-check-2" x-show="files.proposal_penelitian" class="w-[24px] h-[24px]"></i>
                    <i data-lucide="upload-cloud" x-show="!files.proposal_penelitian" class="w-[24px] h-[24px]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <template x-if="files.proposal_penelitian">
                        <div>
                            <p class="text-[14px] text-brand-strong font-bold truncate mb-[2px]" x-text="files.proposal_penelitian.name"></p>
                            <p class="text-[12px] text-brand font-medium" x-text="(files.proposal_penelitian.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                        </div>
                    </template>
                    <template x-if="!files.proposal_penelitian">
                        <div>
                            <p class="text-[14px] text-fg-heading font-bold mb-[2px] group-hover:text-brand transition-colors">Pilih File PDF</p>
                            <p class="text-[12px] text-fg-body-subtle">Maks. 2 MB</p>
                        </div>
                    </template>
                </div>
                <template x-if="files.proposal_penelitian">
                    <button @click.prevent="clearFile('proposal_penelitian')" class="text-fg-body hover:text-danger bg-white shadow-sm p-[8px] rounded-full transition-colors shrink-0">
                        <i data-lucide="trash-2" class="w-[16px] h-[16px]"></i>
                    </button>
                </template>
                <input type="file" accept=".pdf" @change="handleFileChange($event, 'proposal_penelitian')" class="hidden" />
            </label>
            <p x-show="errors.proposal_penelitian" class="text-[12px] text-fg-danger mt-[8px] font-medium animate-fade-in" x-text="errors.proposal_penelitian"></p>
        </div>
    </div>
</div>
