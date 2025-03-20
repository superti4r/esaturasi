@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Soal Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('guru.ujian.index') }}">Daftar Ujian</a></div>
            <div class="breadcrumb-item">Kelola Soal Ujian</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Form Tambah Soal</h2>
        <p class="section-lead">Silakan isi form di bawah ini untuk menambahkan soal baru.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Tambah Soal</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('guru.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="soal">Soal</label>
                                <textarea class="form-control @error('soal') is-invalid @enderror" id="soal" name="soal" rows="3" required>{{ old('soal') }}</textarea>
                                @error('soal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file_path">Gambar (Opsional)</label>
                                <input type="file" class="form-control @error('file_path') is-invalid @enderror" id="file_path" name="file_path">
                                @error('file_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Pilihan Jawaban</label>
                                @foreach(['a', 'b', 'c', 'd'] as $option)
                                <div class="input-group mb-2">
                                    <span class="input-group-text text-uppercase">{{ $option }}</span>
                                    <input type="text" name="pilihan_{{ $option }}" class="form-control @error('pilihan_'. $option) is-invalid @enderror" required value="{{ old('pilihan_'.$option) }}">
                                    @error('pilihan_'. $option)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="kunci_jawaban">Kunci Jawaban</label>
                                <select class="form-control @error('kunci_jawaban') is-invalid @enderror" id="kunci_jawaban" name="kunci_jawaban" required>
                                    @foreach(['a', 'b', 'c', 'd'] as $option)
                                    <option value="{{ $option }}" {{ old('kunci_jawaban') == $option ? 'selected' : '' }}>{{ strtoupper($option) }}</option>
                                    @endforeach
                                </select>
                                @error('kunci_jawaban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bobot_jawaban">Bobot Jawaban</label>
                                <input type="number" class="form-control @error('bobot_jawaban') is-invalid @enderror" id="bobot_jawaban" name="bobot_jawaban" min="1" required value="{{ old('bobot_jawaban') }}">
                                @error('bobot_jawaban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('guru.soal.index', $ujian->id) }}" class="btn btn-danger">Batal</a>
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
