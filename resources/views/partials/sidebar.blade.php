<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">E-Saturasi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">ES</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown">
                <a href="{{ Auth::user()->role === 'administrator' ? '/administrator' : '/guru' }}" class="nav-link">
                    <i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>
            @if(auth()->user()->role === 'administrator')
            <li class="menu-header">Admin</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>Data Pengguna</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route ('administrator.user') }}">User</a></li>
                    <li><a class="nav-link" href="{{ route ('administrator.siswa') }}">Siswa</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="{{ route ('administrator.kelas') }}" class="nav-link"><i class="fas fa-building"></i> <span>Kelas</span></a>
                <a href="{{ route ('administrator.jurusan') }}" class="nav-link"><i class="fas fa-bookmark"></i> <span>Jurusan</span></a>
                <a href="#" class="nav-link"><i class="fas fa-list"></i> <span>Jadwal</span></a>
                <a href="{{ route ('administrator.mapel')}}" class="nav-link"><i class="fas fa-book"></i> <span>Mata Pelajaran</span></a>
                <a href="#" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> <span>Pembagian Jadwal</span></a>
                <a href="#" class="nav-link"><i class="fas fa-user-clock"></i> <span>Tahun Ajaran</span></a>
                <a href="{{ route ('administrator.bab') }}" class="nav-link"><i class="fas fa-table"></i> <span>Bab</span></a>
                <a href="{{ route ('administrator.pengumuman') }}" class="nav-link"><i class="fas fa-volume-up"></i> <span>Pengumuman</span></a>
            </li>
            @endif
            @if(auth()->user()->role === 'guru')
            <li class="menu-header">Guru</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown"><i class="fas fa-list"></i> <span>Jadwal Saya</span></a>
                <a href="#" class="nav-link"><i class="fas fa-building"></i> <span>Kelas</span></a>
                <a href="#" class="nav-link"><i class="fas fa-external-link-alt"></i> <span>Statistik</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
