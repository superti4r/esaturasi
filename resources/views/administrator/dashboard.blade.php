@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>User</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalUser }}
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('administrator.user') }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Siswa</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalSiswa }}
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('administrator.siswa') }}" class="btn btn-danger btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-book"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mata Pelajaran</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalMapel }}
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('administrator.mapel') }}" class="btn btn-success btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-building"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Kelas</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalKelas }}
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('administrator.kelas') }}" class="btn btn-warning btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="text-white"><i class="fas fa-server"></i> Status Server</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><i class="fas fa-wifi"></i> Ping Server:</strong>
                            <span>{{ $pingServer }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><i class="fas fa-clock"></i> Uptime:</strong>
                            <span>{{ $uptime }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><i class="fas fa-hdd"></i> Storage Tersedia:</strong>
                            <span>{{ $storageAvailable }} / {{ $storageTotal }} GB</span>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fas fa-microchip"></i> Spesifikasi Server:</strong>
                            <ul class="mt-2">
                                <li><strong>OS:</strong> {{ $serverOS }}</li>
                                <li><strong>CPU:</strong> {{ $cpuInfo }}</li>
                                <li><strong>RAM:</strong> {{ $ramTotal }}</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white"><i class="fas fa-book-open"></i> Catatan Developer</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-bullhorn"></i> <strong>  1.</strong> Administrator wajib mengisi "Data Arsip" terlebih dahulu pada saat Production/Deploy di Hosting maupun VPS agar relasi Arsip dapat bekerja semestinya.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-bullhorn"></i> <strong>  2.</strong> Pastikan Server dalam keadaan optimal dengan cara memantau Status Server.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="text-white"><i class="fas fa-key"></i> Manajemen Token & API</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('administrator.update.register.token') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="register_token">Masukkan Token Register</label>
                            <input type="text" name="register_token" id="register_token" class="form-control" required placeholder="Masukkan token baru">
                        </div>
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-save"></i> Update Token
                        </button>
                    </form>
                    <hr>
                    <form action="{{ route('administrator.update.gemini.api') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="gemini_api_key">Masukkan API untuk Satria AI</label>
                            <input type="text" name="gemini_api_key" id="gemini_api_key" class="form-control" required placeholder="Masukkan API baru">
                        </div>
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-save"></i> Update API
                        </button>
                    </form>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Catatan:</strong> Setelah memperbarui token maupun API, website akan otomatis merefresh. Jika tampilan tidak berubah, silakan reload halaman.
                    </div>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Perhatian:</strong> Jangan beritahu token maupun API kepada siapa pun, karena bersifat sensitif. Jika token diketahui publik, sistem dapat mudah diretas.
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
