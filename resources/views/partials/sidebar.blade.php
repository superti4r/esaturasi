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
                <a href="{{ route ('administrator.mapel') }}" class="nav-link"><i class="fas fa-book"></i> <span>Mata Pelajaran</span></a>
                <a href="{{ route ('administrator.mapelperkelas') }}" class="nav-link"><i class="fas fa-list"></i> <span>Mapel Perkelas</span></a>
                <a href="{{ route ('administrator.jadwal') }}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> <span>Pembagian Jadwal</span></a>
                <a href="{{ route ('administrator.arsip') }}" class="nav-link"><i class="fas fa-archive"></i> <span>Arsip</span></a>
                <a href="{{ route ('administrator.pengumuman') }}" class="nav-link"><i class="fas fa-volume-up"></i> <span>Pengumuman</span></a>
            </li>
            @endif
            @if(auth()->user()->role === 'guru')
            <li class="menu-header">Guru</li>
            <li class="nav-item dropdown">
                <a href="{{ route ('guru.jadwal') }}" class="nav-link"><i class="fas fa-clipboard-list"></i> <span>Jadwal Saya</span></a>
                <a href="{{ route ('guru.tugas-dan-materi.index') }}" class="nav-link"><i class="fas fa-window-restore"></i><span>Kelola</span></a>
                <a href="{{ route ('guru.ujian.index') }}" class="nav-link"><i class="fas fa-file-signature"></i><span>Manajemen Ujian</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
