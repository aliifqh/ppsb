<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
        });
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Sidebar Styles */
        .sidebar {
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 16rem; /* 256px / 16 = 16rem (sama dengan w-64) */
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                z-index: 50;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .bottom-menu {
                display: flex;
            }
        }

        @media (min-width: 769px) {
            .bottom-menu {
                display: none;
            }
        }

        /* Bottom Menu Styles */
        .bottom-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(42, 96, 97, 0.1);
            padding: 0.75rem 1rem;
            z-index: 40;
        }

        .bottom-menu-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            color: #2A6061;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .bottom-menu-item.active {
            color: #2A6061;
        }

        .bottom-menu-item i {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .bottom-menu-item span {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Add padding to main content for mobile */
        @media (max-width: 768px) {
            main {
                padding-bottom: 100px !important;
            }
        }

        /* Custom scrollbar untuk sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }" data-user-name="{{ Auth::user()->name ?? 'Admin' }}" data-user-email="{{ Auth::user()->email }}">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" x-show="sidebarOpen" @click="sidebarOpen = false"></div>

    <!-- Sidebar & Bottom Bar Vue -->
    <div id="admin-nav"></div>

    <!-- Main Content -->
    <div class="main-content flex-1 flex flex-col min-h-screen">
        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
