@vite('resources/css/navbarsantri.css')
<div class="nav-wrapper">
    <nav class="modern-nav">
        <!-- Logo dengan optimasi -->
        <div class="logo-container">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/LOGO-PPIN-NGRUKI.png') }}"
                     alt="Logo PPIN Ngruki"
                     class="logo"
                     width="36"
                     height="36"
                     loading="lazy"
                     decoding="async"
                     onload="this.setAttribute('loaded', 'true')">
            </a>
        </div>
        <!-- Navigation Menu -->
        <div class="nav-container">
            @if(session()->has('is_santri_logged_in'))
                <a href="{{ route('santri.dashboard.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}"
                   class="nav-item {{ request()->routeIs('santri.dashboard.index') ? 'active' : '' }}">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path d="M9 22V12h6v10"/>
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('santri.pembayaran.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}"
                   class="nav-item {{ request()->routeIs('santri.pembayaran.index') ? 'active' : '' }}">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 6h20v12H2z"/>
                        <path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        <path d="M6 10v4M18 10v4"/>
                    </svg>
                    <span>Pembayaran</span>
                </a>
                <a href="{{ route('santri.ujian.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}"
                   class="nav-item {{ request()->routeIs('santri.ujian.index') ? 'active' : '' }}">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Ujian</span>
                </a>
                <a href="{{ route('santri.data', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}"
                   class="nav-item {{ request()->routeIs('santri.data') ? 'active' : '' }}">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Data</span>
                </a>
            @else
                <a href="{{ route('santri.check.form') }}"
                   class="nav-item {{ request()->routeIs('santri.check.form') || request()->routeIs('santri.check') ? 'active' : '' }}">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    <span>Login Santri</span>
                </a>
            @endif
        </div>
    </nav>
</div>

@push('scripts')
<script>
    // Add loading state
    document.addEventListener('livewire:navigating', () => {
        document.body.classList.add('navigating');
    });

    document.addEventListener('livewire:navigated', () => {
        document.body.classList.remove('navigating');
    });
</script>
@endpush
