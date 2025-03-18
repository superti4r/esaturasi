@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pembagian Jadwal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Pembagian Jadwal</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Pembagian Jadwal</h2>
        <p class="section-lead">Data jadwal pelajaran berdasarkan kelas.</p>
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-primary mr-2" onclick="confirmSync()">
                <i class="fas fa-sync"></i> Sinkronkan Jadwal
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmResetAll()">
                <i class="fas fa-trash-alt"></i> Reset Semua Jadwal
            </button>
            <form id="sync-form" action="{{ route('administrator.jadwal.sinkronisasi') }}" method="POST" style="display:none;">
                @csrf
            </form>
            <form id="reset-all-form" action="{{ route('administrator.jadwal.resetall') }}" method="POST" style="display:none;">
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
                            $jadwalKelas = $jadwal->where('kelas_id', $k->id);
                        @endphp

                        @if ($jadwalKelas->isEmpty())
                            <p class="text-center text-muted">Tidak ada jadwal.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($jadwalKelas as $j)
                                @php
                                    $jadwalHari = json_decode($j->hari, true);
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $j->mataPelajaran->nama_mapel }}</strong> <br>
                                        <small>{{ $j->guru->nama }}</small> <br>
                                        @if(is_array($jadwalHari))
                                            @foreach($jadwalHari as $hari => $waktu)
                                                <small>
                                                    <strong>{{ $hari }}:</strong>
                                                    {{ $waktu['mulai'] ?? '-' }} - {{ $waktu['selesai'] ?? '-' }}
                                                </small><br>
                                            @endforeach
                                        @else
                                            <small>Tidak ada data jadwal.</small>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('administrator.jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
            text: "Data jadwal ini akan dihapus!",
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
            title: 'Reset Semua Jadwal?',
            text: "Semua data jadwal akan dihapus secara permanen!",
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

    function confirmSync() {
        Swal.fire({
            title: 'Sinkronisasi Jadwal?',
            text: "Data mata pelajaran akan ditambahkan ke jadwal berdasarkan kelas!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, sinkronkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('sync-form').submit();
            }
        });
    }
</script>
@endsection
