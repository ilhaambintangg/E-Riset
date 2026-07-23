@php
    $unreadSubmissionsCount = isset($unreadSubmissions) ? $unreadSubmissions->count() : 0;
    $unreadChatsCount = isset($unreadChatMessages) ? $unreadChatMessages->count() : 0;
    $activeSystem = $activeSystem ?? 'eriset';
    $userRole = auth()->user()->role ?? 'admin';
@endphp

<!-- Logo Header -->
<div class="h-16 flex items-center gap-3 px-6 border-b border-slate-200/60 bg-transparent shrink-0">
    <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center shadow-sm">
        @if($activeSystem === 'edvokat')
            <i data-lucide="gavel" class="w-4 h-4 text-white"></i>
        @else
            <i data-lucide="scale" class="w-4 h-4 text-white"></i>
        @endif
    </div>
    <div>
        <p class="font-bold text-sm text-[#0a2240] tracking-tight leading-none">
            {{ $activeSystem === 'edvokat' ? 'EDVOKAT Admin' : 'E-Riset Admin' }}
        </p>
        <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider mt-1">
            {{ $activeSystem === 'edvokat' ? 'Admin Panel EDVOKAT' : 'Management Portal' }}
        </p>
    </div>
    @if(isset($isMobile) && $isMobile)
        <button @click="sidebarOpen = false" class="ml-auto p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-200/50 rounded-full transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    @endif
</div>

<!-- Navigation Menus -->
<div class="flex-1 overflow-y-auto no-scrollbar py-4 px-3 space-y-1 bg-transparent">
    @if($activeSystem === 'eriset')
        <!-- E-RISET Menus -->
        
        <!-- Dashboard Section (Shared) -->
        @if($userRole !== 'admin')
        <div class="pt-2 pb-1">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dashboard</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            <span class="flex-1">Dashboard</span>
        </a>
        @endif

        @if($userRole === 'hukum')
            <!-- HUKUM Role Menus: Manajemen Permohonan Section -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Permohonan</p>
            </div>
            <!-- Permohonan Masuk -->
            <a href="{{ route('admin.submissions.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.submissions.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                <span class="flex-1">Permohonan Masuk</span>
                @if($unreadSubmissionsCount > 0)
                    <span class="bg-brand text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $unreadSubmissionsCount }}</span>
                @endif
            </a>
            <!-- Laporan -->
            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                <span class="flex-1">Laporan</span>
            </a>
            <!-- Live Chat -->
            <a href="{{ route('admin.chats.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.chats.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span class="flex-1">Live Chat</span>
                @if($unreadChatsCount > 0)
                    <span class="bg-brand text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $unreadChatsCount }}</span>
                @endif
            </a>
        @endif

        @if($userRole === 'admin')
            <!-- ADMIN Role Menus: Master Data Section -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Data</p>
            </div>
            <!-- Persyaratan Dokumen -->
            <a href="{{ route('requirements.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('requirements.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="file-check" class="w-4 h-4"></i>
                <span class="flex-1">Persyaratan Dokumen</span>
            </a>
            <!-- Data Panitera -->
            <a href="{{ route('panitera.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('panitera.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span class="flex-1">Data Panitera</span>
            </a>
            <!-- Data Hakim -->
            <a href="{{ route('hakims.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('hakims.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="gavel" class="w-4 h-4"></i>
                <span class="flex-1">Data Hakim</span>
            </a>
            <!-- Data Universitas -->
            <a href="{{ route('universities.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('universities.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                <span class="flex-1">Data Universitas</span>
            </a>
            <!-- Template Surat -->
            <a href="{{ route('templates.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('templates.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="file-code" class="w-4 h-4"></i>
                <span class="flex-1">Template Surat</span>
            </a>

            <!-- Informasi Publik Section -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Publik</p>
            </div>
            <!-- Kelola FAQ -->
            <a href="{{ route('faqs.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('faqs.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                <span class="flex-1">Kelola FAQ</span>
            </a>

            <!-- System Section -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</p>
            </div>
            <!-- Pengaturan -->
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span class="flex-1">Pengaturan</span>
            </a>
        @endif

    @elseif($activeSystem === 'edvokat')
        <!-- EDVOKAT Menus -->
        @php
            $edvokatPage = request()->query('page', 'dashboard');
        @endphp

        <!-- Dashboard Section -->
        <div class="pt-2 pb-1">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dashboard</p>
        </div>
        <a href="{{ route('admin.edvokat') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'dashboard') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            <span class="flex-1">Dashboard</span>
        </a>

        <!-- Manajemen Section -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen</p>
        </div>
        <!-- Data Advokat -->
        <a href="{{ route('admin.edvokat') }}?page=advokat" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'advokat') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="users" class="w-4 h-4"></i>
            <span class="flex-1">Data Advokat</span>
        </a>
        <!-- Permohonan -->
        <a href="{{ route('admin.edvokat') }}?page=permohonan" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'permohonan') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="inbox" class="w-4 h-4"></i>
            <span class="flex-1">Permohonan</span>
        </a>
        <!-- Verifikasi -->
        <a href="{{ route('admin.edvokat') }}?page=verifikasi" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'verifikasi') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span class="flex-1">Verifikasi</span>
        </a>
        <!-- Dokumen -->
        <a href="{{ route('admin.edvokat') }}?page=dokumen" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'dokumen') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span class="flex-1">Dokumen</span>
        </a>
        <!-- Laporan -->
        <a href="{{ route('admin.edvokat') }}?page=laporan" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'laporan') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
            <span class="flex-1">Laporan</span>
        </a>

        <!-- System Section -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</p>
        </div>
        <!-- Pengaturan -->
        <a href="{{ route('admin.edvokat') }}?page=pengaturan" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-all duration-200 {{ ($edvokatPage === 'pengaturan') ? 'bg-white text-[#0a2240] shadow-sm border border-slate-200/40 font-bold' : 'text-slate-600 hover:text-[#0a2240] hover:bg-slate-200/20' }}">
            <i data-lucide="settings" class="w-4 h-4"></i>
            <span class="flex-1">Pengaturan</span>
        </a>
    @endif
</div>

<!-- Profile & Logout Footer -->
<div class="p-4 border-t border-slate-200/60 shrink-0 bg-transparent">
    <div class="flex items-center gap-3">
        <!-- Avatar with green status dot -->
        <div class="relative shrink-0">
            <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-500 shadow-inner">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-50"></span>
        </div>
        <!-- Profile info -->
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ auth()->user()->name ?? 'Pengguna' }}</p>
            <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $userRole === 'admin' ? 'Super Administrator' : 'Hukum E-Riset' }}</p>
        </div>
        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}" class="m-0 flex items-center">
            @csrf
            <button type="submit" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-200/50 rounded-lg transition-colors cursor-pointer" title="Logout">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</div>
