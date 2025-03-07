@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Kelola Tugas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('guru') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.tugas-dan-materi.index') }}">Kelola</a></div>
            <div class="breadcrumb-item active">Tugas</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Daftar Tugas</h2>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="text-white">
                    {{ $tugas->isEmpty() ? 'Tambah Tugas' : 'Perbarui Tugas' }}
                </h4>

                @if(!$tugas->isEmpty())
                    @php
                        $deadline = \Carbon\Carbon::parse($tugas->first()->deadline);
                        $now = \Carbon\Carbon::now();
                    @endphp

                    @if($deadline->isFuture())
                        <span class="badge badge-success">Berlangsung</span>
                    @else
                        <span class="badge badge-danger">Selesai</span>
                    @endif
                @endif
            </div>

            <div class="card-body">
                <form id="uploadTugasForm" action="{{ $tugas->isEmpty()
                      ? route('guru.tugas-dan-materi.tugas.store', ['id' => $slugData->slug])
                      : route('guru.tugas-dan-materi.tugas.update', ['id' => $tugas->first()->id]) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(!$tugas->isEmpty()) @method('PUT') @endif

                    <div class="form-group">
                        <label for="judul">Judul Tugas</label>
                        <input type="text" class="form-control" name="judul"
                               value="{{ $tugas->isEmpty() ? '' : $tugas->first()->judul }}" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3">{{ $tugas->isEmpty() ? '' : $tugas->first()->deskripsi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Batas Waktu</label>
                        <input type="datetime-local" class="form-control" name="deadline"
                               value="{{ $tugas->isEmpty() ? '' : \Carbon\Carbon::parse($tugas->first()->deadline)->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="form-group">
                        <label for="file">Upload File (Multiple Upload)</label>
                        <input type="file" class="form-control-file" name="file[]" multiple>
                        <small class="text-danger">Format: jpeg, jpg, png, pdf, doc, docx, ppt, pptx, mp4, mkv, mov. Max 50MB per file.</small>
                    </div>

                    <div class="d-flex">
                        <button type="submit" id="uploadButton" class="btn btn-primary mr-2">
                            <span id="uploadText">{{ $tugas->isEmpty() ? 'Upload Tugas' : 'Perbarui Tugas' }}</span>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        @if(!$tugas->isEmpty())
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#lihatTugasModal">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@if(!$tugas->isEmpty())
<div class="modal fade" id="lihatTugasModal" tabindex="-1" aria-labelledby="lihatTugasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lihatTugasModalLabel">Detail Tugas :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="font-weight-bold">{{ $tugas->first()->judul }}</h5>
                <p>{{ $tugas->first()->deskripsi }}</p>
                <p><strong>Batas Waktu:</strong> {{ \Carbon\Carbon::parse($tugas->first()->deadline)->translatedFormat('d F Y, H:i') }}</p>

                <h6 class="mt-3">File Tugas:</h6>
                <ul class="list-group">
                    @foreach(json_decode($tugas->first()->file_path, true) ?? [] as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $file['encrypted_name']) }}" target="_blank">
                                <i class="fas fa-file"></i> {{ $file['original_name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    document.getElementById('uploadTugasForm').addEventListener('submit', function() {
        document.getElementById('uploadButton').disabled = true;
        document.getElementById('uploadText').innerText = 'Mengunggah...';
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>
@endsection
