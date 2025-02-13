@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Jadwal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('administrator.jadwal') }}">Jadwal</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Edit Jadwal</h2>
        <p class="section-lead">Gunakan form ini untuk mengedit data jadwal pelajaran.</p>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('administrator.jadwal.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $jadwal->kelas_id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mata_pelajaran_id">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" {{ $mp->id == $jadwal->mata_pelajaran_id ? 'selected' : '' }}>
                                    {{ $mp->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="guru_id">Guru</label>
                        <select name="guru_id" id="guru_id" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}" {{ $g->id == $jadwal->guru_id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select name="hari" id="hari" class="form-control" required>
                            @php
                                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            @endphp
                            @foreach ($hariList as $h)
                                <option value="{{ $h }}" {{ $h == $jadwal->hari ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ $jadwal->jam_mulai }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ $jadwal->jam_selesai }}" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('administrator.jadwal') }}" class="btn btn-danger mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection
