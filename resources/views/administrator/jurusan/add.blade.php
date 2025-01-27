@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Jurusan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/jurusan">Jurusan</a></div>
            <div class="breadcrumb-item">Tambah Jurusan</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Form Tambah Jurusan</h2>
        <p class="section-lead">Silakan isi form di bawah ini untuk menambahkan jurusan baru.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Tambah Jurusan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('administrator.jurusan.add.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="kode_jurusan">Kode Jurusan</label>
                                <input type="text" class="form-control @error('kode_jurusan') is-invalid @enderror" id="kode_jurusan" name="kode_jurusan" value="{{ old('kode_jurusan') }}" required>
                                @error('kode_jurusan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_jurusan">Nama Jurusan</label>
                                <input type="text" class="form-control @error('nama_jurusan') is-invalid @enderror" id="nama_jurusan" name="nama_jurusan" value="{{ old('nama_jurusan') }}" required>
                                @error('nama_jurusan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('administrator.jurusan') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                showConfirmButton: false,
                timer: 5000
            });
        @endif
    });
</script>
@endsection
