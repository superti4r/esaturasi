@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Kelola Materi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('guru') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.tugas-dan-materi.index') }}">Tugas dan Materi</a></div>
            <div class="breadcrumb-item active">Kelola Materi</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Daftar Materi</h2>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="text-white">
                    @if($materi->isEmpty()) Tambah Materi @else Perbarui Materi @endif
                </h4>
            </div>
            <div class="card-body">
                <form id="uploadMateriForm"
                      action="@if($materi->isEmpty()) {{ route('guru.tugas-dan-materi.materi.store', $slugData->slug) }}
                             @else {{ route('guru.tugas-dan-materi.materi.update', $materi->first()->id) }} @endif"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(!$materi->isEmpty()) @method('PUT') @endif

                    <div class="form-group">
                        <label for="judul">Judul Materi</label>
                        <input type="text" class="form-control" name="judul"
                               value="{{ $materi->isEmpty() ? '' : $materi->first()->judul }}" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3">{{ $materi->isEmpty() ? '' : $materi->first()->deskripsi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="file">Upload File (Multiple Upload)</label>
                        <input type="file" class="form-control-file" name="file[]" multiple>
                        <small class="text-danger">Format: jpeg, jpg, png, pdf, doc, docx, ppt, pptx, mp4, mkv, mov. Max 50MB per file.</small>
                    </div>

                    <div class="d-flex">
                        <button type="submit" id="uploadButton" class="btn btn-primary mr-2">
                            <span id="uploadText">@if($materi->isEmpty()) Upload Materi @else Perbarui Materi @endif</span>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        @if(!$materi->isEmpty())
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#lihatMateriModal">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@if(!$materi->isEmpty())
<div class="modal fade" id="lihatMateriModal" tabindex="-1" aria-labelledby="lihatMateriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lihatMateriModalLabel">Detail Materi :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="font-weight-bold">{{ $materi->first()->judul }}</h5>
                <p>{{ $materi->first()->deskripsi }}</p>

                <h6 class="mt-3">File Materi:</h6>
                <ul class="list-group">
                    @foreach(json_decode($materi->first()->file_path, true) as $file)
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
    document.getElementById('uploadMateriForm').addEventListener('submit', function() {
        document.getElementById('uploadButton').disabled = true;
        document.getElementById('uploadText').innerText = 'Mengunggah...';
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>
@endsection
