<nav x-data="{ menuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-neutral-primary/90 backdrop-blur-md shadow-xs border-b border-border-default': scrolled, 'bg-transparent': !scrolled }"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="container-standard">
        <div class="flex items-center justify-between h-[80px]">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-[12px] group">
                <div class="w-[40px] h-[40px] bg-brand rounded-base flex items-center justify-center shadow-xs transition-all group-hover:bg-brand-medium">
                    <i data-lucide="scale" class="w-[24px] h-[24px] text-white"></i>
                </div>
                <div>
                    <span class="text-[20px] font-heading font-bold text-fg-heading tracking-tight">E-RISET</span>
                    <p class="text-[10px] text-brand font-bold uppercase tracking-widest hidden sm:block">PT Tanjungkarang</p>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-[32px]">
                <a href="/#tentang" class="text-[14px] text-fg-body hover:text-brand font-bold transition-colors uppercase tracking-wider">Tentang</a>
                <a href="/#alur" class="text-[14px] text-fg-body hover:text-brand font-bold transition-colors uppercase tracking-wider">Prosedur</a>
                <a href="/#persyaratan" class="text-[14px] text-fg-body hover:text-brand font-bold transition-colors uppercase tracking-wider">Persyaratan</a>
                <a href="/#faq" class="text-[14px] text-fg-body hover:text-brand font-bold transition-colors uppercase tracking-wider">FAQ</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center gap-[16px]">
                <a href="/track" class="btn-secondary h-[40px]">
                    Cek Status
                </a>
                <a href="/register-permit" class="btn-brand h-[40px]">
                    Ajukan Izin
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="menuOpen = !menuOpen" class="md:hidden p-[8px] text-fg-body hover:text-fg-brand">
                <i x-show="!menuOpen" data-lucide="menu" class="w-[24px] h-[24px]"></i>
                <i x-show="menuOpen" x-cloak data-lucide="x" class="w-[24px] h-[24px]"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="menuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         x-cloak
         class="md:hidden absolute w-full bg-neutral-primary border-b border-border-default px-[16px] pb-[24px] pt-[8px] shadow-sm">
        <div class="flex flex-col gap-[8px]">
            <a href="/#tentang" @click="menuOpen = false" class="text-fg-heading font-bold hover:text-brand hover:bg-neutral-secondary-soft py-[12px] px-[16px] rounded-base transition-colors">Tentang Sistem</a>
            <a href="/#alur" @click="menuOpen = false" class="text-fg-heading font-bold hover:text-brand hover:bg-neutral-secondary-soft py-[12px] px-[16px] rounded-base transition-colors">Prosedur</a>
            <a href="/#persyaratan" @click="menuOpen = false" class="text-fg-heading font-bold hover:text-brand hover:bg-neutral-secondary-soft py-[12px] px-[16px] rounded-base transition-colors">Persyaratan</a>
            <a href="/#faq" @click="menuOpen = false" class="text-fg-heading font-bold hover:text-brand hover:bg-neutral-secondary-soft py-[12px] px-[16px] rounded-base transition-colors">FAQ</a>
            
            <div class="h-px bg-border-default my-[8px]"></div>
            
            <a href="/track" @click="menuOpen = false" class="btn-secondary h-[48px] w-full flex items-center justify-center mt-[8px]">Cek Status</a>
            <a href="/register-permit" @click="menuOpen = false" class="btn-brand h-[48px] w-full flex items-center justify-center mt-[8px]">Ajukan Sekarang</a>
        </div>
    </div>
</nav>
