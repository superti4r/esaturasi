@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Kelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/kelas">Kelas</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('administrator.kelas.update', $kelas->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas</label>
                                <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                                @error('nama_kelas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="jurusan_id">Jurusan</label>
                                <select class="form-control @error('jurusan_id') is-invalid @enderror" id="jurusan_id" name="jurusan_id" required>
                                    <option value="" disabled>Pilih Jurusan</option>
                                    @foreach($jurusan as $item)
                                        <option value="{{ $item->id }}" {{ old('jurusan_id', $kelas->jurusan_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('administrator.kelas') }}" class="btn btn-danger">Batal</a>
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
