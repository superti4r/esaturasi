@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Siswa</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Kelola Siswa</h2>
        <p class="section-lead">Kelola identitas dan akun untuk Siswa, bila Siswa lupa dengan password nya, anda bisa mengubahnya disini.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data &mdash; Siswa</h4>
                        <div>
                            <a href="{{ route('administrator.siswa.export.pdf') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-print"></i> Download PDF
                            </a>
                            <a href="{{ route('administrator.siswa.export.excel') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-print"></i> Download .xlsx
                            </a>
                            <a href="{{ route('administrator.siswa.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Siswa
                            </a>
                            <button type="button" id="btn-hapus-terpilih" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus yang Dipilih
                            </button>
                            <button type="button" id="btn-naik-kelas" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-up"></i> Naik Kelas
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Foto Profil</th>
                                        <th>Kelas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $siswa)
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input select-item" id="checkbox-{{ $siswa->id }}" value="{{ $siswa->id }}">
                                                <label for="checkbox-{{ $siswa->id }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>
                                            <img src="{{ $siswa->foto_profil ? asset('storage/' . $siswa->foto_profil) : asset('module/Stisla/avatar.png') }}"
                                                 alt="Foto Profil" class="rounded-circle" width="35">
                                        </td>
                                        <td>{{ $siswa->kelas->nama_kelas }}</td>
                                        <td>
                                            <a href="{{ route('administrator.siswa.view', $siswa->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('administrator.siswa.edit', $siswa->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('administrator.siswa.delete', $siswa->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
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

<div class="modal fade" id="modal-naik-kelas" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Naikkan Siswa ke Kelas Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="kelas-baru">Pilih Kelas Tujuan:</label>
                <select class="form-control" id="kelas-baru">
                    @foreach($kelas as $kls)
                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <button type="button" id="btn-proses-naik-kelas" class="btn btn-primary">Proses</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxAll = document.getElementById('checkbox-all');
    const studentCheckboxes = document.querySelectorAll('.select-item');
    const btnHapusTerpilih = document.getElementById('btn-hapus-terpilih');
    const btnNaikKelas = document.getElementById('btn-naik-kelas');

    checkboxAll.addEventListener('change', function () {
        studentCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    btnHapusTerpilih.addEventListener('click', function () {
        let selectedIds = [...document.querySelectorAll('.select-item:checked')].map(el => el.value);
        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu siswa untuk dihapus.',
            });
            return;
        }

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menghapus siswa yang dipilih?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('administrator.siswa.bulkdelete') }}", {
                    method: "POST",
                    body: JSON.stringify({ ids: selectedIds }),
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                        });
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Silakan coba lagi.',
                    });
                });
            }
        });
    });

    btnNaikKelas.addEventListener('click', function () {
        let selectedIds = [...document.querySelectorAll('.select-item:checked')].map(el => el.value);
        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu siswa untuk dinaikkan kelas.',
            });
            return;
        }
        $('#modal-naik-kelas').modal('show');
    });

    document.getElementById('btn-proses-naik-kelas').addEventListener('click', function () {
        let selectedIds = [...document.querySelectorAll('.select-item:checked')].map(el => el.value);
        let kelasTujuan = document.getElementById('kelas-baru').value;

        fetch("{{ route('administrator.siswa.naikkelas') }}", {
            method: "POST",
            body: JSON.stringify({ student_ids: selectedIds, kelas_id: kelasTujuan }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan pada server.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan saat memproses data.',
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: error.message || 'Silakan coba lagi.',
            });
        });
    });
});
</script>
@endsection
