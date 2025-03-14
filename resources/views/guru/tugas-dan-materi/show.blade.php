@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Tugas dan Materi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('guru') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.tugas-dan-materi.index') }}">Kelola</a></div>
            <div class="breadcrumb-item active">Tugas & Materi</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Detail Tugas dan Materi</h2>
        <p class="section-lead">
            Berikut adalah tugas dan materi untuk jadwal:
            <strong>{{ $slugData->jadwal->kelas->nama_kelas }} - {{ $slugData->jadwal->mataPelajaran->nama_mapel }}</strong>
        </p>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white">{{ $slugData->judul }}</h4>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column align-items-center">
                    <a href="{{ route('guru.tugas-dan-materi.materi.index', $slugData->slug) }}" class="btn btn-success mb-3">
                        <i class="fas fa-book"></i> Kelola Materi
                    </a>
                    <a href="{{ route('guru.tugas-dan-materi.tugas.index', $slugData->slug) }}" class="btn btn-primary mb-3">
                        <i class="fas fa-pen"></i> Kelola Tugas
                    </a>
                    @if(!$tugas->isEmpty())
                    <a href="{{ route('guru.tugas-dan-materi.pengumpulan.index', ['id' => $tugas->first()->id]) }}" class="btn btn-warning mb-3">
                        <i class="fas fa-eye"></i> Pengumpulan
                    </a>
                @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <a href="{{ route('guru.tugas-dan-materi.index') }}" class="btn btn-danger">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</section>
@endsection
