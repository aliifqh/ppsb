<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500&display=swap" rel="stylesheet"> --}}
    <style>
        @keyframes gentleGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: 'Google Sans', sans-serif;
            background: linear-gradient(120deg, rgba(35, 166, 213, 0.8), rgba(35, 213, 171, 0.8));
            background-size: 400% 400%;
            animation: gentleGradient 15s ease infinite;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #1a73e8, #23d5ab);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .logo:hover::after {
            width: 80%;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-5">
    <div class="w-full max-w-[450px] p-10 bg-white/95 backdrop-blur-sm rounded-[20px] shadow-[0_8px_32px_0_rgba(31,38,135,0.2)] text-center animate-[fadeIn_0.8s_ease-out]">
        <div class="logo relative inline-block mb-[50px]">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/img/LOGO-PPIN-NGRUKI.png') }}" alt="Logo" class="w-[350px] h-auto transition-all duration-300 hover:translate-y-[-3px] hover:drop-shadow-[0_6px_12px_rgba(0,0,0,0.1)]">
            </a>
        </div>

        <p class="text-[#202124] text-base mb-2.5">Masuk dengan akun {{ $allowedDomain ?? 'almukmin.sch.id' }}</p>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <a href="{{ route('google.login') }}"
           class="google-btn relative inline-flex items-center px-[30px] h-[50px] bg-white border-none rounded-[25px] cursor-pointer no-underline transition-all duration-300 overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:translate-y-[-2px] hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] hover:scale-[1.02] active:scale-[0.98] active:translate-y-[1px]">
            <svg class="mr-3 w-6 h-6 transition-transform duration-300 group-hover:rotate-[360deg]" viewBox="0 0 24 24">
                <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span class="text-[#202124] text-base font-medium tracking-[0.5px] transition-all duration-300 group-hover:translate-x-1">Masuk dengan Google</span>
        </a>

        <div class="mt-[50px] text-[#202124] text-sm">
            <p class="leading-relaxed">
                Dengan melanjutkan, Anda menyetujui
                <a href="{{ route('home') }}/terms" target="_blank"
                   class="text-emerald-600 hover:text-emerald-700 transition-colors duration-200 font-medium underline decoration-2 decoration-emerald-200 hover:decoration-emerald-400">
                    Syarat Layanan
                </a>
                dan
                <a href="{{ route('home') }}/privacy" target="_blank"
                   class="text-emerald-600 hover:text-emerald-700 transition-colors duration-200 font-medium underline decoration-2 decoration-emerald-200 hover:decoration-emerald-400">
                    Kebijakan Privasi
                </a>
                kami.
            </p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mencegah form submission default
            document.querySelector('form')?.addEventListener('submit', function(e) {
                e.preventDefault();
            });

            // Menangani input email
            const emailInput = document.querySelector('input[type="email"]');
            if (emailInput) {
                emailInput.addEventListener('input', function(e) {
                    const value = e.target.value;
                    if (value.includes('@')) {
                        const parts = value.split('@');
                        if (parts[1] !== 'almukmin.sch.id') {
                            e.target.value = parts[0] + '@almukmin.sch.id';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
