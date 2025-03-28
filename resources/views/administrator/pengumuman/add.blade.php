@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Pengumuman</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/pengumuman">Pengumuman</a></div>
            <div class="breadcrumb-item">Tambah Pengumuman</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('administrator.pengumuman.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="judul_pengumuman">Judul Pengumuman</label>
                                <input type="text" class="form-control @error('judul_pengumuman') is-invalid @enderror" id="judul_pengumuman" name="judul_pengumuman" value="{{ old('judul_pengumuman') }}" required placeholder="Masukkan Nama Pengumuman">
                                @error('judul_pengumuman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="content_pengumuman"></label>
                                <textarea id="content_pengumuman" name="content_pengumuman" class="summernote form-control @error('content_pengumuman') is-invalid @enderror">{{ old('content_pengumuman') }} </textarea>
                                @error('content_pengumuman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('administrator.pengumuman') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Masukkan isi artikel untuk Pengumuman di sini...',
            height: 200,
            minHeight: 100,
            maxHeight: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });
    });
    </script>
@endpush
