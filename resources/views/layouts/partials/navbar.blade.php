@vite('resources/css/navbar.css')
<script>
// Navbar Hide Login
document.addEventListener('DOMContentLoaded', function () {
    function toggleLoginVisibility() {
        const loginBtn = document.querySelector('.login-desktop');
        if (!loginBtn) return;
        if (window.innerWidth < 1280) {
            loginBtn.style.setProperty('display', 'none', 'important');
        } else {
            loginBtn.style.setProperty('display', 'flex', 'important');
        }
    }

    toggleLoginVisibility();
    window.addEventListener('resize', toggleLoginVisibility);

    // Tambahkan event listener untuk scroll
    const modernNav = document.querySelector('.modern-nav');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 0) {
            modernNav.style.marginTop = '0';
        } else {
            modernNav.style.marginTop = '10px';
        }
    });
});
</script>
<div class="nav-wrapper">
    <nav class="modern-nav">
        <!-- Logo -->
        <div class="logo-container">
            <a href="/">
                <img src="{{ asset('img/logo-ppin.png') }}" alt="Logo Al Mukmin" class="logo"
                    loading="lazy"
                    onload="this.style.display='block'">
            </a>
        </div>
        <!-- Navigation Menu -->
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" loading="lazy"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path d="M9 22V12h6v10"/></svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('formulir.index') }}" class="nav-item {{ request()->routeIs('formulir.index') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" loading="lazy"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                <span>Formulir</span>
            </a>
            <a href="{{ route('santri.check.form') }}" class="nav-item {{ request()->routeIs('santri.check.form') || request()->routeIs('santri.check') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" loading="lazy"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                <span>Cek Status</span>
            </a>
            @guest
                <a href="/login" class="nav-item login-desktop">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" loading="lazy"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    <span>Login Admin</span>
                </a>
            @else
                <div class="nav-item user-dropdown" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" class="user-btn">
                        <a href="/admin" class="text-gray-800 hover:text-gray-600">Dashboard</a>
                    </button>
                </div>
            @endguest
        </div>
    </nav>
</div>
