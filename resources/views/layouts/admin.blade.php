<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-primary-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin E-Riset') - Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="h-full font-sans text-fg-body bg-neutral-primary-soft antialiased overflow-hidden flex" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-neutral-secondary-strong/80 backdrop-blur-sm lg:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-72 bg-brand-strong border-r border-brand/20 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-xl"
         :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        
        <!-- Logo -->
        <div class="h-[80px] flex items-center gap-[12px] px-[24px] border-b border-brand/20 shrink-0 bg-brand-strong">
            <div class="w-[40px] h-[40px] bg-brand-alt rounded-base flex items-center justify-center shadow-lg shadow-brand-alt/25">
                <i data-lucide="scale" class="w-[24px] h-[24px] text-brand-strong"></i>
            </div>
            <div>
                <p class="font-bold text-[18px] text-white tracking-tight">E-RISET</p>
                <p class="text-[10px] text-brand-alt font-bold uppercase tracking-widest">Admin Panel</p>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-white/80 hover:text-white">
                <i data-lucide="x" class="w-[24px] h-[24px]"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-[24px] px-[16px] space-y-[4px] custom-scrollbar">
            @php
                $navItems = [
                    ['url' => route('admin.dashboard'), 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'active' => request()->routeIs('admin.dashboard')],
                    ['url' => route('admin.submissions.index'), 'icon' => 'inbox', 'label' => 'Permohonan Masuk', 'active' => request()->routeIs('admin.submissions.*')],
                    ['url' => route('admin.reports.index'), 'icon' => 'bar-chart-3', 'label' => 'Laporan', 'active' => request()->routeIs('admin.reports.*')],
                    ['url' => route('admin.chats.index'), 'icon' => 'message-square', 'label' => 'Live Chat', 'active' => request()->routeIs('admin.chats.*')],
                    
                    ['is_divider' => true, 'label' => 'Master Data'],
                    
                    ['url' => route('requirements.index'), 'icon' => 'file-check', 'label' => 'Persyaratan Dokumen', 'active' => request()->routeIs('requirements.*')],
                    ['url' => route('panitera.index'), 'icon' => 'users', 'label' => 'Data Panitera', 'active' => request()->routeIs('panitera.*')],
                    ['url' => route('templates.index'), 'icon' => 'file-code', 'label' => 'Template Surat', 'active' => request()->routeIs('templates.*')],
                    
                    ['is_divider' => true, 'label' => 'Informasi Publik'],
                    
                    ['url' => route('faqs.index'), 'icon' => 'help-circle', 'label' => 'Kelola FAQ', 'active' => request()->routeIs('faqs.*')],
                    ['url' => route('announcements.index'), 'icon' => 'megaphone', 'label' => 'Pengumuman', 'active' => request()->routeIs('announcements.*')],
                    
                    ['is_divider' => true, 'label' => 'Sistem'],
                    
                    ['url' => route('admin.settings.index'), 'icon' => 'settings', 'label' => 'Pengaturan', 'active' => request()->routeIs('admin.settings.*')],
                ];
            @endphp

            @foreach($navItems as $item)
                @if(isset($item['is_divider']))
                    <p class="px-[16px] pt-[24px] pb-[8px] text-[11px] font-bold text-white/30 uppercase tracking-widest">{{ $item['label'] }}</p>
                @else
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center gap-[12px] px-[16px] py-[12px] rounded-base transition-all duration-200 group font-medium {{ $item['active'] ? 'bg-gradient-to-r from-brand-alt to-amber-500 text-brand-strong shadow-lg shadow-brand-alt/10 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-[20px] h-[20px] {{ $item['active'] ? 'text-brand-strong font-bold' : 'text-white/50 group-hover:text-white' }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- User profile bottom -->
        <div class="p-[16px] border-t border-brand/20 shrink-0 bg-brand-strong">
            <div class="flex items-center gap-[12px] px-[16px] py-[12px] bg-brand/25 border border-brand/20 rounded-base">
                <div class="w-[40px] h-[40px] rounded-base bg-brand-alt text-brand-strong font-bold shrink-0 flex items-center justify-center shadow-md">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-bold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[12px] text-white/60 truncate">Admin Sistem</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-[8px] text-white/60 hover:text-brand-alt hover:bg-white/10 rounded-base transition-colors" title="Logout">
                        <i data-lucide="log-out" class="w-[20px] h-[20px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-neutral-primary-soft">
        <!-- Topbar -->
        <header class="h-[80px] bg-white/80 backdrop-blur-md border-b border-border-default flex items-center justify-between px-[16px] sm:px-[24px] lg:px-[32px] z-30 shrink-0">
            <div class="flex items-center gap-[16px]">
                <button @click="sidebarOpen = true" class="lg:hidden p-[8px] text-fg-body hover:text-fg-heading hover:bg-neutral-secondary-soft rounded-base">
                    <i data-lucide="menu" class="w-[24px] h-[24px]"></i>
                </button>
                <h1 class="text-h4 text-fg-heading">@yield('header_title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-[16px]">
                <!-- Notification Bell Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-[10px] text-fg-body hover:text-brand hover:bg-neutral-secondary-soft rounded-full transition-colors relative">
                        <i data-lucide="bell" class="w-[20px] h-[20px]"></i>
                        @if($unreadSubmissions->count() > 0)
                            <span class="absolute top-[8px] right-[8px] flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-danger"></span>
                            </span>
                        @endif
                    </button>
                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white border border-border-default rounded-base shadow-lg py-2 z-50 origin-top-right"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-border-default flex items-center justify-between">
                            <span class="font-bold text-sm text-fg-heading">Notifikasi</span>
                            @if($unreadSubmissions->count() > 0)
                                <span class="text-[10px] bg-brand-alt-soft text-fg-warning px-2 py-0.5 rounded-full font-bold">{{ $unreadSubmissions->count() }} Baru</span>
                            @else
                                <span class="text-[10px] bg-neutral-primary-medium text-fg-body-subtle px-2 py-0.5 rounded-full font-bold">0</span>
                            @endif
                        </div>
                        <div class="max-h-[250px] overflow-y-auto">
                            @forelse($unreadSubmissions as $unread)
                                <a href="{{ route('admin.submissions.show', $unread->id) }}" class="block px-4 py-3 hover:bg-neutral-primary-soft transition-colors border-b border-border-default-subtle last:border-0 cursor-pointer">
                                    <p class="text-xs text-fg-body font-medium">Permohonan baru masuk: <span class="font-bold text-fg-heading">{{ $unread->name }}</span> ({{ $unread->university }})</p>
                                    <span class="text-[10px] text-fg-body-subtle mt-1 block">{{ $unread->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-fg-body-subtle text-xs">
                                    <i data-lucide="bell-off" class="w-8 h-8 text-neutral-tertiary mx-auto mb-2"></i>
                                    Tidak ada notifikasi baru
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <a href="/" target="_blank" class="hidden sm:flex items-center gap-[8px] text-[14px] font-medium text-fg-body hover:text-fg-brand transition-colors bg-neutral-secondary-soft hover:bg-neutral-tertiary-soft px-[16px] py-[8px] rounded-base border border-border-default">
                    <i data-lucide="external-link" class="w-[16px] h-[16px]"></i>
                    Lihat Web Publik
                </a>
            </div>
        </header>

        <!-- Main scrollable area -->
        <main class="flex-1 overflow-y-auto p-[16px] sm:p-[24px] lg:p-[32px] relative">
            
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="mb-[24px] bg-success-soft border border-border-success-subtle p-[16px] rounded-base shadow-xs flex items-start gap-[12px]">
                    <i data-lucide="check-circle-2" class="w-[20px] h-[20px] text-fg-success-strong shrink-0 mt-[2px]"></i>
                    <div>
                        <p class="text-[14px] font-bold text-fg-success-strong">Berhasil</p>
                        <p class="text-[14px] text-fg-success-strong mt-[2px]">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-[24px] bg-danger-soft border border-border-danger-subtle p-[16px] rounded-base shadow-xs flex items-start gap-[12px]">
                    <i data-lucide="alert-circle" class="w-[20px] h-[20px] text-fg-danger-strong shrink-0 mt-[2px]"></i>
                    <div>
                        <p class="text-[14px] font-bold text-fg-danger-strong">Terjadi Kesalahan</p>
                        <ul class="text-[14px] text-fg-danger-strong mt-[4px] list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>
