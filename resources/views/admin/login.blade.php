<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral-primary-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Riset PT Tanjungkarang</title>
    @vite(['resources/css/app.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full font-sans text-fg-body bg-neutral-primary-soft antialiased flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative background -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-br from-brand-alt/20 to-brand-alt/5 blur-3xl"></div>
        <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-tr from-brand-strong/10 to-brand/5 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10 animate-fade-up">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-brand-strong rounded-base flex items-center justify-center mx-auto mb-6 shadow-xl shadow-brand-strong/20">
                <i data-lucide="scale" class="w-8 h-8 text-brand-alt"></i>
            </div>
            <h1 class="text-h3 text-fg-heading m-0 tracking-tight mb-2">Admin Panel</h1>
            <p class="text-xs text-fg-body-subtle font-medium">Sistem E-Riset Pengadilan Tinggi Tanjungkarang</p>
        </div>

        <!-- Login Card -->
        <div class="card-static bg-white/90 backdrop-blur-xl p-8 sm:p-10 border border-border-default">
            @if($errors->any())
                <div class="bg-danger-soft border border-border-danger-subtle p-4 rounded-default mb-6 text-xs flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-fg-danger shrink-0"></i>
                    <div>
                        <p class="font-bold text-fg-danger-strong m-0">Gagal Login</p>
                        <p class="text-fg-danger-strong mt-0.5 m-0">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="input-label">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus
                               class="w-full pl-11 pr-4 py-3 bg-neutral-primary-soft border border-border-default-medium rounded-default text-xs text-fg-heading font-medium focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand-soft transition-all placeholder:text-fg-body-subtle placeholder:font-normal"
                               placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="input-label">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                        <input type="password" name="password" id="password-input" required
                               class="w-full pl-11 pr-12 py-3 bg-neutral-primary-soft border border-border-default-medium rounded-default text-xs text-fg-heading font-medium focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand-soft transition-all placeholder:text-fg-body-subtle placeholder:font-normal"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-fg-body-subtle hover:text-fg-brand focus:outline-none">
                            <span id="eye-icon-open"><i data-lucide="eye" class="w-4 h-4"></i></span>
                            <span id="eye-icon-closed" class="hidden"><i data-lucide="eye-off" class="w-4 h-4"></i></span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-brand hover:bg-brand-medium text-white font-bold py-3.5 rounded-default shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-xs">
                    Masuk ke Sistem
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-8 text-[11px] text-fg-body-subtle font-medium">
            &copy; {{ date('Y') }} Pengadilan Tinggi Tanjungkarang
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePasswordVisibility() {
            const input = document.getElementById('password-input');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
