@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pengumuman</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Pengumuman</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Kelola Pengumuman</h2>
        <p class="section-lead">Pengumuman ini akan ditampilkan di menu Pengumuman pada Aplikasi Mobile yang terintegrasi.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data &mdash; Pengumuman</h4>
                        <div>
                            <a href="{{ route('administrator.pengumuman.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Pengumuman
                            </a>
                            <button type="button" id="btn-delete-selected" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus yang Dipilih
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-pengumuman">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Judul Pengumuman</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengumuman as $item)
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{ $item->id }}" value="{{ $item->id }}">
                                                <label for="checkbox-{{ $item->id }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>{{ $item->judul_pengumuman }}</td>
                                        <td>
                                            <a href="{{ route('administrator.pengumuman.view', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('administrator.pengumuman.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('administrator.pengumuman.delete', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">
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

<script src="{{ asset ('module/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-pengumuman').DataTable();

        $('#checkbox-all').on('change', function() {
            $('.checkbox-item').prop('checked', this.checked);
        });

        $('#btn-delete-selected').on('click', function() {
            var selectedIds = $('.checkbox-item:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length > 0) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menghapus pengumuman yang dipilih!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('administrator.pengumuman.bulkdelete') }}",
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.success,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus data.',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Pilih setidaknya satu pengumuman untuk dihapus.',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });

    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus pengumuman ini!",
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
</script>
@endsection
