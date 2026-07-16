<!-- STEP 2: Riset & Akademik -->
<div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak class="space-y-[24px]">
    <!-- Card 1: Informasi Akademik -->
    <div class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px]">
        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
            <div class="w-[32px] h-[32px] bg-brand-softer rounded-[10px] flex items-center justify-center border border-brand-subtle">
                <i data-lucide="building-2" class="w-[16px] h-[16px] text-brand"></i>
            </div>
            Informasi Akademik
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
            <!-- Universitas -->
            <div class="md:col-span-2">
                <label class="input-label">Universitas / Instansi <span class="text-fg-danger">*</span></label>
                <select x-model="form.university" @change="clearError('university')" 
                        class="input-standard bg-white"
                        :class="errors.university ? '!border-border-danger focus:!ring-danger' : ''">
                    <option value="" disabled selected>Pilih Universitas...</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->name }}">{{ $uni->name }}</option>
                    @endforeach
                    <option value="Lainnya">Lainnya (Tulis Manual)</option>
                </select>
                <p x-show="errors.university" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.university"></p>
            </div>

            <!-- Custom Universitas (Shown when "Lainnya" is selected) -->
            <div class="md:col-span-2" x-show="form.university === 'Lainnya'" x-transition x-cloak>
                <label class="input-label">Nama Universitas / Instansi <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.custom_university" @input="clearError('custom_university')" placeholder="Masukkan nama universitas atau instansi secara lengkap" 
                       class="input-standard" :class="errors.custom_university ? '!border-border-danger' : ''">
                <p x-show="errors.custom_university" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.custom_university"></p>
            </div>

            <!-- Fakultas -->
            <div>
                <label class="input-label">Fakultas <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.faculty" @input="clearError('faculty')" placeholder="Contoh: Hukum" 
                       class="input-standard"
                       :class="errors.faculty ? '!border-border-danger focus:!ring-danger' : ''">
                <p x-show="errors.faculty" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.faculty"></p>
            </div>

            <!-- Program Studi -->
            <div>
                <label class="input-label">Program Studi <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.study_program" @input="clearError('study_program')" placeholder="Contoh: S1 Ilmu Hukum" 
                       class="input-standard"
                       :class="errors.study_program ? '!border-border-danger focus:!ring-danger' : ''">
                <p x-show="errors.study_program" class="text-[12px] text-fg-danger mt-[6px] font-medium animate-fade-in" x-text="errors.study_program"></p>
            </div>
        </div>
    </div>

    <!-- Card 2: Surat Pengantar Kampus -->
    <div class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px]">
        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                <i data-lucide="file-check-2" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
            </div>
            Surat Pengantar Kampus
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">




            <!-- Nomor Surat Pengantar Kampus -->
            <div>
                <label class="input-label">Nomor Surat Pengantar Kampus <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.reference_letter_number" @input="clearError('reference_letter_number')" placeholder="Contoh: 68/021003/PL/VI/2024" 
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
    </div>

    <!-- Card 3: Informasi Penelitian -->
    <div class="bg-white border-2 border-border-default rounded-[16px] shadow-2xs p-[24px] sm:p-[40px]">
        <h2 class="text-h4 text-fg-heading mb-[24px] pb-[16px] border-b border-border-default flex items-center gap-[12px]">
            <div class="w-[32px] h-[32px] bg-brand-alt-soft rounded-[10px] flex items-center justify-center border border-brand-alt-subtle">
                <i data-lucide="file-text" class="w-[16px] h-[16px] text-brand-alt-strong"></i>
            </div>
            Informasi Penelitian
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
            <!-- Judul Penelitian -->
            <div class="md:col-span-2">
                <label class="input-label">Judul Penelitian <span class="text-fg-danger">*</span></label>
                <textarea rows="2" x-model="form.research_title" @input="clearError('research_title')" placeholder="Masukkan judul penelitian sesuai dengan proposal yang diajukan" 
                          class="input-standard resize-none" :class="errors.research_title ? '!border-border-danger' : ''"></textarea>
                <p x-show="errors.research_title" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_title"></p>
            </div>



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
                    <option value="Pengadilan Negeri Kota Agung">Pengadilan Negeri Kota Agung</option>
                    <option value="Pengadilan Negeri Gedong Tataan">Pengadilan Negeri Gedong Tataan</option>
                    <option value="Lainnya">Lainnya (Tulis Custom)</option>
                </select>
                <p x-show="errors.research_location" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_location"></p>
            </div>

            <!-- Tujuan Penelitian -->
            <div>
                <label class="input-label">Tujuan Penelitian <span class="text-fg-danger">*</span></label>
                <select x-model="form.research_type" @change="clearError('research_type'); clearError('custom_research_type')" class="input-standard" :class="errors.research_type ? '!border-border-danger' : ''">
                    <option value="" disabled selected>Pilih Tujuan Penelitian...</option>
                    <option value="Skripsi">Skripsi</option>
                    <option value="Tesis">Tesis</option>
                    <option value="Disertasi">Disertasi</option>
                    <option value="Penelitian Akademik">Penelitian Akademik</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <p x-show="errors.research_type" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.research_type"></p>
            </div>

            <!-- Custom Tujuan Penelitian (Shown when "Lainnya" is selected) -->
            <div class="md:col-span-2" x-show="form.research_type === 'Lainnya'" x-transition x-cloak>
                <label class="input-label">Masukkan Tujuan Penelitian <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.custom_research_type" @input="clearError('custom_research_type')" placeholder="Contoh: Laporan Magang, PKL, Tugas Akhir" 
                       class="input-standard" :class="errors.custom_research_type ? '!border-border-danger' : ''">
                <p x-show="errors.custom_research_type" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.custom_research_type"></p>
            </div>

            <!-- Custom Lokasi (Shown when "Lainnya" is selected) -->
            <div class="md:col-span-2" x-show="form.research_location === 'Lainnya'" x-transition x-cloak>
                <label class="input-label">Masukkan Lokasi Penelitian <span class="text-fg-danger">*</span></label>
                <input type="text" x-model="form.custom_research_location" @input="clearError('custom_research_location')" placeholder="Contoh: Pengadilan Agama Tanjungkarang" 
                       class="input-standard" :class="errors.custom_research_location ? '!border-border-danger' : ''">
                <p x-show="errors.custom_research_location" class="text-[12px] text-fg-danger mt-[6px] font-medium" x-text="errors.custom_research_location"></p>
            </div>


        </div>
    </div>
</div>
