<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary Meta Tags -->
    <title>{{ config('app.name', 'Pondok Pesantren Islam Al Mukmin') }}</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Pendaftaran Santri Online - Daftar dengan mudah dan pantau status pendaftaran Anda secara real-time.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', 'Pondok Pesantren - Pendaftaran Santri Online')">
    <meta property="og:description" content="@yield('meta_description', 'Sistem Pendaftaran Santri Online - Daftar dengan mudah dan pantau status pendaftaran Anda secara real-time.')">
    <meta property="og:image" content="@yield('meta_image', asset('images/og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('meta_title', 'Pondok Pesantren - Pendaftaran Santri Online')">
    <meta property="twitter:description" content="@yield('meta_description', 'Sistem Pendaftaran Santri Online - Daftar dengan mudah dan pantau status pendaftaran Anda secara real-time.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('images/og-image.jpg'))">

    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Custom Styles -->
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <title>@yield('title', 'Pondok Pesantren Al-Mukmin Ngruki')</title>

</head>
<body class="bg-gray-100 min-h-screen flex flex-col" x-data="{ loading: false }">
    <!-- Loading Overlay -->
    <div x-cloak x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-xl flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-emerald-500 border-t-transparent"></div>
            <span class="text-gray-700">Memuat...</span>
        </div>
    </div>
    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Pondok Pesantren Al-Mukmin Ngruki",
        "description": "Pondok Pesantren Al-Mukmin Ngruki adalah lembaga pendidikan Islam yang didirikan oleh Abu Bakar Ba'asyir dan Abdullah Sungkar pada tahun 1972",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. Ngruki No. 1",
            "addressLocality": "Sukoharjo",
            "addressRegion": "Jawa Tengah",
            "postalCode": "57552",
            "addressCountry": "ID"
        },
        "telephone": "+62 271 717 101",
        "email": "admin@almukminngruki.or.id",
        "url": "https://www.almukminngruki.or.id",
        "openingHours": [
            "Mo-Su 24/7"
        ],
        "sameAs": [
            "https://www.facebook.com/pondok.ngruki/",
            "https://www.instagram.com/pondok.ngruki/",
            "https://www.tiktok.com/@pondok.ngruki"
        ]
    }
    </script>

    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    {{-- @include('layouts.partials.footer') --}}

    <!-- Scripts -->
    @stack('scripts')
    @livewireScripts
</body>
</html>
