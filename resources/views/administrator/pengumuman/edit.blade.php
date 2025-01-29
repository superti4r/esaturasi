@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Pengumuman</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="/administrator/pengumuman">Pengumuman</a></div>
            <div class="breadcrumb-item">Edit Pengumuman</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Form Edit Pengumuman</h2>
        <p class="section-lead">Silakan edit form di bawah ini untuk mengubah pengumuman.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Edit Pengumuman</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('administrator.pengumuman.update', $pengumuman->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="judul_pengumuman">Judul Pengumuman</label>
                                <input type="text" class="form-control @error('judul_pengumuman') is-invalid @enderror" id="judul_pengumuman" name="judul_pengumuman" value="{{ old('judul_pengumuman', $pengumuman->judul_pengumuman) }}" required>
                                @error('judul_pengumuman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="content_pengumuman">Isi Pengumuman</label>
                                <textarea id="content_pengumuman" name="content_pengumuman" class="summernote form-control @error('content_pengumuman') is-invalid @enderror">{{ old('content_pengumuman', $pengumuman->content_pengumuman) }}</textarea>
                                @error('content_pengumuman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
        height: 200,
        minHeight: 100,
        maxHeight: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['codeview']]
        ]
    });
});
</script>
@endpush
