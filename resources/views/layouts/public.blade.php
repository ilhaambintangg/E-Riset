<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RISET | Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js + Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="font-sans bg-neutral-primary selection:bg-brand-soft selection:text-fg-brand-strong text-fg-body antialiased">
    
    @include('components.navbar')
    
    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>
