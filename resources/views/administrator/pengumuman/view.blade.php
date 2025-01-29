@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Pengumuman</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/administrator">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('administrator.pengumuman') }}">Pengumuman</a></div>
            <div class="breadcrumb-item">Detail Pengumuman</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{ $pengumuman->judul_pengumuman }}</h2>
        <p class="section-lead">
            Diposting pada: {{ $pengumuman->created_at->format('d M Y H:i') }}
        </p>

        <div class="card">
            <div class="card-body">
                {!! $pengumuman->content_pengumuman !!}
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('administrator.pengumuman') }}" class="btn btn-danger">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
