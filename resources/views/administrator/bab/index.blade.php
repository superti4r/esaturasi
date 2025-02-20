@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Bab</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item">Bab</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">List Data</h2>
        <p class="section-lead">Data Bab.</p>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Data &mdash; Bab</h4>
                        <div>
                            <a href="{{ route('administrator.bab.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Bab
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-bab">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama Bab</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($babs as $item)
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{ $item->id }}" value="{{ $item->id }}">
                                                <label for="checkbox-{{ $item->id }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>{{ $item->nama_bab }}</td>
                                        <td>
                                            <form action="{{ route('administrator.bab.delete', ['id' => $item->id]) }}" method="POST" style="display:inline;">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-bab').DataTable();
        $('#checkbox-all').on('change', function() {
            $('.checkbox-item').prop('checked', this.checked);
        });
    });

    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus bab ini!",
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
