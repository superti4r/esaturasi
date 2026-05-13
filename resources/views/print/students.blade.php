<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* ===== MARGIN PINGGIR ===== */
        .page {
            padding: 30px 40px 30px 40px;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* ===== HEADER ===== */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 8px;
            padding: 0 75px;
        }

        .header img.logo-left {
            width: 60px;
            position: absolute;
            left: 0;
            top: 0;
        }

        .header img.logo-right {
            width: 65px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            font-size: 11px;
            margin: 2px 0 0 0;
        }

        .header hr {
            margin: 10px 0 8px 0;
            border: none;
            border-top: 2.5px solid #000;
        }

        .header h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 4px 0 2px 0;
        }

        /* ===== INFO ===== */
        .info {
            margin: 10px 0 6px 0;
            font-size: 12px;
            font-weight: bold;
        }

        /* ===== TABEL ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 11.5px;
        }

        table.data th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }

        table.data td {
            text-align: center;
        }

        table.data td.nama {
            text-align: left;
        }

        /* ===== FOOTER TTD ===== */
        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

        .footer table {
            width: 100%;
        }

        .footer .ttd-box {
            text-align: center;
            width: 230px;
        }

        .footer .ttd-box p {
            margin: 2px 0;
        }

        .ttd-space {
            height: 60px;
        }
    </style>
</head>
<body>

    @foreach ($classrooms as $classroom)
    <div class="page">

        {{-- HEADER --}}
        <div class="header">
            <img src="{{ public_path('img/print1.png') }}" alt="Logo" class="logo-left">
            <img src="{{ public_path('img/print2.png') }}" alt="Logo" class="logo-right">
            <h2>SMK NEGERI 1 SUMBERASIH</h2>
            <p>Jl. Brawijaya No. 78 Telp &amp; Fax 0335 435952 Probolinggo 67251</p>
            <p>Website : https://www.smkn1sumberasih-pbl.sch.id &nbsp;&nbsp; E-Mail : smknsumberasih@gmail.com</p>
            <hr>
            <h3>{{ $classroom->name }}</h3>
        </div>

        {{-- INFO JUMLAH --}}
        <div class="info">Jumlah Siswa : {{ $classroom->students->count() }}</div>

        {{-- TABEL — tanpa kolom Jenis Kelamin --}}
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 120px;">NISN</th>
                    <th>Nama</th>
                    <th style="width: 110px;">Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classroom->students as $i => $student)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td class="nama">{{ $student->name }}</td>
                    <td>{{ $classroom->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        
    </div>
    @endforeach

</body>
</html>