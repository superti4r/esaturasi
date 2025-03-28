@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Mata Pelajaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/mapel">Mata Pelajaran</a></div>
            <div class="breadcrumb-item">Tambah Mata Pelajaran</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('administrator.mapel.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_mapel">Nama Mata Pelajaran</label>
                                <input type="text" name="nama_mapel" id="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror" value="{{ old('nama_mapel') }}" required placeholder="Masukkan Nama Mata Pelajaran, misal : PPKN">
                                @error('nama_mapel')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('administrator.mapel') }}" class="btn btn-danger">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<script src="{{ asset ('module/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>

@section('scripts')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif
@endsection
