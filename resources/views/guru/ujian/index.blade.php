@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item">Ujian</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Daftar Ujian</h2>
        <p class="section-lead">List ujian yang tersedia dalam sistem.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data &mdash; Ujian</h4>
                        <div>
                            <a href="{{ route('guru.ujian.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Ujian
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($ujian->isEmpty())
                                <div class="alert alert-warning text-center">
                                    <strong>Belum ada ujian yang tersedia.</strong>
                                </div>
                            @else
                                <table class="table table-striped" id="table-ujian">
                                    <thead>
                                        <tr>
                                            <th>Jenis Ujian</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Waktu Pengerjaan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ujian as $item)
                                        <tr>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ optional($item->mataPelajaran)->nama_mapel ?? 'Tidak ada data' }}</td>
                                            <td>{{ optional($item->kelas)->nama_kelas ?? 'Tidak ada data' }}</td>
                                            <td>{{ $item->waktu_pengerjaan ?? 'N/A' }} Menit</td>
                                            <td>
                                                <span class="badge {{ $item->status ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick="openManageModal({{ $item->id }}, '{{ $item->token }}', '{{ route('guru.ujian.edit', $item->id) }}', '{{ route('guru.ujian.delete', $item->id) }}', '{{ route('guru.soal.index', $item->id) }}')">
                                                    <i class="fas fa-cog"></i> Kelola
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="manageUjianModal" tabindex="-1" role="dialog" aria-labelledby="manageUjianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-black">
                <h5 class="modal-title"><i class="fas fa-cog"></i> Kelola Ujian</h5>
                <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <button class="btn btn-primary btn-lg btn-block mb-3" id="lihatTokenBtn">
                    <i class="fas fa-eye"></i> Lihat Token
                </button>
                <a href="#" class="btn btn-warning btn-lg btn-block mb-3" id="editUjianBtn">
                    <i class="fas fa-edit"></i> Edit Ujian
                </a>
                <a href="#" class="btn btn-success btn-lg btn-block mb-3" id="kelolaSoalBtn">
                    <i class="fas fa-book"></i> Kelola Soal
                </a>
                <form id="deleteUjianForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="confirmDelete(event)">
                        <i class="fas fa-trash"></i> Hapus Ujian
                    </button>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tokenUjian" tabindex="-1" role="dialog" aria-labelledby="tokenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-black">
                <h5 class="modal-title"><i class="fas fa-key"></i> Token Ujian</h5>
                <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p class="text-success font-weight-bold">
                    <i class="fas fa-check-circle"></i> Berikan token ini kepada siswa agar bisa mengakses dan mengerjakan Ujian
                </p>
                <div class="token-box bg-light p-3 rounded shadow">
                    <h3 id="ujianToken" class="font-weight-bold text-dark"></h3>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-ujian').DataTable();
    });

    function openManageModal(id, token, editUrl, deleteUrl, soalUrl) {
        $('#editUjianBtn').attr('href', editUrl);
        $('#deleteUjianForm').attr('action', deleteUrl);
        $('#kelolaSoalBtn').attr('href', soalUrl);
        $('#lihatTokenBtn').off('click').on('click', function() {
            lihatToken(token);
        });
        $('#manageUjianModal').modal('show');
    }

    function lihatToken(token) {
        $('#ujianToken').text(token);
        $('#tokenUjian').modal('show');
    }

    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Ujian ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteUjianForm').submit();
            }
        });
    }
</script>
@endsection
