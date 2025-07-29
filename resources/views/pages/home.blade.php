@extends('layouts.main')

@section('title', 'PSB Al-Mukmin Ngruki - Pendaftaran Santri Baru')
@section('meta_description', 'Pendaftaran santri baru tahun 2026/2027 - Pondok Pesantren Islam Al Mukmin Ngruki')

@section('content')
    <!-- Frame 1: Hero Section -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Ctext x=\"30\" y=\"30\" text-anchor=\"middle\" dy=\"0.35em\" font-size=\"24\" font-family=\"Arial\"%3EA%3C/text%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <!-- Header -->
        <header class="relative z-10 px-6 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">A</span>
                    </div>
                    <span class="text-xl font-bold text-gray-800">PSB Al-Mukmin Ngruki</span>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="#" class="px-4 py-2 rounded-lg bg-white bg-opacity-20 text-gray-800 hover:bg-opacity-30 transition-all">Beranda</a>
                    <a href="#" class="px-4 py-2 rounded-lg bg-white bg-opacity-20 text-gray-800 hover:bg-opacity-30 transition-all">Formulir</a>
                    <a href="#" class="px-4 py-2 rounded-lg bg-white bg-opacity-20 text-gray-800 hover:bg-opacity-30 transition-all">Cek Status</a>
                    <a href="#" class="px-4 py-2 rounded-lg bg-yellow-400 text-gray-800 hover:bg-yellow-500 transition-all font-semibold">Login</a>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg bg-white bg-opacity-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Hero Banner -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
            <div class="bg-gradient-to-r from-teal-700 to-green-800 rounded-3xl p-8 md:p-12 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M20 20c0 11.046-8.954 20-20 20s-20-8.954-20-20 8.954-20 20-20 20 8.954 20 20z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>

                <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between">
                    <!-- Left Content -->
                    <div class="lg:w-1/2 text-white mb-8 lg:mb-0">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                            Pondok Pesantren Islam Al Mukmin Ngruki
                        </h1>
                        <p class="text-xl md:text-2xl mb-8 text-gray-200">
                            Pendaftaran santri baru tahun 2026/2027
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#" class="px-8 py-4 bg-yellow-400 text-gray-800 rounded-full font-semibold hover:bg-yellow-500 transition-all text-center">
                                Daftar
                            </a>
                            <a href="#" class="px-8 py-4 border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-gray-800 transition-all text-center">
                                Chat Admin
                            </a>
                        </div>
                    </div>

                    <!-- Right Content - Image -->
                    <div class="lg:w-1/2 relative">
                        <div class="relative">
                            <!-- Random Student Image -->
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop&crop=face" 
                                 alt="Santri dengan prestasi" 
                                 class="w-80 h-96 object-cover rounded-2xl shadow-2xl">
                            
                            <!-- Decorative Elements -->
                            <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-pulse"></div>
                            <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-yellow-400 rounded-full animate-pulse delay-1000"></div>
                            <div class="absolute top-1/2 -right-8 w-4 h-4 bg-orange-400 rounded-full animate-pulse delay-500"></div>
                            <div class="absolute top-1/4 -left-4 w-5 h-5 bg-orange-400 rounded-full animate-pulse delay-1500"></div>
                            <div class="absolute bottom-1/4 -left-6 w-3 h-3 bg-orange-400 rounded-full animate-pulse delay-2000"></div>
                        </div>
                    </div>
                </div>

                <!-- Scroll Down Button -->
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2">
                    <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 pb-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <!-- Left - Accreditation -->
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div>
                        <p class="text-gray-600 font-medium">Akreditasi</p>
                        <div class="flex items-center space-x-1 mt-1">
                            <div class="flex space-x-1">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center ml-2">
                                <span class="text-white font-bold text-sm">A</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right - Social Media -->
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 font-medium">Share</p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-8 h-8 bg-black rounded-full flex items-center justify-center hover:bg-gray-800 transition-all">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 transition-all">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center hover:bg-blue-600 transition-all">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center hover:bg-red-700 transition-all">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection