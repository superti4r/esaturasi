<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img
                    alt="image"
                    src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('module/Stisla/avatar.png') }}"
                    class="rounded-circle mr-1"
                    width="35">
                <div class="d-sm-none d-lg-inline-block">
                    Hi, {{ Auth::user()->nama }}
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Menu</div>
                <a href="{{ auth()->user()->role == 'administrator' ? route('administrator.settings') : route('guru.settings') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
