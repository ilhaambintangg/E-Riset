<!-- STEP 1: Identitas Pemohon & Anggota Penelitian -->
<div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px] space-y-[24px]">
    <!-- Identitas Pemohon -->
    <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
        <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
            <i data-lucide="user" class="w-[16px] h-[16px] text-brand"></i>
        </div>
        Identitas Pemohon
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-[40px]">
        <!-- Nama Lengkap -->
        <div class="md:col-span-2">
            <label class="input-label">Nama Lengkap <span class="text-fg-danger">*</span></label>
            <input type="text" x-model="form.name" @input="clearError('name')" placeholder="Nama Lengkap" 
                   class="input-standard"
                   :class="errors.name ? '!border-border-danger focus:!ring-danger' : ''">
            <p x-show="errors.name" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.name"></p>
        </div>


        <!-- NIM / NPM -->
        <div>
            <label class="input-label">NIM / NPM <span class="text-fg-danger">*</span></label>
            <div class="relative group">
                <i data-lucide="graduation-cap" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                <input type="text" x-model="form.nim" @input="clearError('nim')" placeholder="Nomor Induk Mahasiswa" 
                       class="input-standard pl-[44px]"
                       :class="errors.nim ? '!border-border-danger focus:!ring-danger' : ''">
            </div>
            <p x-show="errors.nim" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.nim"></p>
        </div>

        <!-- No HP -->
        <div>
            <label class="input-label">Nomor HP / WhatsApp <span class="text-fg-danger">*</span></label>
            <div class="relative group">
                <i data-lucide="phone" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                <input type="tel" x-model="form.phone" @input="clearError('phone')" placeholder="08xxxxxxxxxx" 
                       class="input-standard pl-[44px]"
                       :class="errors.phone ? '!border-border-danger focus:!ring-danger' : ''">
            </div>
            <p x-show="errors.phone" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.phone"></p>
        </div>

        <!-- Email -->
        <div class="md:col-span-2">
            <label class="input-label">Alamat Email <span class="text-fg-danger">*</span></label>
            <div class="relative group">
                <i data-lucide="mail" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                <input type="email" x-model="form.email" @input="clearError('email')" placeholder="contoh@email.com" 
                       class="input-standard pl-[44px]"
                       :class="errors.email ? '!border-border-danger focus:!ring-danger' : ''">
            </div>
            <p x-show="errors.email" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.email"></p>
        </div>
    </div>

    <!-- Status & Anggota Penelitian -->
    <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
        <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
            <i data-lucide="users" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
        </div>
        Status & Anggota Penelitian
    </h2>

    <div class="mb-[20px]">
        <label class="input-label">Status Penelitian <span class="text-fg-danger">*</span></label>
        <div class="flex gap-[16px] mb-4">
            <label class="flex items-center gap-[12px] cursor-pointer px-[16px] py-[12px] rounded-[10px] border flex-1 transition-all"
                   :class="form.is_group === 'individu' ? 'border-brand bg-brand-softer text-brand-strong' : 'border-border-default-medium bg-neutral-secondary-medium hover:border-border-default-strong text-fg-body'">
                <input type="radio" x-model="form.is_group" value="individu" class="accent-brand w-[16px] h-[16px]">
                <span class="text-[14px] font-medium">Penelitian Individu</span>
            </label>
            <label class="flex items-center gap-[12px] cursor-pointer px-[16px] py-[12px] rounded-[10px] border flex-1 transition-all"
                   :class="form.is_group === 'kelompok' ? 'border-brand bg-brand-softer text-brand-strong' : 'border-border-default-medium bg-neutral-secondary-medium hover:border-border-default-strong text-fg-body'">
                <input type="radio" x-model="form.is_group" value="kelompok" class="accent-brand w-[16px] h-[16px]">
                <span class="text-[14px] font-medium">Penelitian Kelompok</span>
            </label>
        </div>

        <!-- Dynamic Members List -->
        <div x-show="form.is_group === 'kelompok'" x-transition x-cloak class="space-y-[16px] bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default mt-[16px]">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-[14px] text-fg-heading">Daftar Anggota Kelompok</h4>
                    <p class="text-[12px] text-fg-body-subtle">Masukkan data nama dan NPM seluruh anggota kelompok Anda.</p>
                </div>
                <button type="button" @click="addMember()" class="btn-secondary py-[8px] px-[16px] text-[13px] flex items-center gap-[6px] border-brand text-brand hover:bg-brand-softer shadow-sm">
                    <i data-lucide="plus" class="w-[14px] h-[14px]"></i>
                    Tambah Anggota
                </button>
            </div>
            
            <div class="overflow-hidden border border-border-default rounded-[12px] bg-white">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-neutral-primary-soft border-b border-border-default text-[13px] font-bold text-fg-heading">
                            <th class="p-[12px] pl-[16px]">Nama Lengkap</th>
                            <th class="p-[12px]">NIM / NPM</th>
                            <th class="p-[12px] text-center w-[80px]">Hapus / Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(member, index) in form.members" :key="index">
                            <tr class="border-b border-border-default last:border-0" :class="index === 0 ? 'bg-neutral-primary-soft/30' : ''">
                                <td class="p-[8px] pl-[16px]">
                                    <input type="text" x-model="member.name" placeholder="Nama Lengkap Anggota" 
                                           class="input-standard py-[8px] text-[14px] bg-white disabled:bg-neutral-primary-soft disabled:text-fg-body-subtle disabled:border-dashed disabled:cursor-not-allowed" 
                                           :disabled="index === 0" required>
                                </td>
                                <td class="p-[8px]">
                                    <input type="text" x-model="member.npm" placeholder="NPM Anggota" 
                                           class="input-standard py-[8px] text-[14px] bg-white disabled:bg-neutral-primary-soft disabled:text-fg-body-subtle disabled:border-dashed disabled:cursor-not-allowed" 
                                           :disabled="index === 0" required>
                                </td>
                                <td class="p-[8px] text-center">
                                    <template x-if="index === 0">
                                        <span class="text-[11px] font-bold text-brand bg-brand-softer px-[8px] py-[4px] rounded-[6px] border border-brand-subtle">Ketua</span>
                                    </template>
                                    <template x-if="index > 0">
                                        <button type="button" @click="removeMember(index)" 
                                                class="text-fg-body hover:text-danger p-[8px] rounded-full hover:bg-danger-soft transition-colors inline-flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-[16px] h-[16px]"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <template x-if="form.members.length === 0">
                            <tr>
                                <td colspan="3" class="p-[24px] text-center text-[13px] text-fg-body-subtle font-medium">
                                    Belum ada anggota. Klik "Tambah Anggota" di atas untuk menambahkan.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <p x-show="errors.members" class="text-[12px] text-fg-danger font-medium mt-[4px]" x-text="errors.members"></p>
        </div>
    </div>
</div>
