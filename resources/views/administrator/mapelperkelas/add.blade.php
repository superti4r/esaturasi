@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Mata Pelajaran Perkelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('administrator.mapelperkelas') }}">Mata Pelajaran Perkelas</a></div>
            <div class="breadcrumb-item">Tambah</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.mapelperkelas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="mata_pelajaran_id">Pilih Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="guru_id">Pilih Guru</label>
                        <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                            <option value="">Pilih Guru</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('administrator.mapelperkelas') }}" class="btn btn-danger ml-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
