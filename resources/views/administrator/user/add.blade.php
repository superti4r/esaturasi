@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Data User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/user">User</a></div>
            <div class="breadcrumb-item">Tambah User</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Tambah Data User</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.user.add.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->has('nik'))
                    <div class="alert alert-danger">
                        {{ $errors->first('nik') }}
                    </div>
                @endif
                    <div class="form-group row">
                        <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input type="text" name="nik" id="nik" class="form-control" required placeholder="Masukkan NIK Anda.">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan Nama Lengkap Anda.">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required placeholder="Masukkan Tempat Lahir Anda.">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="alamat" id="alamat" class="form-control" required placeholder="Masukkan Alamat Anda."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Akses</label>
                        <div class="col-sm-10">
                            <select name="role" id="role" class="form-control" required>
                                <option value="administrator">Administrator</option>
                                <option value="guru">Guru</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto_profil" class="col-sm-2 col-form-label">Foto Profil</label>
                        <div class="col-sm-10">
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control" accept="image/*">
                            <div id="crop-area" class="mt-3">
                                <img id="image-preview" style="max-width: 100%; display: none;">
                            </div>
                            <button type="button" id="crop-button" class="btn btn-primary mt-3" style="display: none;">Crop & Simpan</button>
                            <input type="hidden" name="cropped_image" id="cropped_image">
                            <small class="form-text text-muted">Pilih foto, dan sesuaikan, jangan lupa untuk di crop & simpan.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" id="email" class="form-control" required placeholder="Masukkan Alamat Email AKtif Anda.">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan Password Minimal 8 Karakter.">
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
<link rel="stylesheet" href="{{ asset ('module/Cropper/cropper.min.css') }}">
<script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset ('module/Cropper/cropper.min.js') }}"></script>
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
                            confirmButtonText: 'Oke'
                        });
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memotong gambar!',
                    text: 'Silakan coba lagi.',
                    confirmButtonText: 'Oke'
                });
            }
        });
    });
</script>
@endsection
