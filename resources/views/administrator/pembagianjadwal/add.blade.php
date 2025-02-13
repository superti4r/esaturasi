@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Jadwal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('administrator.jadwal') }}">Jadwal</a></div>
            <div class="breadcrumb-item">Tambah Jadwal</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Tambah Jadwal</h2>
        <p class="section-lead">Silahkan menambahkan jadwal disini.</p>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="form-control" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach ($mataPelajaran as $mp)
                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Guru</label>
                        <select name="guru_id" class="form-control" required>
                            <option value="">Pilih Guru</option>
                            @foreach ($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Hari</label>
                        <select name="hari" class="form-control" required>
                            <option value="">Pilih Hari</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                            <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('administrator.jadwal') }}" class="btn btn-danger">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
