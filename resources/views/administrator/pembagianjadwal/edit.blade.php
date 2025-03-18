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
                        <select id="kelas_id" class="form-control" required disabled>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $jadwal->kelas_id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="kelas_id" value="{{ $jadwal->kelas_id }}">
                    </div>

                    <div class="form-group">
                        <label for="mata_pelajaran_id">Mata Pelajaran</label>
                        <select id="mata_pelajaran_id" class="form-control" required disabled>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" {{ $mp->id == $jadwal->mata_pelajaran_id ? 'selected' : '' }}>
                                    {{ $mp->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $jadwal->mata_pelajaran_id }}">
                    </div>

                    <div class="form-group">
                        <label for="guru_id">Guru</label>
                        <select id="guru_id" class="form-control" required disabled>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}" {{ $g->id == $jadwal->guru_id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="guru_id" value="{{ $jadwal->guru_id }}">
                    </div>

                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <div class="d-flex flex-wrap">
                            @php
                                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                                $selectedHari = json_decode($jadwal->hari, true) ?? [];
                                $jamMulai = json_decode($jadwal->jam_mulai, true) ?? [];
                                $jamSelesai = json_decode($jadwal->jam_selesai, true) ?? [];
                            @endphp
                            @foreach ($hariList as $h)
                                <label class="btn hari-btn mr-2 mb-2 {{ in_array($h, $selectedHari) ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <input type="checkbox" name="hari[]" value="{{ $h }}" class="d-none"
                                        {{ in_array($h, $selectedHari) ? 'checked' : '' }}
                                        onchange="toggleTimeInput('{{ $h }}')">
                                    {{ $h }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div id="jamContainer">
                        @foreach ($hariList as $h)
                            <div class="form-group jam-group" id="jam_{{ $h }}" style="{{ in_array($h, $selectedHari) ? '' : 'display: none;' }}">
                                <label for="jam_mulai_{{ $h }}">Jam untuk {{ $h }}</label>
                                <div class="d-flex">
                                    <input type="time" name="jam_mulai[{{ $h }}]" class="form-control mr-2"
                                        value="{{ $jamMulai[$h] ?? '' }}">
                                    <input type="time" name="jam_selesai[{{ $h }}]" class="form-control"
                                        value="{{ $jamSelesai[$h] ?? '' }}">
                                </div>
                            </div>
                        @endforeach
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

<script>
    function toggleTimeInput(hari) {
        let jamDiv = document.getElementById("jam_" + hari);
        let checkbox = document.querySelector(`input[value='${hari}']`);
        let label = checkbox.closest('.hari-btn');
        let inputs = jamDiv.querySelectorAll("input");

        if (checkbox.checked) {
            jamDiv.style.display = "block";
            inputs.forEach(input => input.setAttribute("required", "required"));
            label.classList.add("btn-primary");
            label.classList.remove("btn-outline-primary");
        } else {
            jamDiv.style.display = "none";
            inputs.forEach(input => {
                input.removeAttribute("required");
                input.value = "";
            });
            label.classList.remove("btn-primary");
            label.classList.add("btn-outline-primary");
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".hari-btn input").forEach(checkbox => {
            toggleTimeInput(checkbox.value);
            checkbox.addEventListener("change", function () {
                toggleTimeInput(checkbox.value);
            });
        });
    });
</script>
@endsection
