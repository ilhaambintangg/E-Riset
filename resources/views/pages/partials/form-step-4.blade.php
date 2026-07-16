<!-- STEP 4: Ringkasan Sebelum Submit -->
<div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px] space-y-[24px]">
    <!-- Header Ringkasan -->
    <div class="flex items-start gap-[16px] pb-[16px] border-b border-border-default">
        <div class="w-[44px] h-[44px] bg-blue-50 rounded-[12px] flex items-center justify-center border border-blue-100 shrink-0 shadow-2xs">
            <i data-lucide="file-text" class="w-[22px] h-[22px] text-brand"></i>
        </div>
        <div>
            <h2 class="text-[18px] sm:text-[20px] font-heading font-black text-fg-heading leading-tight">Ringkasan Permohonan</h2>
            <p class="text-[12px] sm:text-[13px] text-fg-body-subtle font-medium mt-[2px]">
                Harap periksa kembali seluruh data yang Anda masukkan sebelum mengirim pengajuan.
            </p>
        </div>
    </div>

    <div class="space-y-[24px]">
        <!-- Kelompok 1: Identitas & Anggota -->
        <div class="bg-slate-50/70 border border-slate-200/80 rounded-[16px] p-[24px] sm:p-[32px] space-y-[20px]">
            <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] pb-[8px] flex items-center gap-2">
                <i data-lucide="user" class="w-[16px] h-[16px] text-brand"></i>
                Identitas & Anggota
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[16px] text-[14px]">
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Nama Lengkap</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.name || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">NIM / NPM</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.nim || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Kontak / HP</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.phone || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Email Aktif</span>
                    <strong class="text-fg-heading text-[14px] font-bold block text-brand" x-text="form.email || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Status Penelitian</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.is_group === 'kelompok' ? 'Penelitian Kelompok' : 'Penelitian Individu'"></strong>
                </div>
            </div>
        </div>

        <!-- Kelompok 2: Riset & Akademik -->
        <div class="bg-slate-50/70 border border-slate-200/80 rounded-[16px] p-[24px] sm:p-[32px] space-y-[20px]">
            <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] pb-[8px] flex items-center gap-2">
                <i data-lucide="compass" class="w-[16px] h-[16px] text-brand"></i>
                Riset & Akademik
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[16px] text-[14px]">
                <div class="sm:col-span-2">
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Asal Kampus / Fakultas / Prodi</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.university + ' · ' + form.faculty + ' · ' + form.study_program"></strong>
                </div>


                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Nomor Surat Pengantar</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.reference_letter_number || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Tanggal Surat Pengantar</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="formatDate(form.reference_letter_date)"></strong>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Judul Penelitian</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.research_title || '-'"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Tujuan Penelitian</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.research_type === 'Lainnya' ? form.custom_research_type : form.research_type"></strong>
                </div>
                <div>
                    <span class="text-[11px] text-fg-body-subtle/80 uppercase tracking-wider font-semibold block mb-[2px]">Lokasi Penelitian</span>
                    <strong class="text-fg-heading text-[14px] font-bold block" x-text="form.research_location === 'Lainnya' ? form.custom_research_location : form.research_location"></strong>
                </div>

            </div>
        </div>

        <!-- Kelompok 3: Anggota Kelompok (if Kelompok) -->
        <div class="bg-slate-50/70 border border-slate-200/80 rounded-[16px] p-[24px] sm:p-[32px] space-y-[12px]" x-show="form.is_group === 'kelompok'" x-transition>
            <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] pb-[8px] flex items-center gap-2">
                <i data-lucide="users" class="w-[16px] h-[16px] text-brand"></i>
                Anggota Kelompok
            </h3>
            <div class="divide-y divide-border-default/50 text-[14px]">
                <template x-for="(m, idx) in form.members" :key="idx">
                    <div class="py-[12px] flex justify-between items-center">
                        <span class="font-semibold text-fg-heading" x-text="(idx+1) + '. ' + m.name + (idx === 0 ? ' (Ketua)' : '')"></span>
                        <span class="text-fg-body-subtle font-medium" x-text="'(NPM: ' + m.npm + ')'"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Kelompok 4: Berkas Dokumen -->
        <div class="bg-slate-50/70 border border-slate-200/80 rounded-[16px] p-[24px] sm:p-[32px] space-y-[20px]">
            <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] pb-[8px] flex items-center gap-2">
                <i data-lucide="paperclip" class="w-[16px] h-[16px] text-brand"></i>
                Berkas Terlampir
            </h3>
            <div class="space-y-[12px]">
                <!-- Surat Pengantar Kampus -->
                <div class="flex items-center justify-between p-[16px] bg-white rounded-[12px] border border-slate-200/60 shadow-2xs hover:border-brand/35 transition-all duration-300">
                    <div class="flex items-center gap-[16px] min-w-0">
                        <div class="w-[40px] h-[40px] bg-emerald-50 rounded-[10px] flex items-center justify-center border border-emerald-100/50 shrink-0">
                            <i data-lucide="file-text" class="w-[20px] h-[20px] text-emerald-600"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] text-fg-body-subtle/80 uppercase tracking-wider block font-semibold mb-[2px]">Surat Pengantar Kampus</span>
                            <strong class="text-fg-heading text-[13px] font-bold truncate block max-w-[200px] sm:max-w-[450px]" x-text="files.surat_pengantar_kampus ? files.surat_pengantar_kampus.name : '-'"></strong>
                        </div>
                    </div>
                    <template x-if="files.surat_pengantar_kampus">
                        <button type="button" @click="previewFile('surat_pengantar_kampus')" class="w-[36px] h-[36px] rounded-full flex items-center justify-center text-fg-body hover:text-brand hover:bg-brand-softer transition-all shrink-0">
                            <i data-lucide="eye" class="w-[18px] h-[18px]"></i>
                        </button>
                    </template>
                </div>

                <!-- Proposal Penelitian -->
                <div class="flex items-center justify-between p-[16px] bg-white rounded-[12px] border border-slate-200/60 shadow-2xs hover:border-brand/35 transition-all duration-300">
                    <div class="flex items-center gap-[16px] min-w-0">
                        <div class="w-[40px] h-[40px] bg-emerald-50 rounded-[10px] flex items-center justify-center border border-emerald-100/50 shrink-0">
                            <i data-lucide="file-text" class="w-[20px] h-[20px] text-emerald-600"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] text-fg-body-subtle/80 uppercase tracking-wider block font-semibold mb-[2px]">Proposal Penelitian</span>
                            <strong class="text-fg-heading text-[13px] font-bold truncate block max-w-[200px] sm:max-w-[450px]" x-text="files.proposal_penelitian ? files.proposal_penelitian.name : '-'"></strong>
                        </div>
                    </div>
                    <template x-if="files.proposal_penelitian">
                        <button type="button" @click="previewFile('proposal_penelitian')" class="w-[36px] h-[36px] rounded-full flex items-center justify-center text-fg-body hover:text-brand hover:bg-brand-softer transition-all shrink-0">
                            <i data-lucide="eye" class="w-[18px] h-[18px]"></i>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Konfirmasi Checkbox -->
    <div class="mt-[32px] p-[16px] bg-amber-50/50 border border-amber-100 rounded-[12px] flex gap-[12px]">
        <input type="checkbox" id="declaration-check" x-model="declarationChecked" class="accent-brand w-[20px] h-[20px] mt-[2px] cursor-pointer">
        <label for="declaration-check" class="text-[13px] text-fg-heading leading-[1.6] cursor-pointer select-none">
            Saya menyatakan bahwa seluruh data yang saya isi di atas adalah <strong>benar, sah, dan sesuai</strong> dengan berkas aslinya untuk proses verifikasi.
        </label>
    </div>
    <p x-show="errors.declaration" class="text-[12px] text-fg-danger font-medium mt-[6px] ml-[32px]" x-text="errors.declaration"></p>
</div>
