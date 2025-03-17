@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Data Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/siswa">Siswa</a></div>
            <div class="breadcrumb-item">Edit Siswa</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Edit Data Siswa</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="nisn" class="col-sm-2 col-form-label">NISN</label>
                        <div class="col-sm-10">
                            <input type="text" name="nisn" id="nisn" class="form-control" required value="{{ $siswa->nisn }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama" id="nama" class="form-control" required value="{{ $siswa->nama }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required value="{{ $siswa->tempat_lahir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="alamat" id="alamat" class="form-control" required>{{ $siswa->alamat }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                        <div class="col-sm-10">
                            <select name="kelas_id" id="kelas" class="form-control" required>
                                @foreach($kelas as $item)
                                    <option value="{{ $item->id }}" {{ $siswa->kelas_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                <option value="laki-laki" {{ $siswa->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ $siswa->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto_profil" class="col-sm-2 col-form-label">Foto Profil</label>
                        <div class="col-sm-10">
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control" accept="image/*">
                            <div id="crop-area" class="mt-3">
                                @if($siswa->foto_profil)
                                    <img id="image-preview" src="{{ asset('storage/' . $siswa->foto_profil) }}" style="max-width: 100%;">
                                @else
                                    <img id="image-preview" style="max-width: 100%; display: none;">
                                @endif
                            </div>
                            <button type="button" id="crop-button" class="btn btn-primary mt-3" style="display: none;">Crop & Simpan</button>
                            <input type="hidden" name="cropped_image" id="cropped_image">
                            <small class="form-text text-muted">Pilih foto, dan sesuaikan, jangan lupa untuk di crop & simpan.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tahun_masuk" class="col-sm-2 col-form-label">Tahun Masuk</label>
                        <div class="col-sm-10">
                            <input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" required value="{{ $siswa->tahun_masuk }}" min="2000" max="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" id="status" class="form-control" required>
                                <option value="Aktif" {{ $siswa->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ $siswa->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" id="email" class="form-control" required value="{{ $siswa->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10 input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                            <a href="/administrator/siswa" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
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

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        var icon = this.querySelector('i');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection
