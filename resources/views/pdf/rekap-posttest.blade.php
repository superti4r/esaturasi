<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Post Test</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        .header {
            position: relative;
            text-align: center;
            margin-bottom: 10px;
        }

        .header img.logo-left {
            width: 60px;
            position: absolute;
            left: 0;
            top: 0;
        }

        .header img.logo-right {
            width: 70px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .header h2, .header h3, .header p {
            margin: 0;
            padding: 2px 0;
        }

        .header hr {
            margin: 15px 0;
            border: 1px solid #000;
        }

        .info-box {
            margin-bottom: 10px;
        }

        .info-box table td {
            padding: 2px 6px;
        }

        .info-box table td:first-child {
            width: 80px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        table.data th {
            background-color: #d9d9d9;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

        .footer .signature {
            float: right;
            text-align: center;
        }

        .clear { clear: both; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ public_path('img/print1.png') }}" alt="logo" class="logo-left">
        <img src="{{ public_path('img/print2.png') }}" alt="logo" class="logo-right">
        <h2>SMK NEGERI 1 SUMBERASIH</h2>
        <p>Jl. Brawijaya No. 78 Telp & Fax 0335 435952 Probolinggo 67251</p>
        <p>Website : https://www.smkn1sumberasih-pbl.sch.id E-Mail : smknsumberasih@gmail.com</p>
        <hr>
        <h3>REKAP NILAI POST TEST</h3>
    </div>

    {{-- INFO FILTER --}}
    @if(!empty($info))
    <div class="info-box">
        <table>
            @foreach($info as $key => $val)
            <tr>
                <td>{{ $key }}</td>
                <td>: {{ $val }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    {{-- LOOP PER JUDUL Post-Test --}}
   
    @forelse($grouped as $posttestId => $rows)
    @php
        $posttest = $rows->first()->posttest;
        $mapel    = $posttest?->slug?->schedule?->subject?->name ?? '-';
        $guru_row = $posttest?->slug?->schedule?->teacher?->name ?? '-';
        $tanggal  = $posttest?->waktu_mulai
                        ? \Carbon\Carbon::parse($posttest->waktu_mulai)->format('d-m-Y')
                        : '-';
    @endphp

    <div style="font-weight: bold; font-size: 13px; margin-top: 20px; margin-bottom: 6px;">
        Posttest - {{ $posttest?->judul ?? '-' }}
    </div>

    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 110px;">Mata Pelajaran</td>
            <td>: {{ $mapel }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $tanggal }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left;">{{ $row->student->name ?? '-' }}</td>
                <td>{{ $row->student->classroom->name ?? '-' }}</td>
                <td>{{ $row->nilai ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@empty
    <p style="text-align:center; color:#888;">Tidak ada data posttest</p>
@endforelse

    {{-- FOOTER TTD GURU --}}
    <div class="footer">
        <div class="signature">
            <p>Probolinggo, {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
            <p><strong>Guru Pengajar</strong></p>
            <br><br><br>
            <p><strong>{{ $guru }}</strong></p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>