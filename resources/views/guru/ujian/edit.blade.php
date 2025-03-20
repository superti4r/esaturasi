@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('guru.ujian.index') }}">Ujian</a></div>
            <div class="breadcrumb-item">Edit Ujian</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Form Edit Ujian</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.ujian.update', $ujian->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="jenis">Jenis Ujian</label>
                        <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                            <option value="UTS" {{ $ujian->jenis == 'UTS' ? 'selected' : '' }}>UTS</option>
                            <option value="UAS" {{ $ujian->jenis == 'UAS' ? 'selected' : '' }}>UAS</option>
                        </select>
                        @error('jenis')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mata_pelajaran_id">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror">
                            @foreach($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" {{ $ujian->mata_pelajaran_id == $mp->id ? 'selected' : '' }}>{{ $mp->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $ujian->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="waktu_pengerjaan">Waktu Pengerjaan (menit)</label>
                        <input type="number" name="waktu_pengerjaan" id="waktu_pengerjaan" class="form-control @error('waktu_pengerjaan') is-invalid @enderror" value="{{ $ujian->waktu_pengerjaan }}" required>
                        @error('waktu_pengerjaan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{ $ujian->status ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$ujian->status ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
