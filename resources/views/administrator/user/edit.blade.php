@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Data User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/user">User</a></div>
            <div class="breadcrumb-item">Edit User</div>
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
                            <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik', $user->nik) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="alamat" id="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
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
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control" accept="image/*">
                            @if($user->foto_profil)
                                <div id="crop-area" class="mt-3">
                                    <img id="image-preview" src="{{ asset('storage/'.$user->foto_profil) }}" style="max-width: 100%; display: block;">
                                </div>
                            @else
                                <div id="crop-area" class="mt-3">
                                    <img id="image-preview" style="max-width: 100%; display: none;">
                                </div>
                            @endif
                            <button type="button" id="crop-button" class="btn btn-primary mt-3" style="display: none;">Crop & Simpan</button>
                            <input type="hidden" name="cropped_image" id="cropped_image">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password" class="form-control">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
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

@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<style>
    #crop-area {
        max-width: 500px;
        max-height: 500px;
        margin: 0 auto;
    }

    #image-preview {
        max-width: 100%;
        max-height: 400px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('foto_profil');
        const imagePreview = document.getElementById('image-preview');
        const cropButton = document.getElementById('crop-button');
        const croppedImageInput = document.getElementById('cropped_image');
        let cropper;

        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    cropButton.style.display = 'inline-block';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imagePreview, {
                        aspectRatio: 1,
                        viewMode: 2,
                        responsive: true,
                        autoCropArea: 1,
                        preview: null
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        cropButton.addEventListener('click', () => {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                });
                canvas.toBlob((blob) => {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        croppedImageInput.value = reader.result;
                        Swal.fire({
                            icon: 'success',
                            title: 'Gambar berhasil disimpan!',
                            text: 'Anda telah berhasil memotong dan menyimpan gambar profil.',
                            confirmButtonText: 'Ok'
                        });
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memotong gambar!',
                    text: 'Silakan coba lagi.',
                    confirmButtonText: 'Ok'
                });
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        @endif
    });
</script>
@endsection
