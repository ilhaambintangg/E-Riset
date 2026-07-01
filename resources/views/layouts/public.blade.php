<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RISET | Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Google Fonts: Outfit & Quicksand -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js + Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="font-sans bg-neutral-primary-soft selection:bg-brand-alt-soft selection:text-fg-brand-alt-strong text-fg-body antialiased">
    
    @include('components.navbar')
    
    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @include('components.chat-widget')

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>
