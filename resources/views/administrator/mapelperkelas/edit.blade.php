@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Mapel Perkelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('administrator.mapelperkelas') }}">Mapel Perkelas</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.mapelperkelas.update', $mapelPerKelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $mapelPerKelas->kelas_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mapel_id">Pilih Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ $mapelPerKelas->mapel_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="guru_id">Pilih Guru</label>
                        <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" {{ $mapelPerKelas->guru_id == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('administrator.mapelperkelas') }}" class="btn btn-danger">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
