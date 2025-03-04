@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/user">User</a></div>
            <div class="breadcrumb-item">Detail User</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('module/Stisla/avatar.png') }}"
                             alt="Foto Profil" class="img-fluid rounded-circle mb-4" width="200">
                        <h5>{{ $user->nama }}</h5>
                        <p class="text-muted">{{ $user->role === 'administrator' ? 'Administrator' : ucfirst($user->role) }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>NIK</th>
                                    <td>{{ $user->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $user->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td>{{ $user->tempat_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $user->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td>
                                        <span class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-danger' }}">
                                            {{ $user->email_verified_at ? 'Terverifikasi' : 'Tidak Terverifikasi' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <a href="{{ route('administrator.user') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
