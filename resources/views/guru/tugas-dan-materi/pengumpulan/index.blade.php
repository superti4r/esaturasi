@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pengumpulan Tugas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('guru') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.tugas-dan-materi.index') }}">Kelola</a></div>
            <div class="breadcrumb-item active">Pengumpulan Tugas</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Daftar Pengumpulan</h2>
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="text-white">Pengumpulan Tugas: {{ $tugas->judul }}</h4>
                <span class="badge badge-{{ \Carbon\Carbon::parse($tugas->deadline)->isFuture() ? 'success' : 'danger' }}">
                    {{ \Carbon\Carbon::parse($tugas->deadline)->isFuture() ? 'Berlangsung' : 'Selesai' }}
                </span>
            </div>

            <div class="card-body">
                @if($tugas->pengumpulan->isEmpty())
                    <div class="alert alert-warning">Belum ada siswa yang mengumpulkan tugas.</div>
                @else
                    <div class="row">
                        @foreach($tugas->pengumpulan as $pengumpulan)
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $pengumpulan->siswa->nama }}</h5>
                                        <p class="card-text">
                                            <span class="badge badge-primary">
                                                {{ \Carbon\Carbon::parse($pengumpulan->created_at)->translatedFormat('d F Y, H:i') }}
                                            </span>
                                        </p>
                                        <p class="card-text">
                                            @if($pengumpulan->nilai !== null)
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Belum Dinilai</span>
                                            @endif
                                        </p>
                                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#detailTugasModal{{ $pengumpulan->id }}">
                                            <i class="fas fa-eye"></i> Lihat & Beri Nilai
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@foreach($tugas->pengumpulan as $pengumpulan)
<div class="modal fade" id="detailTugasModal{{ $pengumpulan->id }}" tabindex="-1" aria-labelledby="detailTugasLabel{{ $pengumpulan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengumpulan Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><h6>Nama:</h6> {{ $pengumpulan->siswa->nama }}</p>
                    <span class="badge badge-primary">
                        {{ \Carbon\Carbon::parse($pengumpulan->created_at)->translatedFormat('d F Y, H:i') }}
                    </span>
                </p>
                <p>
                    @if($pengumpulan->nilai !== null)
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                    @else
                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Belum Dinilai</span>
                    @endif
                </p>

                <strong>File Tugas:</strong>
                <ul class="list-group">
                    @foreach(json_decode($pengumpulan->file_path, true) ?? [] as $file)
                        <li class="list-group-item">
                            <a href="{{ asset('storage/' . $file['encrypted_name']) }}" target="_blank">
                                <i class="fas fa-file"></i> {{ $file['original_name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('guru.tugas-dan-materi.pengumpulan.update', $pengumpulan->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nilai">Nilai:</label>
                        <input type="number" name="nilai" id="nilai" class="form-control" value="{{ $pengumpulan->nilai ?? '' }}" min="0" max="100" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block mt-2">
                        <i class="fas fa-save"></i> Simpan Nilai
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
