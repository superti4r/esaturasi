@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Soal Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('guru.ujian.index') }}">Daftar Ujian</a></div>
            <div class="breadcrumb-item">Edit Soal Ujian</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Edit Soal</h2>
        <p class="section-lead">Silakan perbarui soal ujian di bawah ini.</p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Form Edit Soal</h4>
                    </div>
                    <div class="card-body">
                        <form id="soal-form" action="{{ route('guru.soal.update', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div id="questions-container">
                                @foreach($soalList as $index => $soal)
                                    <div class="border p-3 mb-3 question-item" id="question-{{ $index + 1 }}">
                                        <h5>Soal {{ $index + 1 }}</h5>
                                        <div class="form-group">
                                            <label>Pertanyaan</label>
                                            <textarea class="form-control" name="pertanyaan[]" required>{{ $soal->pertanyaan }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Gambar (Opsional)</label>
                                            <input type="file" class="form-control" name="file_path[]">
                                            @if($soal->file_path)
                                                <img src="{{ asset('storage/' . $soal->file_path) }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Pilihan Jawaban</label>
                                            <ul>
                                                <li>A. <input type="text" name="pilihan_a[]" class="form-control" required value="{{ $soal->pilihan_a }}"></li>
                                                <li>B. <input type="text" name="pilihan_b[]" class="form-control" required value="{{ $soal->pilihan_b }}"></li>
                                                <li>C. <input type="text" name="pilihan_c[]" class="form-control" required value="{{ $soal->pilihan_c }}"></li>
                                                <li>D. <input type="text" name="pilihan_d[]" class="form-control" required value="{{ $soal->pilihan_d }}"></li>
                                            </ul>
                                        </div>

                                        <div class="form-group">
                                            <label>Kunci Jawaban</label>
                                            <select class="form-control" name="jawaban[]" required>
                                                <option value="A" {{ $soal->jawaban == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="B" {{ $soal->jawaban == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="C" {{ $soal->jawaban == 'C' ? 'selected' : '' }}>C</option>
                                                <option value="D" {{ $soal->jawaban == 'D' ? 'selected' : '' }}>D</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Bobot Jawaban</label>
                                            <input type="number" class="form-control" name="skor[]" min="0" required value="{{ $soal->skor }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
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
