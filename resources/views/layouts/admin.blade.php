<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-primary-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin E-Riset') - Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Google Fonts: Outfit & Quicksand (Playful & Bubbly Font Theme) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="h-screen font-sans text-fg-body bg-neutral-primary-soft antialiased flex flex-col overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 w-full border-b border-neutral-secondary-strong bg-white/85 backdrop-blur-md shadow-sm shrink-0">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand / Logo & Desktop Nav Links -->
            <div class="flex items-center gap-8">
                <!-- Mobile Menu Toggle -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-fg-body hover:text-brand hover:bg-brand-soft/50 rounded-full transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                
                <!-- Logo -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 shrink-0 group">
                    <div class="w-11 h-11 bg-brand rounded-xl flex items-center justify-center shadow-md shadow-brand/10 transition-transform group-hover:scale-105">
                        <i data-lucide="scale" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="hidden sm:block leading-none">
                        <p class="font-bold text-lg text-brand tracking-tight">E-RISET</p>
                        <p class="text-[11px] text-fg-body-subtle font-semibold uppercase tracking-wider mt-0.5">Admin Panel</p>
                    </div>
                </a>

                <!-- Desktop Nav Menu -->
                <nav class="hidden lg:flex items-center gap-2">
                    <!-- Dashboard Link -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-5 py-2.5 text-[15px] font-semibold rounded-full transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-brand text-white shadow-sm' : 'text-fg-body hover:text-fg-heading hover:bg-neutral-primary-strong' }}">
                        Dashboard
                    </a>

                    <!-- Layanan Dropdown -->
                    @php
                        $layananActive = request()->routeIs('admin.submissions.*') || request()->routeIs('admin.chats.*') || request()->routeIs('requirements.*') || request()->routeIs('panitera.*') || request()->routeIs('templates.*');
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="px-5 py-2.5 text-[15px] font-semibold rounded-full flex items-center gap-1 transition-all duration-200 cursor-pointer {{ $layananActive ? 'bg-brand/10 text-brand' : 'text-fg-body hover:text-fg-heading hover:bg-neutral-primary-strong' }}">
                            Layanan <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-64 bg-white border border-border-default rounded-xl shadow-lg py-2 z-50 origin-top-left"
                             x-cloak>
                            <a href="{{ route('admin.submissions.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('admin.submissions.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="inbox" class="w-4 h-4"></i> Permohonan Masuk
                            </a>
                            <a href="{{ route('admin.chats.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('admin.chats.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="message-square" class="w-4 h-4"></i> Live Chat
                            </a>
                            <div class="border-t border-border-default my-1"></div>
                            <div class="px-4 py-1 text-[11px] font-bold text-fg-body-subtle uppercase tracking-widest">Master Data</div>
                            <a href="{{ route('requirements.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('requirements.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="file-check" class="w-4 h-4"></i> Persyaratan Dokumen
                            </a>
                            <a href="{{ route('panitera.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('panitera.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="users" class="w-4 h-4"></i> Data Panitera
                            </a>
                            <a href="{{ route('templates.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('templates.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="file-code" class="w-4 h-4"></i> Template Surat
                            </a>
                        </div>
                    </div>

                    <!-- Laporan Link -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="px-5 py-2.5 text-[15px] font-semibold rounded-full transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-brand/10 text-brand' : 'text-fg-body hover:text-fg-heading hover:bg-neutral-primary-strong' }}">
                        Laporan
                    </a>

                    <!-- Pusat Bantuan Dropdown -->
                    @php
                        $bantuanActive = request()->routeIs('faqs.*') || request()->routeIs('announcements.*') || request()->routeIs('admin.settings.*');
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="px-5 py-2.5 text-[15px] font-semibold rounded-full flex items-center gap-1 transition-all duration-200 cursor-pointer {{ $bantuanActive ? 'bg-brand/10 text-brand' : 'text-fg-body hover:text-fg-heading hover:bg-neutral-primary-strong' }}">
                            Pusat Bantuan <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-60 bg-white border border-border-default rounded-xl shadow-lg py-2 z-50 origin-top-left"
                             x-cloak>
                            <a href="{{ route('faqs.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('faqs.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="help-circle" class="w-4 h-4"></i> Kelola FAQ
                            </a>
                            <a href="{{ route('announcements.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('announcements.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="megaphone" class="w-4 h-4"></i> Pengumuman
                            </a>
                            <div class="border-t border-border-default my-1"></div>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[15px] font-medium hover:bg-neutral-primary-soft transition-colors {{ request()->routeIs('admin.settings.*') ? 'text-brand bg-brand-soft/30' : 'text-fg-body hover:text-fg-heading' }}">
                                <i data-lucide="settings" class="w-4 h-4 text-fg-body-subtle"></i> Pengaturan
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Search Bar (Pill Shape) -->
            <div class="hidden md:flex items-center flex-1 max-w-xs relative">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                <input type="text" placeholder="Cari data..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-neutral-primary-soft border border-transparent rounded-full text-sm font-semibold focus:outline-none focus:border-brand-alt focus:ring-2 focus:ring-brand-alt/20 transition-all placeholder:text-fg-body-subtle text-fg-body">
            </div>

            <!-- Right Side Actions: Notifications, Web View, Profile -->
            <div class="flex items-center gap-4">
                 <!-- Notification Bell Dropdown -->
                <div class="relative" x-data="{ open: false, hasNew: {{ ($unreadSubmissions->count() + $unreadChatMessages->count()) > 0 ? 'true' : 'false' }} }">
                    @php
                        $totalNotifications = $unreadSubmissions->count() + $unreadChatMessages->count();
                    @endphp
                    <button @click="open = !open" class="p-2.5 text-fg-body hover:text-brand hover:bg-neutral-primary-soft rounded-full transition-colors relative cursor-pointer">
                        <i data-lucide="bell" class="w-6 h-6"></i>
                        <span x-show="hasNew" class="absolute top-1 right-1 flex h-3 w-3" x-cloak>
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-danger"></span>
                        </span>
                    </button>
                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white border border-border-default rounded-xl shadow-lg py-2 z-50 origin-top-right"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-border-default flex items-center justify-between">
                            <span class="font-bold text-sm text-fg-heading">Notifikasi</span>
                            <span x-show="hasNew" class="text-[10px] bg-brand-soft text-brand px-2 py-0.5 rounded-full font-bold" x-cloak>{{ $totalNotifications }} Baru</span>
                            <span x-show="!hasNew" class="text-[10px] bg-neutral-primary-strong text-fg-body-subtle px-2 py-0.5 rounded-full font-bold" x-cloak>0</span>
                        </div>
                        <div class="max-h-[250px] overflow-y-auto">
                            <!-- Unread Chat Messages -->
                            @foreach($unreadChatMessages as $msg)
                                <a href="{{ route('admin.chats.index') }}?session_id={{ $msg->chat_session_id }}" @click="hasNew = false" class="block px-4 py-3 hover:bg-neutral-primary-soft transition-colors border-b border-border-default-subtle last:border-0 cursor-pointer">
                                    <div class="flex items-start gap-2.5">
                                        <div class="p-1.5 bg-brand-soft text-brand rounded-full shrink-0">
                                            <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm text-fg-body font-medium leading-tight">Pesan baru dari <span class="font-bold text-fg-heading">{{ $msg->session->name ?: 'Pemohon' }}</span></p>
                                            <p class="text-xs text-fg-body-subtle truncate mt-0.5">"{{ $msg->message }}"</p>
                                            <span class="text-[10px] text-fg-body-subtle mt-1 block">{{ $msg->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            <!-- Unread Submissions -->
                            @foreach($unreadSubmissions as $unread)
                                <a href="{{ route('admin.submissions.show', $unread->id) }}" @click="hasNew = false" class="block px-4 py-3 hover:bg-neutral-primary-soft transition-colors border-b border-border-default-subtle last:border-0 cursor-pointer">
                                    <div class="flex items-start gap-2.5">
                                        <div class="p-1.5 bg-brand-soft text-brand rounded-full shrink-0">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm text-fg-body font-medium leading-tight">Permohonan baru masuk: <span class="font-bold text-fg-heading">{{ $unread->name }}</span></p>
                                            <p class="text-xs text-fg-body-subtle truncate mt-0.5">{{ $unread->university }}</p>
                                            <span class="text-[10px] text-fg-body-subtle mt-1 block">{{ $unread->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            @if($totalNotifications === 0)
                                <div class="px-4 py-6 text-center text-fg-body-subtle text-xs flex flex-col items-center justify-center">
                                    <i data-lucide="bell-off" class="w-8 h-8 text-neutral-tertiary mb-2"></i>
                                    <p>Tidak ada notifikasi baru</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Public View Button -->
                <a href="/" target="_blank" class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-fg-body hover:text-brand hover:bg-brand-soft/20 px-4 py-2.5 rounded-full border border-border-default transition-all">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Public View
                </a>

                <div class="hidden sm:block w-px h-6 bg-border-default"></div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-3.5 text-left focus:outline-none cursor-pointer group">
                        <div class="w-11 h-11 rounded-full bg-brand-soft border border-brand/20 text-brand font-bold shrink-0 flex items-center justify-center shadow-2xs group-hover:border-brand/40 transition-colors text-base">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden md:block leading-tight shrink-0">
                            <p class="text-sm font-bold text-fg-heading truncate max-w-[120px]">{{ auth()->user()->name ?? 'Admin Riset' }}</p>
                            <p class="text-xs text-fg-body-subtle truncate">Administrator</p>
                        </div>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-border-default rounded-xl shadow-lg py-1.5 z-50 origin-top-right"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-border-default md:hidden">
                            <p class="text-sm font-bold text-fg-heading truncate">{{ auth()->user()->name ?? 'Admin Riset' }}</p>
                            <p class="text-xs text-fg-body-subtle truncate">Administrator</p>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-fg-body hover:bg-neutral-primary-soft transition-colors">
                            <i data-lucide="settings" class="w-4 h-4 text-fg-body-subtle"></i> Pengaturan
                        </a>
                        <div class="border-t border-border-default my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-fg-danger hover:bg-danger-soft/30 transition-colors text-left cursor-pointer font-medium">
                                <i data-lucide="log-out" class="w-4 h-4 text-fg-danger"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 flex flex-row min-w-0 overflow-hidden">
        
        <!-- Mobile Sidebar Drawer Background Overlay -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm lg:hidden" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             x-cloak></div>

        <!-- Mobile Drawer Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-border-default transform transition-transform duration-300 ease-in-out lg:hidden flex flex-col shadow-xl"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             x-cloak>
            
            <!-- Logo header -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-border-default shrink-0">
                <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center shadow-md shadow-brand/10">
                    <i data-lucide="scale" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-base text-brand tracking-tight">E-RISET</p>
                    <p class="text-[9px] text-fg-body-subtle font-semibold uppercase tracking-wider">Admin Panel</p>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto p-1 text-fg-body hover:bg-neutral-primary-strong rounded-full transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Mobile navigation links -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                @php
                    $mobileNavItems = [
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

                @foreach($mobileNavItems as $item)
                    @if(isset($item['is_divider']))
                        <p class="px-4 pt-4 pb-1 text-[10px] font-bold text-fg-body-subtle uppercase tracking-widest">{{ $item['label'] }}</p>
                    @else
                        <a href="{{ $item['url'] }}" 
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors font-semibold {{ $item['active'] ? 'bg-brand/10 text-brand' : 'text-fg-body hover:bg-neutral-primary-strong hover:text-fg-heading' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 {{ $item['active'] ? 'text-brand' : 'text-fg-body-subtle' }}"></i>
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Profile and logout footer -->
            <div class="p-4 border-t border-border-default shrink-0 bg-neutral-primary-medium">
                <div class="flex items-center gap-3 px-2 py-1.5">
                    <div class="w-10 h-10 rounded-full bg-brand-soft border border-brand/20 text-brand font-bold shrink-0 flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-fg-heading truncate">{{ auth()->user()->name ?? 'Admin Riset' }}</p>
                        <p class="text-[10px] text-fg-body-subtle truncate">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="p-2 text-fg-body-subtle hover:text-fg-danger hover:bg-danger-soft/30 rounded-lg transition-colors" title="Logout">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto relative bg-neutral-primary-soft">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <!-- Page Title Header Section -->
                <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-border-default pb-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-fg-heading tracking-tight">@yield('header_title', 'Dashboard')</h1>
                        <p class="text-sm text-fg-body-subtle mt-2 flex items-center gap-1.5 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4 text-brand"></i>
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    @yield('header_actions')
                </div>
                
                <!-- Global Flash Messages -->
                @if(session('success'))
                    <div class="mb-[24px] bg-success-soft border border-border-success-subtle p-[16px] rounded-xl shadow-2xs flex items-start gap-[12px]">
                        <i data-lucide="check-circle-2" class="w-[20px] h-[20px] text-fg-success shrink-0 mt-[2px]"></i>
                        <div>
                            <p class="text-[14px] font-bold text-fg-success">Berhasil</p>
                            <p class="text-[14px] text-fg-success mt-[2px]">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-[24px] bg-danger-soft border border-border-danger-subtle p-[16px] rounded-xl shadow-2xs flex items-start gap-[12px]">
                        <i data-lucide="alert-circle" class="w-[20px] h-[20px] text-fg-danger shrink-0 mt-[2px]"></i>
                        <div>
                            <p class="text-[14px] font-bold text-fg-danger">Terjadi Kesalahan</p>
                            <ul class="text-[14px] text-fg-danger mt-[4px] list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>

