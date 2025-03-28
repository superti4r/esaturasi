@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Mata Pelajaran Perkelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Mata Pelajaran Perkelas</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Kelola Mata Pelajaran Perkelas</h2>
        <p class="section-lead">Atur Jadwal Mata Pelajaran untuk setiap Kelas yang terdaftar.</p>

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('administrator.mapelperkelas.add') }}" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmResetAll()">
                <i class="fas fa-trash-alt"></i> Reset Semua
            </button>
            <form id="reset-all-form" action="{{ route('administrator.mapelperkelas.reset') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

        <div class="row">
            @foreach ($kelas as $k)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="mb-0 text-white">{{ $k->nama_kelas }}</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $mapelKelas = $mapelPerKelas->where('kelas_id', $k->id);
                        @endphp

                        @if ($mapelKelas->isEmpty())
                            <p class="text-center text-muted">Tidak ada mata pelajaran.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($mapelKelas as $m)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $m->mataPelajaran->nama_mapel ?? 'Mata pelajaran tidak ditemukan' }}</strong> <br>
                                        <small>Diajarkan oleh: {{ $m->guru->nama ?? 'Guru tidak ditemukan' }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('administrator.mapelperkelas.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('administrator.mapelperkelas.delete', $m->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @endif
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
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }

    function confirmResetAll() {
        Swal.fire({
            title: 'Reset Semua Data?',
            text: "Semua data akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reset-all-form').submit();
            }
        });
    }
</script>
@endsection
