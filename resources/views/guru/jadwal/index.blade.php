@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Jadwal Mengajar</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/dashboard">Dashboard</a></div>
            <div class="breadcrumb-item">Jadwal</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">List Jadwal</h2>
        <p class="section-lead">Jadwal mengajar Anda.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Jadwal &mdash; Mengajar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-jadwal">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal as $index => $item)
                                    <tr class="jadwal-row" data-hari="{{ strtolower($item->hari) }}">
                                        <td>
                                            <span class="badge badge-primary">{{ $item->hari }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $item->mataPelajaran->nama_mapel }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light">{{ $item->kelas->nama_kelas }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $item->jam_mulai }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">{{ $item->jam_selesai }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-jadwal').DataTable();

        function getHariIni() {
            const hariIndo = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];
            const hariSekarang = new Date().getDay();
            return hariIndo[hariSekarang];
        }

        let hariIni = getHariIni();

        $('.jadwal-row').each(function() {
            var hariJadwal = $(this).data('hari');
            if (hariJadwal === hariIni) {
                $(this).css('background-color', '#d4edda');
            } else {
                $(this).css('background-color', '#f5f5f5');
            }
        });
    });
</script>
@endsection
