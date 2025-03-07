@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tugas dan Materi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('guru') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Kelola</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Tugas dan Materi</h2>
        <p class="section-lead">Kelola tugas dan materi berdasarkan jadwal mengajar Anda.</p>

        <div class="row">
            @foreach ($jadwal as $j)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="mb-0 text-white">{{ $j->kelas->nama_kelas }} - {{ $j->mataPelajaran->nama_mapel }}</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $slugs = $j->slugs ?? collect();
                        @endphp

                        @if ($slugs->isEmpty())
                            <p class="text-center text-muted">Belum ada data.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($slugs as $slug)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $slug->judul }}</strong>
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{ route('guru.tugas-dan-materi.show', $slug->slug) }}" class="btn btn-sm btn-info mr-2">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('guru.tugas-dan-materi.destroy', $slug->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @endif

                        <form action="{{ route('guru.tugas-dan-materi.create') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $j->id }}">
                            <div class="input-group">
                                <input type="text" name="judul" class="form-control" placeholder="Judul Bab" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data ini akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest("form").submit();
                    }
                });
            });
        });
    });
</script>
@endsection
