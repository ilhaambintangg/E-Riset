@extends('layouts.public')

@section('content')

<div class="min-h-screen bg-neutral-primary-soft relative overflow-hidden flex flex-col pt-[120px] sm:pt-[140px]" x-data="submissionForm()">
    <!-- Background abstract patterns -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-40" style="background-image: radial-gradient(var(--color-border-default) 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
    
    <div class="flex-1 pb-[80px] container-standard relative z-10 max-w-[1000px] mx-auto w-full">
        <div class="mt-[20px]">
            <!-- Header -->
            <div class="text-center mb-[40px] animate-fade-up">
                <div class="inline-flex items-center gap-[8px] bg-brand-softer border-2 border-brand/20 px-[18px] py-[8px] rounded-full mb-[16px] shadow-2xs">
                    <i data-lucide="scale" class="w-[16px] h-[16px] text-brand-alt"></i>
                    <span class="text-brand font-extrabold text-[12px] tracking-widest uppercase">E-RISET FORMULIR</span>
                </div>
                <h1 class="text-h2 text-fg-heading mb-[8px] font-black">
                    Pengajuan Izin Penelitian
                </h1>
                <p class="text-[15px] text-fg-body max-w-[600px] mx-auto font-medium opacity-90">
                    Isi formulir pendaftaran riset elektronik bersama Si Risi di bawah ini dengan lengkap.
                </p>
            </div>

            <!-- Global Error -->
            <div x-show="errors._global" x-transition x-cloak class="flex items-center gap-[12px] bg-danger-soft border-2 border-danger rounded-base p-[16px] mb-[24px] text-fg-danger-strong shadow-xs animate-fade-in">
                <i data-lucide="alert-circle" class="w-[22px] h-[22px] shrink-0 text-danger"></i>
                <span class="text-[14px] font-bold" x-text="errors._global"></span>
            </div>

            <!-- Success State -->
            <div x-show="success" x-transition x-cloak class="bg-white rounded-base shadow-lg border-[3px] border-brand p-[48px] text-center animate-fade-up">
                <div class="w-[96px] h-[96px] bg-success-soft rounded-full flex items-center justify-center mx-auto mb-[32px] border-[4px] border-white shadow-md">
                    <i data-lucide="check-circle-2" class="w-[48px] h-[48px] text-success-strong"></i>
                </div>
                <h2 class="font-heading font-black text-[32px] text-fg-heading mb-[16px]">Permohonan Terkirim!</h2>
                <p class="text-fg-body max-w-[500px] mx-auto leading-relaxed">
                    Terima kasih telah mengajukan izin penelitian. Nomor registrasi dan konfirmasi telah kami kirimkan ke email Anda.
                </p>
            </div>

            <!-- Multi-Step Form Card -->
            <div x-show="!success" x-transition class="animate-fade-up space-y-[24px]">
                
                <!-- Stepper Header -->
                <div class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs px-[24px] sm:px-[40px] py-[32px] relative">
                    <!-- Dynamic Mascot Dialog Bubble -->
                    <div class="flex items-center gap-4 mb-[20px] bg-white border-2 border-border-default rounded-base p-3.5 shadow-2xs">
                        <div class="shrink-0 bg-brand rounded-full w-12 h-12 flex items-center justify-center">
                            <!-- Mini Owl Head SVG -->
                            <svg class="w-10 h-10 animate-head-bob" viewBox="0 0 100 100" fill="none">
                                <circle cx="50" cy="50" r="42" fill="#143A66" stroke="#0A2240" stroke-width="3"/>
                                <circle cx="35" cy="50" r="11" fill="white" stroke="#F4A261" stroke-width="3"/>
                                <circle cx="65" cy="50" r="11" fill="white" stroke="#F4A261" stroke-width="3"/>
                                <path d="M44 50 L56 50" stroke="#F4A261" stroke-width="3"/>
                                <g class="animate-risi-blink">
                                    <circle cx="35" cy="50" r="4.5" fill="#0A2240"/>
                                    <circle cx="65" cy="50" r="4.5" fill="#0A2240"/>
                                </g>
                                <polygon points="50,54 45,61 55,61" fill="#E76F51" stroke="#0A2240" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[13px] text-fg-heading font-black" 
                               x-text="step === 1 ? 'Si Risi: Yuk isi nama & data dirimu! 📝' : (step === 2 ? 'Si Risi: Ceritakan rencana riset hebatmu! 🎓' : (step === 3 ? 'Si Risi: Upload berkas pendukungnya ya! ☁️' : 'Si Risi: Cek kembali sebelum dikirim! 👀'))">
                            </p>
                            <p class="text-[11px] text-fg-body-subtle font-bold uppercase tracking-wider" x-text="stepTitle()"></p>
                        </div>
                        <span class="text-[14px] font-black text-brand bg-brand-softer border border-brand/20 px-3 py-1 rounded-full" x-text="'Langkah ' + step + ' / 4'"></span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full h-[8px] bg-neutral-primary-strong rounded-full overflow-hidden border border-border-default/40">
                        <div class="h-full bg-brand-alt transition-all duration-500 ease-out" :style="'width: ' + ((step / 4) * 100) + '%'"></div>
                    </div>
                    
                    <!-- Steps Indicator circles -->
                    <div class="flex justify-between mt-[20px] px-1">
                        <div class="flex flex-col items-center gap-[6px]" :class="step >= 1 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[36px] h-[36px] rounded-full flex items-center justify-center font-black text-[13px] border-2 transition-all duration-300"
                                 :class="step > 1 ? 'bg-brand border-brand text-white shadow-2xs' : (step === 1 ? 'bg-brand-alt border-brand-alt text-white shadow-xs scale-110' : 'bg-white border-border-default text-fg-body-subtle')">
                                <i x-show="step > 1" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 1">1</span>
                            </div>
                            <span class="text-[11px] font-extrabold hidden sm:block" :class="step >= 1 ? 'text-brand' : 'text-fg-body-subtle'">Identitas</span>
                        </div>
                        
                        <div class="flex flex-col items-center gap-[6px]" :class="step >= 2 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[36px] h-[36px] rounded-full flex items-center justify-center font-black text-[13px] border-2 transition-all duration-300"
                                 :class="step > 2 ? 'bg-brand border-brand text-white shadow-2xs' : (step === 2 ? 'bg-brand-alt border-brand-alt text-white shadow-xs scale-110' : 'bg-white border-border-default text-fg-body-subtle')">
                                <i x-show="step > 2" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 2">2</span>
                            </div>
                            <span class="text-[11px] font-extrabold hidden sm:block" :class="step >= 2 ? 'text-brand' : 'text-fg-body-subtle'">Riset</span>
                        </div>
                        
                        <div class="flex flex-col items-center gap-[6px]" :class="step >= 3 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[36px] h-[36px] rounded-full flex items-center justify-center font-black text-[13px] border-2 transition-all duration-300"
                                 :class="step > 3 ? 'bg-brand border-brand text-white shadow-2xs' : (step === 3 ? 'bg-brand-alt border-brand-alt text-white shadow-xs scale-110' : 'bg-white border-border-default text-fg-body-subtle')">
                                <i x-show="step > 3" data-lucide="check" class="w-[16px] h-[16px]"></i>
                                <span x-show="step <= 3">3</span>
                            </div>
                            <span class="text-[11px] font-extrabold hidden sm:block" :class="step >= 3 ? 'text-brand' : 'text-fg-body-subtle'">Upload</span>
                        </div>
                        
                        <div class="flex flex-col items-center gap-[6px]" :class="step >= 4 ? 'opacity-100' : 'opacity-40'">
                            <div class="w-[36px] h-[36px] rounded-full flex items-center justify-center font-black text-[13px] border-2 transition-all duration-300"
                                 :class="step === 4 ? 'bg-brand-alt border-brand-alt text-white shadow-xs scale-110' : 'bg-white border-border-default text-fg-body-subtle'">
                                <span>4</span>
                            </div>
                            <span class="text-[11px] font-extrabold hidden sm:block" :class="step === 4 ? 'text-brand' : 'text-fg-body-subtle'">Review</span>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-[24px]" novalidate>
                    
                    <!-- STEP 1: Identitas Pemohon & Anggota Penelitian -->
                    @include('pages.partials.form-step-1')
                    
                    <!-- STEP 2: Riset & Akademik -->
                    @include('pages.partials.form-step-2')
                    
                    <!-- STEP 3: Dokumen Persyaratan -->
                    @include('pages.partials.form-step-3')
                    
                    <!-- STEP 4: Ringkasan Sebelum Submit -->
                    @include('pages.partials.form-step-4')
                    
                    <!-- Navigation Buttons -->
                    <div class="mt-[12px] flex justify-between items-center">
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
            target_institution: '',
            reference_letter_number: '',
            reference_letter_date: '',
            research_title: '',
            purpose: '',
            research_location: '',
            custom_research_location: '',
            research_type: '',
            custom_research_type: '', // Untuk opsi "Lainnya" pada Jenis Penelitian
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
        
        previewFile(field) {
            if (this.files[field]) {
                const url = URL.createObjectURL(this.files[field]);
                window.open(url, '_blank');
            }
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
                // Jika "Lainnya" dipilih, wajib isi custom_research_type
                if (f.research_type === 'Lainnya' && !f.custom_research_type.trim()) {
                    errs.custom_research_type = 'Jenis Penelitian wajib diisi.';
                }
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
                } else if (k === 'research_type' && this.form.research_type === 'Lainnya') {
                    // Jika "Lainnya" dipilih, kirim nilai custom sebagai research_type
                    fd.append('research_type', this.form.custom_research_type);
                } else if (k === 'custom_research_type') {
                    // Skip — sudah di-handle di atas bersama research_type
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
