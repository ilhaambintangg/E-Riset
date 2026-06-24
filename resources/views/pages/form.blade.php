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
                    Lengkapi semua data administrasi di bawah ini dengan benar
                </p>
            </div>

            <!-- Global Error -->
            <div x-show="errors._global" x-transition x-cloak class="flex items-center gap-[12px] bg-danger-soft border border-border-danger-subtle rounded-[12px] p-[16px] mb-[24px] text-fg-danger-strong shadow-sm animate-fade-in">
                <i data-lucide="alert-circle" class="w-[20px] h-[20px] shrink-0"></i>
                <span class="text-[14px] font-medium" x-text="errors._global"></span>
            </div>

            <!-- Success State -->
            <div x-show="success" x-transition x-cloak class="bg-white rounded-[24px] shadow-lg border border-border-default p-[48px] text-center animate-fade-up">
                <div class="w-[96px] h-[96px] bg-success-soft rounded-full flex items-center justify-center mx-auto mb-[32px] border-[4px] border-white shadow-md">
                    <i data-lucide="check-circle-2" class="w-[48px] h-[48px] text-success-strong"></i>
                </div>
                <h2 class="font-heading font-bold text-[32px] text-fg-heading mb-[16px]">Permohonan Terkirim!</h2>
                
                <div class="bg-neutral-primary-soft border border-border-default rounded-[16px] p-[32px] mb-[32px] text-left">
                    <p class="text-[15px] text-fg-heading leading-[1.6] mb-[24px]">
                        <strong>Permohonan izin penelitian Anda berhasil dikirim ke sistem.</strong><br />
                        Silakan menunggu proses verifikasi dari petugas Pengadilan Tinggi Tanjungkarang.
                    </p>
                    
                    <div x-show="registrationNumber" class="bg-white rounded-[12px] p-[24px] border border-border-default text-center shadow-sm">
                        <p class="text-[12px] text-brand font-bold mb-[8px] uppercase tracking-widest">Nomor Registrasi Anda</p>
                        <p class="text-[40px] font-mono font-black text-brand-strong tracking-tight" x-text="registrationNumber"></p>
                        <p class="text-[13px] text-fg-body-subtle mt-[12px]">Simpan nomor registrasi ini untuk melacak status permohonan Anda melalui menu "Lacak Permohonan".</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/track" class="btn-brand btn-lg">
                        Lacak Status
                        <i data-lucide="search" class="w-[18px] h-[18px] ml-2"></i>
                    </a>
                    <a href="/" class="btn-secondary btn-lg">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <!-- Multi-Step Form -->
            <div x-show="!success" x-transition class="bg-white rounded-[24px] shadow-lg border border-border-default overflow-hidden animate-fade-up">
                
                <!-- Stepper Header -->
                <div class="bg-neutral-primary-soft border-b border-border-default px-[24px] sm:px-[40px] py-[32px]">
                    <div class="flex items-center justify-between mb-[16px]">
                        <h3 class="font-heading font-bold text-[18px] text-fg-heading" x-text="'Langkah ' + step + ' dari 3'"></h3>
                        <span class="text-[13px] font-bold text-brand" x-text="stepTitle()"></span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full h-[6px] bg-neutral-secondary-medium rounded-full overflow-hidden">
                        <div class="h-full bg-brand transition-all duration-500 ease-out" :style="'width: ' + ((step / 3) * 100) + '%'"></div>
                    </div>
                    
                    <!-- Steps Indicator -->
                    <div class="flex justify-between mt-[16px]">
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 1 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step > 1 ? 'bg-brand text-white' : (step === 1 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body')">
                                <i x-show="step > 1" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 1">1</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step >= 1 ? 'text-brand' : 'text-fg-body-subtle'">Data Diri</span>
                        </div>
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 2 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step > 2 ? 'bg-brand text-white' : (step === 2 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body')">
                                <i x-show="step > 2" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 2">2</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step >= 2 ? 'text-brand' : 'text-fg-body-subtle'">Penelitian</span>
                        </div>
                        <div class="flex flex-col items-center gap-[8px]" :class="step >= 3 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[32px] h-[32px] rounded-full flex items-center justify-center font-bold text-[14px] transition-colors"
                                 :class="step === 3 ? 'bg-brand text-white shadow-md' : 'bg-neutral-secondary-strong text-fg-body'">
                                <span>3</span>
                            </div>
                            <span class="text-[12px] font-bold hidden sm:block" :class="step === 3 ? 'text-brand' : 'text-fg-body-subtle'">Dokumen</span>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-[24px] sm:p-[40px]" novalidate>
                    
                    <!-- STEP 1: Data Pemohon -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                                <i data-lucide="user" class="w-[16px] h-[16px] text-brand"></i>
                            </div>
                            Identitas Pemohon
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label class="input-label">Nama Lengkap <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.name" @input="clearError('name')" placeholder="Sesuai KTP" 
                                       class="input-standard"
                                       :class="errors.name ? '!border-border-danger focus:!ring-danger' : ''">
                                <p x-show="errors.name" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.name"></p>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="md:col-span-2">
                                <label class="input-label">Jenis Kelamin <span class="text-fg-danger">*</span></label>
                                <div class="flex gap-[16px]">
                                    <label class="flex items-center gap-[12px] cursor-pointer px-[16px] py-[12px] rounded-[10px] border flex-1 transition-all"
                                           :class="form.gender === 'Laki-laki' ? 'border-brand bg-brand-softer text-brand-strong' : 'border-border-default-medium bg-neutral-secondary-medium hover:border-border-default-strong text-fg-body'">
                                        <input type="radio" x-model="form.gender" @change="clearError('gender')" value="Laki-laki" class="accent-brand w-[16px] h-[16px]">
                                        <span class="text-[14px] font-medium">Laki-laki</span>
                                    </label>
                                    <label class="flex items-center gap-[12px] cursor-pointer px-[16px] py-[12px] rounded-[10px] border flex-1 transition-all"
                                           :class="form.gender === 'Perempuan' ? 'border-brand bg-brand-softer text-brand-strong' : 'border-border-default-medium bg-neutral-secondary-medium hover:border-border-default-strong text-fg-body'">
                                        <input type="radio" x-model="form.gender" @change="clearError('gender')" value="Perempuan" class="accent-brand w-[16px] h-[16px]">
                                        <span class="text-[14px] font-medium">Perempuan</span>
                                    </label>
                                </div>
                                <p x-show="errors.gender" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.gender"></p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="input-label">Alamat Email <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="mail" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="email" x-model="form.email" @input="clearError('email')" placeholder="contoh@email.com" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.email ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.email" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.email"></p>
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

                            <!-- Universitas -->
                            <div class="md:col-span-2">
                                <label class="input-label">Universitas / Instansi <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="building-2" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="text" x-model="form.university" @input="clearError('university')" placeholder="Nama Universitas / Instansi Lengkap" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.university ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.university" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.university"></p>
                            </div>

                            <!-- Fakultas -->
                            <div>
                                <label class="input-label">Fakultas <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="layout" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="text" x-model="form.faculty" @input="clearError('faculty')" placeholder="Nama Fakultas" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.faculty ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.faculty" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.faculty"></p>
                            </div>

                            <!-- Program Studi -->
                            <div>
                                <label class="input-label">Program Studi <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="book-open" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="text" x-model="form.study_program" @input="clearError('study_program')" placeholder="Nama Program Studi" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.study_program ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.study_program" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.study_program"></p>
                            </div>

                            <!-- NIM -->
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

                            <!-- Semester -->
                            <div>
                                <label class="input-label">Semester <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="hash" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="text" x-model="form.semester" @input="clearError('semester')" placeholder="Contoh: 6" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.semester ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.semester" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.semester"></p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Data Penelitian -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
                            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                                <i data-lucide="file-text" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
                            </div>
                            Detail Penelitian
                        </h2>

                        <div class="space-y-[24px]">
                            <div>
                                <label class="input-label">Judul Penelitian <span class="text-fg-danger">*</span></label>
                                <input type="text" x-model="form.title" @input="clearError('title')" placeholder="Masukkan judul penelitian sesuai proposal" 
                                       class="input-standard"
                                       :class="errors.title ? '!border-border-danger focus:!ring-danger' : ''">
                                <p x-show="errors.title" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.title"></p>
                            </div>

                            <div>
                                <label class="input-label">Tujuan Penelitian <span class="text-fg-danger">*</span></label>
                                <textarea rows="4" x-model="form.purpose" @input="clearError('purpose')" placeholder="Jelaskan tujuan utama penelitian dilakukan di instansi ini" 
                                          class="input-standard resize-none"
                                          :class="errors.purpose ? '!border-border-danger focus:!ring-danger' : ''"></textarea>
                                <p x-show="errors.purpose" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.purpose"></p>
                            </div>

                            <div>
                                <label class="input-label">Lokasi / Objek Penelitian <span class="text-fg-danger">*</span></label>
                                <div class="relative group">
                                    <i data-lucide="map-pin" class="absolute left-[16px] top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-fg-body-subtle group-focus-within:text-brand transition-colors"></i>
                                    <input type="text" x-model="form.location" @input="clearError('location')" placeholder="Nama Pengadilan Negeri / Instansi tujuan" 
                                           class="input-standard pl-[44px]"
                                           :class="errors.location ? '!border-border-danger focus:!ring-danger' : ''">
                                </div>
                                <p x-show="errors.location" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.location"></p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] bg-neutral-primary-soft p-[24px] rounded-[16px] border border-border-default">
                                <div class="md:col-span-2">
                                    <h4 class="font-bold text-[14px] text-fg-heading mb-[4px]">Periode Penelitian</h4>
                                    <p class="text-[13px] text-fg-body-subtle mb-[16px]">Tentukan estimasi waktu pelaksanaan riset.</p>
                                </div>
                                <div>
                                    <label class="input-label">Tanggal Mulai <span class="text-fg-danger">*</span></label>
                                    <div class="relative group">
                                        <input type="date" x-model="form.start_date" @change="clearError('start_date')" 
                                               class="input-standard"
                                               :class="errors.start_date ? '!border-border-danger focus:!ring-danger' : ''">
                                    </div>
                                    <p x-show="errors.start_date" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.start_date"></p>
                                </div>
                                <div>
                                    <label class="input-label">Tanggal Selesai <span class="text-fg-danger">*</span></label>
                                    <div class="relative group">
                                        <input type="date" x-model="form.end_date" @change="clearError('end_date')" 
                                               class="input-standard"
                                               :class="errors.end_date ? '!border-border-danger focus:!ring-danger' : ''">
                                    </div>
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
                        
                        <div class="mt-[32px] p-[16px] bg-warning-soft border border-border-warning-subtle rounded-[12px] flex gap-[12px]">
                            <i data-lucide="info" class="w-[20px] h-[20px] text-warning-strong shrink-0 mt-[2px]"></i>
                            <p class="text-[13px] text-fg-heading leading-[1.6]">
                                <strong>Pernyataan:</strong> Dengan mengirim formulir ini, saya menyatakan bahwa seluruh data dan dokumen yang saya lampirkan adalah <strong>benar dan sah</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-[40px] pt-[24px] border-t border-border-default flex justify-between items-center">
                        <button type="button" @click="prevStep()" x-show="step > 1" class="btn-secondary h-[48px] px-[24px] flex items-center gap-[8px]">
                            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
                            Kembali
                        </button>
                        <div x-show="step === 1" class="w-1"></div> <!-- Spacer for flex layout -->
                        
                        <button type="button" @click="nextStep()" x-show="step < 3" class="btn-brand h-[48px] px-[32px] flex items-center gap-[8px]">
                            Lanjutkan
                            <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                        </button>
                        
                        <button type="submit" x-show="step === 3" :disabled="loading"
                                class="btn-brand h-[56px] px-[40px] flex items-center gap-[12px] text-[16px] disabled:opacity-70 disabled:cursor-not-allowed shadow-md hover:shadow-lg transition-all duration-300">
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
                                    Kirim Permohonan
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
            name: '', gender: '', email: '', phone: '', address: 'Dilengkapi nanti', 
            university: '', faculty: '', study_program: '', semester: '', nim: '', 
            title: '', purpose: '', location: '', start_date: '', end_date: ''
        },
        files: {
            surat_pengantar_kampus: null,
            proposal_penelitian: null
        },
        errors: {},
        loading: false,
        success: false,
        registrationNumber: '',
        
        stepTitle() {
            if (this.step === 1) return 'Data Identitas';
            if (this.step === 2) return 'Detail Riset';
            return 'Upload Dokumen';
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
            
            if (f.size > 2 * 1024 * 1024) {
                alert(`Ukuran file "${f.name}" terlalu besar. Maksimal ukuran file adalah 2 MB.`);
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
                if (!f.gender) errs.gender = 'Jenis kelamin wajib dipilih.';
                if (!f.email.trim()) {
                    errs.email = 'Email wajib diisi.';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email)) {
                    errs.email = 'Format email tidak valid.';
                }
                if (!f.phone.trim()) errs.phone = 'Nomor HP wajib diisi.';
                if (!f.university.trim()) errs.university = 'Universitas wajib diisi.';
                if (!f.faculty.trim()) errs.faculty = 'Fakultas wajib diisi.';
                if (!f.study_program.trim()) errs.study_program = 'Program Studi wajib diisi.';
                if (!f.nim.trim()) errs.nim = 'NIM / NPM wajib diisi.';
                if (!f.semester.trim()) errs.semester = 'Semester wajib diisi.';
            }
            else if (stepNumber === 2) {
                if (!f.title.trim()) errs.title = 'Judul Penelitian wajib diisi.';
                if (!f.purpose.trim()) errs.purpose = 'Tujuan Penelitian wajib diisi.';
                if (!f.location.trim()) errs.location = 'Lokasi / Objek Penelitian wajib diisi.';
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
            const errs = this.validateStep(3);
            if (Object.keys(errs).length > 0) {
                this.errors = errs;
                return;
            }
            
            this.loading = true;
            this.errors = {};
            
            const fd = new FormData();
            for (let k in this.form) {
                fd.append(k, this.form[k]);
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
                    this.registrationNumber = res.body.registration_number || '';
                    this.success = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    if (res.body.errors) {
                        let mapped = {};
                        for (let k in res.body.errors) {
                            mapped[k] = res.body.errors[k][0];
                        }
                        this.errors = mapped;
                        // Determine which step has errors and navigate back
                        if (mapped.name || mapped.gender || mapped.email || mapped.phone || mapped.university || mapped.faculty || mapped.study_program || mapped.nim || mapped.semester) {
                            this.step = 1;
                        } else if (mapped.title || mapped.purpose || mapped.location || mapped.start_date || mapped.end_date) {
                            this.step = 2;
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
