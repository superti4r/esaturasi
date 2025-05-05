@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Tahun Pelajaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/arsip">Tahun Pelajaran</a></div>
            <div class="breadcrumb-item">Edit Tahun Pelajaran</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('administrator.arsip.update', $arsip->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama">Nama Tahun Pelajaran</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $arsip->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                                    <option value="ganjil" {{ old('semester', $arsip->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $arsip->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="aktif" {{ old('status', $arsip->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak aktif" {{ old('status', $arsip->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                                <a href="{{ route('administrator.arsip') }}" class="btn btn-danger">Batal</a>
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
