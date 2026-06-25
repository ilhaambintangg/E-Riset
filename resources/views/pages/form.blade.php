@extends('layouts.public')

@section('content')

<div class="min-h-screen bg-neutral-primary-soft relative overflow-hidden flex flex-col pt-[100px] sm:pt-[120px]" x-data="submissionForm()">
    
    <div class="flex-1 pb-[80px] container-standard relative z-10 max-w-[800px] mx-auto w-full">
        <div class="mt-[20px]">
            <!-- Header -->
            <div class="text-center mb-[40px] animate-fade-up">
                <div class="inline-flex items-center gap-[8px] bg-brand-softer border border-brand-subtle px-[16px] py-[8px] rounded-full mb-[16px] shadow-sm">
                    <i data-lucide="scale" class="w-[16px] h-[16px] text-brand"></i>
                    <span class="text-brand font-bold text-[12px] tracking-widest uppercase">E-RISET</span>
                </div>
                <h1 class="text-h2 text-fg-heading mb-[8px]">
                    Formulir Pengajuan Izin Penelitian
                </h1>
                <p class="text-[15px] text-fg-body max-w-[600px] mx-auto opacity-90">
                    Lengkapi semua data administrasi di bawah ini untuk menghasilkan surat izin otomatis.
                </p>
            </div>

            <!-- Global Error -->
            <div x-show="errors._global" x-transition x-cloak class="flex items-center gap-[12px] bg-danger-soft border border-border-danger-subtle rounded-[12px] p-[16px] mb-[24px] text-fg-danger-strong shadow-sm animate-fade-in">
                <i data-lucide="alert-circle" class="w-[20px] h-[20px] shrink-0"></i>
                <span class="text-[14px] font-medium" x-text="errors._global"></span>
            </div>

            <!-- Success State (Will redirect, but kept as fallback) -->
            <div x-show="success" x-transition x-cloak class="bg-white rounded-[24px] shadow-lg border border-border-default p-[48px] text-center animate-fade-up">
                <div class="w-[96px] h-[96px] bg-success-soft rounded-full flex items-center justify-center mx-auto mb-[32px] border-[4px] border-white shadow-md">
                    <i data-lucide="check-circle-2" class="w-[48px] h-[48px] text-success-strong"></i>
                </div>
                <h2 class="font-heading font-bold text-[32px] text-fg-heading mb-[16px]">Permohonan Terkirim!</h2>
            </div>

            <!-- Multi-Step Form -->
            <div x-show="!success" x-transition class="bg-white rounded-[24px] shadow-lg border border-border-default overflow-hidden animate-fade-up">
                
                <!-- Stepper Header -->
                <div class="bg-neutral-primary-soft border-b border-border-default px-[24px] sm:px-[40px] py-[32px]">
                    <div class="flex items-center justify-between mb-[16px]">
                        <h3 class="font-heading font-bold text-[18px] text-fg-heading" x-text="'Langkah ' + step + ' dari 4'"></h3>
                        <span class="text-[13px] font-bold text-brand" x-text="stepTitle()"></span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full h-[6px] bg-neutral-secondary-medium rounded-full overflow-hidden">
                        <div class="h-full bg-brand transition-all duration-500 ease-out" :style="'width: ' + ((step / 4) * 100) + '%'"></div>
                    </div>
                    
                    <!-- Steps Indicator -->
                    <div class="flex justify-between mt-[16px]">
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 1 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step > 1 ? 'bg-brand text-white' : (step === 1 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body')">
                                <i x-show="step > 1" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 1">1</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step >= 1 ? 'text-brand' : 'text-fg-body-subtle'">Identitas & Anggota</span>
                        </div>
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 2 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step > 2 ? 'bg-brand text-white' : (step === 2 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body')">
                                <i x-show="step > 2" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 2">2</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step >= 2 ? 'text-brand' : 'text-fg-body-subtle'">Riset & Akademik</span>
                        </div>
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 3 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step > 3 ? 'bg-brand text-white' : (step === 3 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body')">
                                <i x-show="step > 3" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 3">3</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step >= 3 ? 'text-brand' : 'text-fg-body-subtle'">Upload Dokumen</span>
                        </div>
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 4 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step === 4 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body'">
                                <span>4</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step === 4 ? 'text-brand' : 'text-fg-body-subtle'">Review</span>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-[24px] sm:p-[40px]" novalidate>
                    
                    <!-- STEP 1: Identitas Pemohon & Anggota Penelitian -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
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

                    <!-- STEP 2: Riset & Akademik -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <!-- Informasi Akademik -->
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                                <i data-lucide="building-2" class="w-[16px] h-[16px] text-brand"></i>
                            </div>
                            Informasi Akademik
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-[40px]">
                            <!-- Universitas -->
                            <div class="md:col-span-2">
                                <label class="input-label">Universitas / Instansi <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.university" @input="clearError('university')" placeholder="Nama Universitas / Instansi Lengkap" 
                                       class="input-standard"
                                       :class="errors.university ? '!border-border-danger focus:!ring-danger' : ''">
                                <p x-show="errors.university" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.university"></p>
                            </div>

                            <!-- Fakultas -->
                            <div>
                                <label class="input-label">Fakultas <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.faculty" @input="clearError('faculty')" placeholder="Nama Fakultas" 
                                       class="input-standard"
                                       :class="errors.faculty ? '!border-border-danger focus:!ring-danger' : ''">
                                <p x-show="errors.faculty" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.faculty"></p>
                            </div>

                            <!-- Program Studi -->
                            <div>
                                <label class="input-label">Program Studi <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.study_program" @input="clearError('study_program')" placeholder="Nama Program Studi" 
                                       class="input-standard"
                                       :class="errors.study_program ? '!border-border-danger focus:!ring-danger' : ''">
                                <p x-show="errors.study_program" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.study_program"></p>
                            </div>
                        </div>

                        <!-- Tujuan Surat & Lokasi -->
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                                <i data-lucide="map-pin" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
                            </div>
                            Tujuan Surat & Lokasi
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-[40px]">
                            <!-- Jabatan Tujuan Surat -->
                            <div>
                                <label class="input-label">Jabatan Tujuan Surat <span class="text-fg-danger">*</span></label>
                                <select x-model="form.recipient_position" @change="clearError('recipient_position')" class="input-standard" :class="errors.recipient_position ? '!border-border-danger' : ''">
                                    <option value="" disabled selected>Pilih Jabatan...</option>
                                    <option value="Rektor">Rektor</option>
                                    <option value="Dekan">Dekan</option>
                                    <option value="Ketua Program Studi">Ketua Program Studi</option>
                                    <option value="Direktur Program Pascasarjana">Direktur Program Pascasarjana</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <p x-show="errors.recipient_position" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.recipient_position"></p>
                            </div>

                            <!-- Kota Tujuan -->
                            <div>
                                <label class="input-label">Kota Tujuan Surat <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.destination_city" @input="clearError('destination_city')" placeholder="Contoh: Bandar Lampung" 
                                       class="input-standard" :class="errors.destination_city ? '!border-border-danger' : ''">
                                <p x-show="errors.destination_city" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.destination_city"></p>
                            </div>

                            <!-- Custom Jabatan (Shown when "Lainnya" is selected) -->
                            <div class="md:col-span-2" x-show="form.recipient_position === 'Lainnya'" x-transition x-cloak>
                                <label class="input-label">Masukkan Jabatan <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.custom_recipient_position" @input="clearError('custom_recipient_position')" placeholder="Contoh: Kepala Program Magister Hukum" 
                                       class="input-standard" :class="errors.custom_recipient_position ? '!border-border-danger' : ''">
                                <p x-show="errors.custom_recipient_position" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.custom_recipient_position"></p>
                            </div>
                        </div>

                        <!-- Surat Pengantar Kampus -->
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                                <i data-lucide="file-check-2" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
                            </div>
                            Surat Pengantar Kampus
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-[40px]">
                            <!-- Nomor Surat Pengantar -->
                            <div>
                                <label class="input-label">Nomor Surat Pengantar Kampus <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.reference_letter_number" @input="clearError('reference_letter_number')" placeholder="Contoh: 68/021003/PL/VI/2026" 
                                       class="input-standard" :class="errors.reference_letter_number ? '!border-border-danger' : ''">
                                <p x-show="errors.reference_letter_number" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.reference_letter_number"></p>
                            </div>

                            <!-- Tanggal Surat Pengantar -->
                            <div>
                                <label class="input-label">Tanggal Surat Pengantar <span class="text-fg-danger">*</span></label>
                                <input type="date" x-model="form.reference_letter_date" @change="clearError('reference_letter_date')" 
                                       class="input-standard" :class="errors.reference_letter_date ? '!border-border-danger' : ''">
                                <p x-show="errors.reference_letter_date" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.reference_letter_date"></p>
                            </div>
                        </div>

                        <!-- Informasi Penelitian -->
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                                <i data-lucide="file-text" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
                            </div>
                            Informasi Penelitian
                        </h2>

                        <div class="space-y-[24px]">
                            <!-- Judul Penelitian -->
                            <div>
                                <label class="input-label">Judul Penelitian <span class="text-fg-danger">*</span></label>
                                <textarea rows="2" x-model="form.research_title" @input="clearError('research_title')" placeholder="Masukkan judul penelitian sesuai proposal..." 
                                          class="input-standard resize-none" :class="errors.research_title ? '!border-border-danger' : ''"></textarea>
                                <p x-show="errors.research_title" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_title"></p>
                            </div>

                            <!-- Tujuan Penelitian -->
                            <div>
                                <label class="input-label">Tujuan Penelitian <span class="text-fg-danger">*</span></label>
                                <textarea rows="2" x-model="form.purpose" @input="clearError('purpose')" placeholder="Jelaskan tujuan utama penelitian dilakukan di instansi ini..." 
                                          class="input-standard resize-none" :class="errors.purpose ? '!border-border-danger' : ''"></textarea>
                                <p x-show="errors.purpose" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.purpose"></p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                                <!-- Lokasi Penelitian -->
                                <div>
                                    <label class="input-label">Lokasi Penelitian <span class="text-fg-danger">*</span></label>
                                    <select x-model="form.research_location" @change="clearError('research_location')" class="input-standard" :class="errors.research_location ? '!border-border-danger' : ''">
                                        <option value="" disabled selected>Pilih Lokasi...</option>
                                        <option value="Pengadilan Tinggi Tanjungkarang">Pengadilan Tinggi Tanjungkarang</option>
                                        <option value="Pengadilan Negeri Tanjungkarang">Pengadilan Negeri Tanjungkarang</option>
                                        <option value="Pengadilan Negeri Kalianda">Pengadilan Negeri Kalianda</option>
                                        <option value="Pengadilan Negeri Kotabumi">Pengadilan Negeri Kotabumi</option>
                                        <option value="Pengadilan Negeri Metro">Pengadilan Negeri Metro</option>
                                        <option value="Pengadilan Negeri Menggala">Pengadilan Negeri Menggala</option>
                                        <option value="Pengadilan Negeri Gunung Sugih">Pengadilan Negeri Gunung Sugih</option>
                                        <option value="Pengadilan Negeri Blambangan Umpu">Pengadilan Negeri Blambangan Umpu</option>
                                        <option value="Pengadilan Negeri Liwa">Pengadilan Negeri Liwa</option>
                                        <option value="Pengadilan Negeri Sukadana">Pengadilan Negeri Sukadana</option>
                                        <option value="Lainnya">Lainnya (Tulis Custom)</option>
                                    </select>
                                    <p x-show="errors.research_location" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_location"></p>
                                </div>

                                <!-- Jenis Penelitian -->
                                <div>
                                    <label class="input-label">Jenis Penelitian <span class="text-fg-danger">*</span></label>
                                    <select x-model="form.research_type" @change="clearError('research_type')" class="input-standard" :class="errors.research_type ? '!border-border-danger' : ''">
                                        <option value="" disabled selected>Pilih Jenis Penelitian...</option>
                                        <option value="Skripsi">Skripsi</option>
                                        <option value="Tesis">Tesis</option>
                                        <option value="Disertasi">Disertasi</option>
                                        <option value="Penelitian Akademik">Penelitian Akademik</option>
                                        <option value="Penelitian Lainnya">Penelitian Lainnya</option>
                                    </select>
                                    <p x-show="errors.research_type" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_type"></p>
                                </div>

                                <!-- Custom Lokasi (Shown when "Lainnya" is selected) -->
                                <div class="md:col-span-2" x-show="form.research_location === 'Lainnya'" x-transition x-cloak>
                                    <label class="input-label">Masukkan Lokasi Penelitian <span class="text-fg-danger">*</span></label>
                                    <input type="text" x-model="form.custom_research_location" @input="clearError('custom_research_location')" placeholder="Contoh: Pengadilan Agama Tanjungkarang" 
                                           class="input-standard" :class="errors.custom_research_location ? '!border-border-danger' : ''">
                                    <p x-show="errors.custom_research_location" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.custom_research_location"></p>
                                </div>
                            </div>

                            <!-- Periode Estimasi Penelitian -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <div class="md:col-span-2">
                                    <h4 class="font-bold text-[14px] text-fg-heading mb-[4px]">Periode Penelitian</h4>
                                    <p class="text-[13px] text-fg-body-subtle mb-[16px]">Tentukan estimasi waktu pelaksanaan riset.</p>
                                </div>
                                <div>
                                    <label class="input-label">Tanggal Mulai <span class="text-fg-danger">*</span></label>
                                    <input type="date" x-model="form.start_date" @change="clearError('start_date')" 
                                           class="input-standard" :class="errors.start_date ? '!border-border-danger' : ''">
                                    <p x-show="errors.start_date" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.start_date"></p>
                                </div>
                                <div>
                                    <label class="input-label">Tanggal Selesai <span class="text-fg-danger">*</span></label>
                                    <input type="date" x-model="form.end_date" @change="clearError('end_date')" 
                                           class="input-standard" :class="errors.end_date ? '!border-border-danger' : ''">
                                    <p x-show="errors.end_date" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.end_date"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Dokumen Persyaratan -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
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

                    <!-- STEP 4: Ringkasan Sebelum Submit -->
                    <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <h2 class="text-h4 text-fg-heading mb-[8px] flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                                <i data-lucide="eye" class="w-[16px] h-[16px] text-brand"></i>
                            </div>
                            Ringkasan Permohonan
                        </h2>
                        <p class="text-[13px] text-fg-body-subtle mb-[24px] ml-[44px] font-medium">
                            Harap periksa kembali seluruh data yang Anda masukkan sebelum mengirim pengajuan.
                        </p>

                        <div class="space-y-[24px]">
                            <!-- Kelompok 1: Identitas & Anggota -->
                            <div class="bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] border-b border-border-default pb-[8px] flex items-center gap-2">
                                    <i data-lucide="user" class="w-[16px] h-[16px] text-brand"></i>
                                    Identitas & Anggota
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[8px] text-[14px]">
                                    <div><span class="text-fg-body-subtle block text-[12px]">Nama Lengkap</span> <strong class="text-fg-heading" x-text="form.name"></strong></div>
                                    <div><span class="text-fg-body-subtle block text-[12px]">NIM / NPM</span> <strong class="text-fg-heading" x-text="form.nim"></strong></div>
                                    <div><span class="text-fg-body-subtle block text-[12px]">Kontak / HP</span> <strong class="text-fg-heading" x-text="form.phone"></strong></div>
                                    <div class="sm:col-span-2"><span class="text-fg-body-subtle block text-[12px]">Email Aktif</span> <strong class="text-fg-heading text-brand" x-text="form.email"></strong></div>
                                    <div class="sm:col-span-2"><span class="text-fg-body-subtle block text-[12px]">Status Penelitian</span> <strong class="text-fg-heading text-brand" x-text="form.is_group === 'kelompok' ? 'Penelitian Kelompok' : 'Penelitian Individu'"></strong></div>
                                </div>
                            </div>

                            <!-- Kelompok 2: Akademik, Tujuan & Penelitian -->
                            <div class="bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] border-b border-border-default pb-[8px] flex items-center gap-2">
                                    <i data-lucide="file-text" class="w-[16px] h-[16px] text-brand"></i>
                                    Riset & Akademik
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[8px] text-[14px]">
                                    <div class="sm:col-span-2"><span class="text-fg-body-subtle block text-[12px]">Asal Kampus / Fakultas / Prodi</span> <strong class="text-fg-heading" x-text="form.university + ' · ' + form.faculty + ' · ' + form.study_program"></strong></div>
                                    <div class="sm:col-span-2 mt-2 border-t border-border-default/50 pt-2"></div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Ditujukan Kepada</span> 
                                        <strong class="text-fg-heading" x-text="form.recipient_position === 'Lainnya' ? form.custom_recipient_position : form.recipient_position"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Kota Tujuan</span> 
                                        <strong class="text-fg-heading" x-text="form.destination_city"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Nomor Surat Pengantar</span> 
                                        <strong class="text-fg-heading" x-text="form.reference_letter_number"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Tanggal Surat Pengantar</span> 
                                        <strong class="text-fg-heading" x-text="formatDate(form.reference_letter_date)"></strong>
                                    </div>
                                    <div class="sm:col-span-2 mt-2 border-t border-border-default/50 pt-2">
                                        <span class="text-fg-body-subtle block text-[12px]">Judul Penelitian</span> 
                                        <strong class="text-fg-heading block mt-[2px]" x-text="form.research_title"></strong>
                                    </div>
                                    <div class="sm:col-span-2 mt-2 border-t border-border-default/50 pt-2">
                                        <span class="text-fg-body-subtle block text-[12px]">Tujuan Penelitian</span> 
                                        <strong class="text-fg-heading block mt-[2px]" x-text="form.purpose"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Lokasi Penelitian</span> 
                                        <strong class="text-fg-heading" x-text="form.research_location === 'Lainnya' ? form.custom_research_location : form.research_location"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Jenis Penelitian</span> 
                                        <strong class="text-fg-heading" x-text="form.research_type"></strong>
                                    </div>
                                    <div>
                                        <span class="text-fg-body-subtle block text-[12px]">Estimasi Waktu Riset</span> 
                                        <strong class="text-fg-heading" x-text="formatDate(form.start_date) + ' s/d ' + formatDate(form.end_date)"></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Kelompok 3: Anggota Kelompok (if Kelompok) -->
                            <div x-show="form.is_group === 'kelompok'" x-transition class="bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] border-b border-border-default pb-[8px] flex items-center gap-2">
                                    <i data-lucide="users" class="w-[16px] h-[16px] text-brand"></i>
                                    Anggota Kelompok
                                </h3>
                                <div class="divide-y divide-border-default/50 text-[14px]">
                                    <template x-for="(m, idx) in form.members" :key="idx">
                                        <div class="py-[8px] flex justify-between">
                                            <span class="font-semibold text-fg-heading" x-text="(idx+1) + '. ' + m.name + (idx === 0 ? ' (Ketua)' : '')"></span>
                                            <span class="text-fg-body-subtle" x-text="'(NPM: ' + m.npm + ')'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Kelompok 4: Berkas Dokumen -->
                            <div class="bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <h3 class="font-bold text-[15px] text-fg-heading mb-[12px] border-b border-border-default pb-[8px] flex items-center gap-2">
                                    <i data-lucide="upload" class="w-[16px] h-[16px] text-brand"></i>
                                    Berkas Terlampir
                                </h3>
                                <div class="space-y-[8px] text-[14px]">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file-check-2" class="w-[16px] h-[16px] text-success-strong"></i>
                                        <span class="text-fg-body-subtle">Surat Pengantar Kampus:</span>
                                        <strong class="text-fg-heading font-medium" x-text="files.surat_pengantar_kampus ? files.surat_pengantar_kampus.name : '-'"></strong>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file-check-2" class="w-[16px] h-[16px] text-success-strong"></i>
                                        <span class="text-fg-body-subtle">Proposal Penelitian:</span>
                                        <strong class="text-fg-heading font-medium" x-text="files.proposal_penelitian ? files.proposal_penelitian.name : '-'"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Konfirmasi Checkbox -->
                        <div class="mt-[32px] p-[16px] bg-warning-soft border border-border-warning-subtle rounded-[12px] flex gap-[12px]">
                            <input type="checkbox" id="declaration-check" x-model="declarationChecked" class="accent-brand w-[20px] h-[20px] mt-[2px] cursor-pointer">
                            <label for="declaration-check" class="text-[13px] text-fg-heading leading-[1.6] cursor-pointer select-none">
                                Saya menyatakan bahwa seluruh data yang saya isi di atas adalah <strong>benar, sah, dan sesuai</strong> dengan berkas aslinya untuk proses verifikasi.
                            </label>
                        </div>
                        <p x-show="errors.declaration" class="text-[12px] text-fg-danger font-medium mt-[6px] ml-[32px]" x-text="errors.declaration"></p>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-[40px] pt-[24px] border-t border-border-default flex justify-between items-center">
                        <button type="button" @click="prevStep()" x-show="step > 1" class="btn-secondary h-[48px] px-[24px] flex items-center gap-[8px]">
                            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
                            Sebelumnya
                        </button>
                        <div x-show="step === 1" class="w-1"></div> <!-- Spacer for flex layout -->
                        
                        <button type="button" @click="nextStep()" x-show="step < 4" class="btn-brand h-[48px] px-[32px] flex items-center gap-[8px]">
                            Selanjutnya
                            <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                        </button>
                        
                        <button type="submit" x-show="step === 4" :disabled="loading || !declarationChecked"
                                class="btn-brand h-[56px] px-[40px] flex items-center gap-[12px] text-[16px] disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg transition-all duration-300">
                            <template x-if="loading">
                                <div class="flex items-center gap-[8px]">
                                    <svg class="animate-spin w-[20px] h-[20px] text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Mengirim...
                                </div>
                            </template>
                            <template x-if="!loading">
                                <div class="flex items-center gap-[8px]">
                                    <i data-lucide="send" class="w-[20px] h-[20px]"></i>
                                    Ajukan Permohonan
                                </div>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function submissionForm() {
    return {
        step: 1,
        form: {
            name: '', 
            email: '', 
            phone: '', 
            university: '', 
            faculty: '', 
            study_program: '', 
            nim: '', 
            
            // New fields
            recipient_position: '',
            custom_recipient_position: '',
            destination_city: '',
            reference_letter_number: '',
            reference_letter_date: '',
            research_title: '',
            purpose: '',
            research_location: '',
            custom_research_location: '',
            research_type: '',
            is_group: 'individu',
            members: [] // { name: '', npm: '' }
        },
        files: {
            surat_pengantar_kampus: null,
            proposal_penelitian: null
        },
        declarationChecked: false,
        errors: {},
        loading: false,
        success: false,
        registrationNumber: '',
        
        init() {
            // Keep first member synchronized with applicant's name and nim if group
            this.$watch('form.is_group', value => {
                if (value === 'kelompok') {
                    this.syncFirstMember();
                } else {
                    this.form.members = [];
                }
            });
            this.$watch('form.name', value => {
                if (this.form.is_group === 'kelompok') {
                    this.syncFirstMember();
                }
            });
            this.$watch('form.nim', value => {
                if (this.form.is_group === 'kelompok') {
                    this.syncFirstMember();
                }
            });
        },

        syncFirstMember() {
            if (this.form.members.length === 0) {
                this.form.members.push({ name: this.form.name, npm: this.form.nim });
            } else {
                this.form.members[0].name = this.form.name;
                this.form.members[0].npm = this.form.nim;
            }
        },
        
        stepTitle() {
            if (this.step === 1) return 'Identitas & Anggota';
            if (this.step === 2) return 'Riset & Akademik';
            if (this.step === 3) return 'Upload Berkas';
            return 'Ringkasan Sebelum Submit';
        },
        
        addMember() {
            if (this.form.members.length === 0) {
                this.syncFirstMember();
            }
            this.form.members.push({ name: '', npm: '' });
            setTimeout(() => lucide.createIcons(), 50);
        },
        
        removeMember(index) {
            if (index === 0) return;
            this.form.members.splice(index, 1);
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        },
        
        nextStep() {
            const errs = this.validateStep(this.step);
            if (Object.keys(errs).length > 0) {
                this.errors = errs;
                return;
            }
            this.errors = {};
            this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => lucide.createIcons(), 50);
        },
        
        prevStep() {
            this.step--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => lucide.createIcons(), 50);
        },
        
        clearError(field) {
            if(this.errors[field]) delete this.errors[field];
        },
        
        clearFile(field) {
            this.files[field] = null;
            if(this.errors[field]) delete this.errors[field];
            setTimeout(() => lucide.createIcons(), 10);
        },
        
        handleFileChange(e, field) {
            const f = e.target.files[0];
            if (!f) return;
            
            if (f.type !== 'application/pdf') {
                this.errors[field] = 'File harus berformat PDF.';
                this.files[field] = null;
                e.target.value = null;
                return;
            }

            if (f.size > 2 * 1024 * 1024) {
                this.errors[field] = 'Ukuran file maksimal 2 MB.';
                this.files[field] = null;
                e.target.value = null;
                return;
            }
            
            this.files[field] = f;
            this.clearError(field);
            setTimeout(() => lucide.createIcons(), 10);
        },
        
        validateStep(stepNumber) {
            let errs = {};
            let f = this.form;
            
            if (stepNumber === 1) {
                if (!f.name.trim()) errs.name = 'Nama lengkap wajib diisi.';
                if (!f.email.trim()) {
                    errs.email = 'Email wajib diisi.';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email)) {
                    errs.email = 'Format email tidak valid.';
                }
                if (!f.phone.trim()) errs.phone = 'Nomor HP wajib diisi.';
                if (!f.nim.trim()) errs.nim = 'NIM / NPM wajib diisi.';
                
                if (f.is_group === 'kelompok') {
                    if (f.members.length <= 1) {
                        errs.members = 'Harap tambahkan minimal 1 anggota kelompok untuk penelitian kelompok.';
                    } else {
                        f.members.forEach((member, i) => {
                            if (!member.name.trim() || !member.npm.trim()) {
                                errs.members = 'Nama dan NIM/NPM seluruh anggota wajib diisi.';
                            }
                        });
                    }
                }
            }
            else if (stepNumber === 2) {
                if (!f.university.trim()) errs.university = 'Universitas wajib diisi.';
                if (!f.faculty.trim()) errs.faculty = 'Fakultas wajib diisi.';
                if (!f.study_program.trim()) errs.study_program = 'Program Studi wajib diisi.';

                if (!f.recipient_position) errs.recipient_position = 'Jabatan tujuan surat wajib dipilih.';
                if (f.recipient_position === 'Lainnya' && !f.custom_recipient_position.trim()) {
                    errs.custom_recipient_position = 'Jabatan custom wajib diisi.';
                }
                if (!f.destination_city.trim()) errs.destination_city = 'Kota tujuan wajib diisi.';
                if (!f.reference_letter_number.trim()) errs.reference_letter_number = 'Nomor surat pengantar wajib diisi.';
                if (!f.reference_letter_date) errs.reference_letter_date = 'Tanggal surat pengantar wajib diisi.';
                
                if (!f.research_title.trim()) errs.research_title = 'Judul Penelitian wajib diisi.';
                if (!f.purpose.trim()) errs.purpose = 'Tujuan Penelitian wajib diisi.';
                if (!f.research_location) errs.research_location = 'Lokasi Penelitian wajib dipilih.';
                if (f.research_location === 'Lainnya' && !f.custom_research_location.trim()) {
                    errs.custom_research_location = 'Lokasi custom wajib diisi.';
                }
                if (!f.research_type) errs.research_type = 'Jenis Penelitian wajib dipilih.';
                if (!f.start_date) errs.start_date = 'Tanggal Mulai wajib diisi.';
                if (!f.end_date) errs.end_date = 'Tanggal Selesai wajib diisi.';
            }
            else if (stepNumber === 3) {
                if (!this.files.surat_pengantar_kampus) errs.surat_pengantar_kampus = 'Surat pengantar wajib diunggah.';
                if (!this.files.proposal_penelitian) errs.proposal_penelitian = 'Proposal penelitian wajib diunggah.';
            }
            
            return errs;
        },
        
        submit() {
            // Validate all steps to be safe
            let errs = {
                ...this.validateStep(1),
                ...this.validateStep(2),
                ...this.validateStep(3)
            };
            
            if (!this.declarationChecked) {
                errs.declaration = 'Anda harus menyetujui pernyataan kebenaran data.';
            }
            
            if (Object.keys(errs).length > 0) {
                this.errors = errs;
                // Focus/navigate to the step with error
                if (errs.name || errs.email || errs.phone || errs.nim) {
                    this.step = 1;
                } else if (errs.university || errs.faculty || errs.study_program || errs.recipient_position || errs.destination_city || errs.reference_letter_number || errs.reference_letter_date || errs.research_title || errs.research_location || errs.research_type || errs.members || errs.purpose) {
                    this.step = 2;
                } else {
                    this.step = 3;
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            
            this.loading = true;
            this.errors = {};
            
            const fd = new FormData();
            
            // Map the form data to request keys
            for (let k in this.form) {
                if (k === 'members') {
                    // Send members array
                    this.form.members.forEach((m, index) => {
                        fd.append(`members[${index}][name]`, m.name);
                        fd.append(`members[${index}][npm]`, m.npm);
                    });
                } else if (k === 'recipient_position' && this.form.recipient_position === 'Lainnya') {
                    fd.append('recipient_position', this.form.custom_recipient_position);
                } else if (k === 'research_location' && this.form.research_location === 'Lainnya') {
                    fd.append('research_location', this.form.custom_research_location);
                } else {
                    fd.append(k, this.form[k]);
                }
            }
            
            fd.append('surat_pengantar_kampus', this.files.surat_pengantar_kampus);
            fd.append('proposal_penelitian', this.files.proposal_penelitian);
            
            fetch('/api/public/submissions', {
                method: 'POST',
                body: fd,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json().then(data => ({status: res.status, body: data})))
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    this.success = true;
                    // Redirect to the success page!
                    window.location.href = `/success/${res.body.registration_number}`;
                } else {
                    if (res.body.errors) {
                        let mapped = {};
                        for (let k in res.body.errors) {
                            mapped[k] = res.body.errors[k][0];
                        }
                        this.errors = mapped;
                        // Determine step navigation on validation failure
                        if (mapped.name || mapped.email || mapped.phone || mapped.nim) {
                            this.step = 1;
                        } else if (mapped.university || mapped.faculty || mapped.study_program || mapped.recipient_position || mapped.destination_city || mapped.reference_letter_number || mapped.reference_letter_date || mapped.research_title || mapped.research_location || mapped.research_type || mapped.members || mapped.purpose) {
                            this.step = 2;
                        } else {
                            this.step = 3;
                        }
                    } else {
                        this.errors = { _global: res.body.message || 'Terjadi kesalahan. Silakan coba lagi.' };
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            })
            .catch(err => {
                this.errors = { _global: 'Terjadi kesalahan jaringan. Silakan coba lagi.' };
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .finally(() => {
                this.loading = false;
                setTimeout(() => lucide.createIcons(), 50);
            });
        }
    }
}
</script>
@endpush
