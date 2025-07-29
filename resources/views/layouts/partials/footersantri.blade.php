<!-- Footer -->
<div class="mt-8 border-t border-gray-200 pt-6">
    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-4">
            @if(request()->routeIs('santri.check.form'))
                <a href="{{ route('home') }}" class="text-teal-600 hover:text-teal-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Beranda
                </a>
            @elseif(request()->routeIs('santri.dashboard.index'))
                <a href="{{ route('home') }}" class="text-teal-600 hover:text-teal-500">
                    Kembali ke Beranda
                </a>
            @else
                <a href="{{ route('santri.dashboard.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}" class="text-teal-600 hover:text-teal-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
        @if(session()->has('is_santri_logged_in'))
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <i class="fas fa-user-circle text-teal-600 text-xl mr-2"></i>
                <span class="text-sm text-gray-700">{{ session('nama_lengkap') }}</span>
            </div>
            <form action="{{ route('santri.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
