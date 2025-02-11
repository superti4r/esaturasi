@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/siswa">Siswa</a></div>
            <div class="breadcrumb-item">Detail Siswa</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $siswa->foto_profil ? asset('storage/' . $siswa->foto_profil) : asset('_sys/img/avatar/avatar-1.png') }}"
                             alt="Foto Profil" class="img-fluid rounded-circle mb-4" width="200">
                        <h5>{{ $siswa->nama }}</h5>
                        <p class="text-muted">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>NISN</th>
                                    <td>{{ $siswa->nisn }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $siswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td>{{ $siswa->tempat_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $siswa->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Masuk</th>
                                    <td>{{ $siswa->tahun_masuk }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ ucfirst($siswa->jenis_kelamin) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $siswa->status == 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $siswa->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $siswa->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <a href="{{ route('administrator.siswa') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
