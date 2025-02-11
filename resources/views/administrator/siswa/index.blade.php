@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Siswa</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">List Data</h2>
        <p class="section-lead">Data Siswa yang Terdaftar.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data &mdash; Siswa</h4>
                        <div>
                            <a href="{{ route('administrator.siswa.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Siswa
                            </a>
                            <button type="button" id="btn-delete-selected" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus yang Dipilih
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
                                        <td>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input select-item" value="{{ $siswa->id }}" id="checkbox-{{ $siswa->id }}">
                                                <label for="checkbox-{{ $siswa->id }}" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>
                                            <img src="{{ $siswa->foto_profil ? asset('storage/' . $siswa->foto_profil) : asset('_sys/img/avatar/avatar-1.png') }}"
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
                                                <button type="button" class="btn btn-danger btn-sm btn-delete">
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxAll = document.getElementById('checkbox-all');
        if (checkboxAll) {
            checkboxAll.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.select-item');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        document.querySelectorAll('.select-item').forEach(item => {
            item.addEventListener('change', function () {
                const allCheckboxes = document.querySelectorAll('.select-item');
                const allChecked = [...allCheckboxes].every(checkbox => checkbox.checked);
                document.getElementById('checkbox-all').checked = allChecked;
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.getElementById('btn-delete-selected').addEventListener('click', function () {
            const selectedIds = [...document.querySelectorAll('.select-item:checked')].map(el => el.value);

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada data yang dipilih!',
                    text: 'Pilih setidaknya satu data untuk dihapus.',
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('administrator.siswa.bulkdelete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                        }).then(() => location.reload());
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>
@endsection
