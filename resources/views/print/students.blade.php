<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DATA SISWA</title>
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

        .info {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
            font-size: 12px;
        }

        .info td {
            padding: 4px 0;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.data th, table.data td {
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

        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('img/print1.png') }}" alt="1" class="logo-left">
        <img src="{{ public_path('img/print2.png') }}" alt="2" class="logo-right">
        <h2>SMK NEGERI 1 SUMBERASIH</h2>
        <p>Jl. Brawijaya No. 78 Telp & Fax 0335 435952 Probolinggo 67251</p>
        <p>Website : https://www.smkn1sumberasih-pbl.sch.id E-Mail : smknsumberasih@gmail.com</p>
        <hr>
        <h3>DATA SISWA</h3>
        <p>Dicetak tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Jumlah Siswa : {{ count($students) }}</strong></td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->classroom->name ?? '-' }}</td>
                    <td>{{ $student->gender === 'Male' ? 'Laki-laki' : ($student->gender === 'Female' ? 'Perempuan' : '-') }}</td>                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Probolinggo, {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
            <p><strong>Kepsek. SMK Negeri 1 Sumberasih</strong></p>
            <br><br><br>
            <p><strong>Dwi Anggraeni, S. Pd, M. Pd</strong><br>NIP. 19750521 200312 2 006</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
