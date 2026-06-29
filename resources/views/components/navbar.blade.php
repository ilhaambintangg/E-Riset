<div class="fixed top-[16px] left-0 right-0 z-50 px-[16px] md:px-[24px]">
    <nav x-data="{ menuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'bg-white/95 border-[3px] border-brand shadow-md py-[8px]': scrolled, 'bg-white/80 border-2 border-border-default py-[14px]': !scrolled }"
         class="max-w-[1200px] mx-auto rounded-full backdrop-blur-md transition-all duration-500 ease-out px-[24px]">
        
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-[12px] group">
                <div class="w-[44px] h-[44px] bg-brand rounded-full flex items-center justify-center shadow-xs transition-all group-hover:bg-brand-medium group-hover:rotate-12 duration-300">
                    <i data-lucide="scale" class="w-[22px] h-[22px] text-brand-alt"></i>
                </div>
                <div>
                    <span class="text-[20px] font-heading font-black text-fg-heading tracking-tight flex items-center">
                        E-RISET
                        <span class="ml-1.5 px-2 py-0.5 bg-brand-alt-soft text-brand-alt-strong text-[10px] font-extrabold rounded-full border border-brand-alt/30 animate-pulse">v2.0</span>
                    </span>
                    <p class="text-[9px] text-brand font-bold uppercase tracking-widest hidden sm:block">PT Tanjungkarang</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-[28px]">
                <a href="/#tentang" class="text-[13px] text-fg-body hover:text-brand-alt font-extrabold transition-all uppercase tracking-wider hover:scale-105">Tentang</a>
                <a href="/#alur" class="text-[13px] text-fg-body hover:text-brand-alt font-extrabold transition-all uppercase tracking-wider hover:scale-105">Prosedur</a>
                <a href="/#persyaratan" class="text-[13px] text-fg-body hover:text-brand-alt font-extrabold transition-all uppercase tracking-wider hover:scale-105">Persyaratan</a>
                <a href="/#faq" class="text-[13px] text-fg-body hover:text-brand-alt font-extrabold transition-all uppercase tracking-wider hover:scale-105">FAQ</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center gap-[12px]">
                <a href="/track" class="btn-secondary btn-sm h-[40px] rounded-full px-5">
                    <i data-lucide="search" class="w-[16px] h-[16px]"></i>
                    Cek Status
                </a>
                <a href="/register-permit" class="btn-brand btn-sm h-[40px] rounded-full px-6 bg-brand-alt border-brand-alt text-white hover:bg-brand-alt-strong hover:border-brand-alt-strong">
                    Ajukan Izin
                    <i data-lucide="arrow-right-circle" class="w-[16px] h-[16px] animate-pulse"></i>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="menuOpen = !menuOpen" class="md:hidden p-[8px] text-fg-body hover:text-brand transition-colors focus:outline-none">
                <i x-show="!menuOpen" data-lucide="menu" class="w-[24px] h-[24px] animate-fade-in"></i>
                <i x-show="menuOpen" x-cloak data-lucide="x" class="w-[24px] h-[24px] animate-fade-in"></i>
            </button>
        </div>

        <!-- Mobile Menu (Bubbly Dropdown) -->
        <div x-show="menuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             x-cloak
             class="md:hidden mt-[16px] bg-white border-[3px] border-brand rounded-[24px] px-[20px] pb-[24px] pt-[12px] shadow-lg">
            <div class="flex flex-col gap-[8px]">
                <a href="/#tentang" @click="menuOpen = false" class="text-fg-heading font-extrabold hover:text-brand-alt hover:bg-neutral-primary-medium py-[10px] px-[16px] rounded-full transition-all">Tentang Sistem</a>
                <a href="/#alur" @click="menuOpen = false" class="text-fg-heading font-extrabold hover:text-brand-alt hover:bg-neutral-primary-medium py-[10px] px-[16px] rounded-full transition-all">Prosedur</a>
                <a href="/#persyaratan" @click="menuOpen = false" class="text-fg-heading font-extrabold hover:text-brand-alt hover:bg-neutral-primary-medium py-[10px] px-[16px] rounded-full transition-all">Persyaratan</a>
                <a href="/#faq" @click="menuOpen = false" class="text-fg-heading font-extrabold hover:text-brand-alt hover:bg-neutral-primary-medium py-[10px] px-[16px] rounded-full transition-all">FAQ</a>
                
                <div class="h-[2px] bg-border-default my-[8px]"></div>
                
                <a href="/track" @click="menuOpen = false" class="btn-secondary h-[44px] w-full flex items-center justify-center rounded-full">Cek Status</a>
                <a href="/register-permit" @click="menuOpen = false" class="btn-brand h-[44px] w-full flex items-center justify-center rounded-full bg-brand-alt border-brand-alt text-white">Ajukan Sekarang</a>
            </div>
        </div>
    </nav>
</div>
