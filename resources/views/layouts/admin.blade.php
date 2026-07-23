@php
    if (request()->has('change_chat_status') && auth()->check()) {
        $status = request()->input('change_chat_status') === 'online' ? 'online' : 'offline';
        cache()->forever('admin_chat_status', $status);
        session()->flash('success', 'Status chat admin berhasil diubah menjadi ' . strtoupper($status));
        
        $redirectUrl = request()->url();
        $query = request()->except('change_chat_status');
        if (count($query)) {
            $redirectUrl .= '?' . http_build_query($query);
        }
        
        redirect()->to($redirectUrl)->send();
        exit;
    }

    $activeSystem = str_contains(request()->path(), 'admin/edvokat') ? 'edvokat' : 'eriset';
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($activeSystem === 'edvokat' ? 'EDVOKAT | Admin Panel' : 'Admin E-Riset')) - Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans (Professional & Modern Theme) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
@php
    $unreadSubmissions = $unreadSubmissions ?? collect();
    $unreadChatMessages = $unreadChatMessages ?? collect();
@endphp
<body class="h-screen font-sans text-fg-body bg-slate-50 antialiased flex overflow-hidden admin-theme" x-data="{ sidebarOpen: false }">

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
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-50 border-r border-slate-200/80 transform transition-transform duration-300 ease-in-out lg:hidden flex flex-col shadow-xl"
         :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
         x-cloak>
        @include('layouts.partials.sidebar-content', ['isMobile' => true, 'activeSystem' => $activeSystem])
    </div>

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex lg:flex-shrink-0 w-64 bg-slate-50 border-r border-slate-200/80 flex-col">
        @include('layouts.partials.sidebar-content', ['isMobile' => false, 'activeSystem' => $activeSystem])
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative admin-bg-gradient">
        <!-- Top Navigation Bar Inside Content Area -->
        <header class="h-[70px] bg-transparent shrink-0 z-40">
            <div class="h-full px-6 lg:px-8 flex items-center justify-between gap-6">
                
                <!-- Left side: Mobile Toggle & Search Bar -->
                <div class="flex items-center gap-4 flex-1">
                    <!-- Mobile Menu Toggle -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    
                    <!-- Mobile Logo (only visible on mobile) -->
                    <a href="{{ route('admin.dashboard') }}" class="flex lg:hidden items-center gap-2 shrink-0">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center border border-white/20">
                            <i data-lucide="scale" class="w-4 h-4 text-white"></i>
                        </div>
                    </a>

                    <!-- Search Bar (Mockup Style, Left-Aligned on Navy background) -->
                    <form action="{{ route('admin.submissions.index') }}" method="GET" class="hidden md:flex items-center w-full max-w-md relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/70"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari permohonan..." 
                               class="w-full pl-9 pr-4 py-2 bg-white/10 border border-white/20 rounded-lg text-xs text-white placeholder-white/60 transition-all focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/40 h-9">
                    </form>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{ open: false, hasNew: {{ ($unreadSubmissions->count() + $unreadChatMessages->count()) > 0 ? 'true' : 'false' }} }">
                        @php
                            $totalNotifications = $unreadSubmissions->count() + $unreadChatMessages->count();
                        @endphp
                        <button @click="open = !open" class="p-2 text-white/90 hover:text-white hover:bg-white/10 rounded-full transition-colors relative cursor-pointer">
                            <i data-lucide="bell" class="w-5.5 h-5.5"></i>
                            <span x-show="hasNew" class="absolute top-0.5 right-0.5 flex h-2.5 w-2.5" x-cloak>
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-danger"></span>
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
                                                <p class="text-xs text-fg-body font-medium leading-tight">Pesan baru dari <span class="font-bold text-fg-heading">{{ $msg->session->name ?: 'Pemohon' }}</span></p>
                                                <p class="text-[11px] text-fg-body-subtle truncate mt-0.5">"{{ $msg->message }}"</p>
                                                <span class="text-[9px] text-fg-body-subtle mt-1 block">{{ $msg->created_at->diffForHumans() }}</span>
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
                                                <p class="text-xs text-fg-body font-medium leading-tight">Permohonan baru masuk: <span class="font-bold text-fg-heading">{{ $unread->name }}</span></p>
                                                <p class="text-[11px] text-fg-body-subtle truncate mt-0.5">{{ $unread->university }}</p>
                                                <span class="text-[9px] text-fg-body-subtle mt-1 block">{{ $unread->created_at->diffForHumans() }}</span>
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



                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center justify-center w-8 h-8 rounded-full border border-white/30 hover:border-white/55 text-white/95 hover:text-white transition-colors focus:outline-none cursor-pointer">
                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-1.5 z-50 origin-top-right"
                             x-cloak>
                            <div class="px-4 py-2 border-b border-border-default md:hidden">
                                <p class="text-sm font-bold text-fg-heading truncate">{{ auth()->user()->name ?? 'Admin Riset' }}</p>
                                <p class="text-xs text-fg-body-subtle truncate">Administrator</p>
                            </div>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-fg-body hover:bg-neutral-primary-soft transition-colors">
                                <i data-lucide="settings" class="w-4 h-4 text-fg-body-subtle"></i> Pengaturan
                            </a>
                            <div class="border-t border-border-default my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-fg-danger hover:bg-danger-soft/30 transition-colors text-left cursor-pointer font-medium">
                                    <i data-lucide="log-out" class="w-4 h-4 text-fg-danger"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrolling Content Area -->
        <main class="flex-1 overflow-y-auto relative bg-transparent">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-5">
                
                <!-- Page Title Header Section -->
                @if(!request()->routeIs('admin.dashboard'))
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                     <div>
                         <h1 class="text-2xl font-bold text-white tracking-tight">@yield('header_title', 'Dashboard')</h1>
                         @hasSection('header_subtitle')
                             <p class="text-xs text-white/80 mt-1">@yield('header_subtitle')</p>
                         @endif
                     </div>
                    @yield('header_actions')
                </div>
                @endif
                
                <!-- Global Flash Messages -->
                @if(session('success'))
                    <div class="mb-[24px] bg-success-soft border border-border-success-subtle p-[16px] rounded-xl shadow-2xs flex items-start gap-[12px]">
                        <i data-lucide="check-circle-2" class="w-[20px] h-[20px] text-fg-success shrink-0 mt-[2px]"></i>
                        <div>
                            <p class="text-xs font-bold text-fg-success">Berhasil</p>
                            <p class="text-xs text-fg-success mt-[2px]">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-[24px] bg-danger-soft border border-border-danger-subtle p-[16px] rounded-xl shadow-2xs flex items-start gap-[12px]">
                        <i data-lucide="alert-circle" class="w-[20px] h-[20px] text-fg-danger shrink-0 mt-[2px]"></i>
                        <div>
                            <p class="text-xs font-bold text-fg-danger">Terjadi Kesalahan</p>
                            <ul class="text-xs text-fg-danger mt-[4px] list-disc list-inside">
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
    
    <!-- Automatic Admin Chat Online/Offline Status Handlers -->
    <script>
        window.addEventListener('beforeunload', function () {
            // Send keepalive fetch request to set status to offline when browser/tab is closed
            fetch('?change_chat_status=offline', { keepalive: true });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
