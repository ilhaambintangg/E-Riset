<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-primary-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDVOKAT - Sedang Dalam Pengembangan</title>
    @vite(['resources/css/app.css'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full font-sans text-fg-body bg-neutral-primary-soft antialiased flex flex-col justify-between relative overflow-hidden">

    <!-- Decorative background -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-br from-brand-alt/10 to-brand-alt/5 blur-3xl"></div>
        <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-tr from-brand-strong/15 to-brand/5 blur-3xl"></div>
    </div>

    <!-- Top bar -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex justify-start items-center animate-fade-in">
        <a href="{{ route('admin.portal') }}" class="flex items-center gap-2 text-xs font-bold text-fg-body hover:text-fg-brand transition-colors bg-white/80 border border-border-default px-4 py-2.5 rounded-default shadow-2xs">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Portal
        </a>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 my-auto w-full max-w-md mx-auto px-6 py-12 flex flex-col items-center text-center animate-fade-up">
        <!-- Coming Soon Icon Graphic -->
        <div class="relative w-28 h-28 bg-white border border-border-default rounded-base flex items-center justify-center shadow-md mb-8">
            <div class="absolute -top-3 -right-3 bg-brand-alt text-brand-strong text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border-2 border-white shadow-sm">
                Coming Soon
            </div>
            <i data-lucide="construction" class="w-12 h-12 text-brand-alt animate-bounce"></i>
        </div>

        <h1 class="text-h2 text-fg-heading font-black tracking-tight m-0">EDVOKAT</h1>
        <p class="text-xs text-brand-medium font-bold uppercase tracking-widest mt-1">Sistem Administrasi Advokat</p>
        
        <div class="h-[2px] w-16 bg-border-default my-6 mx-auto"></div>

        <h2 class="text-lg font-bold text-fg-heading m-0">Sedang Dalam Pengembangan</h2>
        <p class="text-sm text-fg-body-subtle font-medium mt-3 leading-relaxed">
            Halaman ini sedang dipersiapkan untuk mengelola administrasi advokat di lingkungan Pengadilan Tinggi Tanjungkarang.
        </p>

        <a href="{{ route('admin.portal') }}" class="mt-8 w-full bg-brand hover:bg-brand-medium text-white font-bold py-3.5 px-6 rounded-default shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer text-sm">
            <i data-lucide="home" class="w-4 h-4"></i>
            Kembali ke Portal Utama
        </a>
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
