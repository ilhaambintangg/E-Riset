<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-primary-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin - Pengadilan Tinggi Tanjungkarang</title>
    @vite(['resources/css/app.css'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Extra hover glow effect */
        .hover-glow:hover {
            box-shadow: 0 20px 40px -15px rgba(244, 162, 97, 0.15), 0 15px 30px -10px rgba(10, 34, 64, 0.1);
        }
    </style>
</head>
<body class="h-full font-sans text-fg-body bg-neutral-primary-soft antialiased flex flex-col justify-between relative overflow-hidden">

    <!-- Decorative background -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-br from-brand-alt/15 to-brand-alt/5 blur-3xl"></div>
        <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-tr from-brand-strong/10 to-brand/5 blur-3xl"></div>
        <div class="absolute top-[20%] left-[20%] w-[30%] h-[30%] rounded-full bg-gradient-to-br from-brand-soft/20 to-transparent blur-3xl"></div>
    </div>

    <!-- Header bar with User Profile and Logout -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-strong rounded-default flex items-center justify-center shadow-md">
                <i data-lucide="scale" class="w-5 h-5 text-brand-alt"></i>
            </div>
            <div>
                <p class="font-bold text-sm text-fg-heading tracking-tight">PORTAL ADMIN</p>
                <p class="text-[10px] text-brand-alt font-bold uppercase tracking-widest">Pengadilan Tinggi Tanjungkarang</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 bg-white/75 backdrop-blur-md border border-border-default px-4 py-2 rounded-default shadow-xs">
            <div class="w-8 h-8 rounded-full bg-brand-strong text-white font-bold shrink-0 flex items-center justify-center text-xs">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-xs font-bold text-fg-heading leading-tight">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-[9px] text-fg-body-subtle">Administrator</p>
            </div>
            <div class="h-6 w-[1px] bg-border-default"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-fg-body-subtle hover:text-fg-danger-strong hover:bg-danger-soft rounded-default transition-all duration-200" title="Keluar dari Sistem">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Container -->
    <main class="relative z-10 my-auto w-full max-w-5xl mx-auto px-6 py-12 flex flex-col items-center">
        <!-- Greetings Section -->
        <div class="text-center mb-12 max-w-2xl animate-fade-in">
            <h2 class="text-h4 text-fg-body-subtle font-medium m-0">Selamat Datang,</h2>
            <h1 class="text-h2 text-fg-heading font-black mt-1 mb-3 leading-tight tracking-tight">
                {{ auth()->user()->name ?? 'Administrator' }}
            </h1>
            <p class="text-sm text-fg-body-subtle font-medium">
                Silakan pilih sistem aplikasi yang ingin Anda kelola di bawah ini.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">
            <!-- Card E-Riset -->
            <div class="card-interactive bg-white/95 backdrop-blur-xl border border-border-default p-8 flex flex-col justify-between hover-glow animate-fade-up" style="animation-delay: 0.1s;">
                <div>
                    <!-- Icon Wrapper -->
                    <div class="w-16 h-16 bg-brand-alt-soft rounded-base flex items-center justify-center mb-6 shadow-sm border border-brand-alt/25">
                        <span class="text-3xl">📄</span>
                    </div>
                    <!-- Content -->
                    <h3 class="text-h3 text-fg-heading font-extrabold m-0 tracking-tight">E-RISET</h3>
                    <p class="text-xs text-brand-alt-strong font-bold uppercase tracking-wider mt-1">Sistem Perizinan Penelitian</p>
                    <p class="text-sm text-fg-body-subtle font-medium mt-4 leading-relaxed">
                        Kelola seluruh permohonan izin penelitian, data persyaratan dokumen, template surat, FAQ, dan pengumuman.
                    </p>
                </div>
                <!-- Action Button -->
                <div class="mt-8">
                    <a href="{{ route('admin.dashboard') }}" class="w-full bg-brand hover:bg-brand-medium text-white font-bold py-4 px-6 rounded-default shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 group cursor-pointer text-sm">
                        Masuk E-RISET
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Card Edvokat -->
            <div class="card-interactive bg-white/95 backdrop-blur-xl border border-border-default p-8 flex flex-col justify-between hover-glow animate-fade-up" style="animation-delay: 0.25s;">
                <div>
                    <!-- Icon Wrapper -->
                    <div class="w-16 h-16 bg-brand-softer rounded-base flex items-center justify-center mb-6 shadow-sm border border-brand-soft/25">
                        <span class="text-3xl">⚖️</span>
                    </div>
                    <!-- Content -->
                    <h3 class="text-h3 text-fg-heading font-extrabold m-0 tracking-tight">EDVOKAT</h3>
                    <p class="text-xs text-brand-medium font-bold uppercase tracking-wider mt-1">Sistem Administrasi Advokat</p>
                    <p class="text-sm text-fg-body-subtle font-medium mt-4 leading-relaxed">
                        Kelola data advokat, pendaftaran, administrasi berkas, serta verifikasi keanggotaan advokat.
                    </p>
                </div>
                <!-- Action Button -->
                <div class="mt-8">
                    <a href="{{ route('admin.edvokat') }}" class="w-full bg-white text-fg-heading border-2 border-border-default hover:border-brand font-bold py-3.5 px-6 rounded-default shadow-2xs hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 group cursor-pointer text-sm">
                        Masuk EDVOKAT
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full text-center py-6 text-[11px] text-fg-body-subtle font-medium animate-fade-in">
        &copy; {{ date('Y') }} Pengadilan Tinggi Tanjungkarang. All rights reserved.
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
