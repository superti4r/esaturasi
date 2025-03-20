@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.ujian.index') }}">Ujian</a></div>
            <div class="breadcrumb-item">Tambah Ujian</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Form Tambah Ujian</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.ujian.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Jenis Ujian</label>
                        <select name="jenis" class="form-control @error('jenis') is-invalid @enderror">
                            <option value="UTS">UTS</option>
                            <option value="UAS">UAS</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror">
                            @foreach($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Waktu Pengerjaan (menit)</label>
                        <input type="number" name="waktu_pengerjaan" class="form-control @error('waktu_pengerjaan') is-invalid @enderror" required>
                        @error('waktu_pengerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('guru.ujian.index') }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
