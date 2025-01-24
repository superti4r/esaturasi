@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Edit Data User</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="/administrator/user">User</a></div>
        <div class="breadcrumb-item">Edit</div>
      </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Edit Data User</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.user.edit.post', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input type="text" name="nik" id="nik" class="form-control" value="{{ $user->nik }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ $user->nama }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ $user->tanggal_lahir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ $user->tempat_lahir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="alamat" id="alamat" class="form-control">{{ $user->alamat }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Akses</label>
                        <div class="col-sm-10">
                            <select name="role" id="role" class="form-control" required>
                                <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto_profil" class="col-sm-2 col-form-label">Foto Profil</label>
                        <div class="col-sm-10">
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="verified" class="col-sm-2 col-form-label">Status Verifikasi</label>
                        <div class="col-sm-10">
                            <select name="verified" id="verified" class="form-control" required>
                                <option value="1" {{ $user->email_verified_at ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="0" {{ !$user->email_verified_at ? 'selected' : '' }}>Tidak Terverifikasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                            <a href="/administrator/user" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
