<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            padding: 0;
            background-color: #fff;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            opacity: 0.08;
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        .header .address {
            font-size: 10px;
            color: #555;
        }

        hr {
            border: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            word-break: break-word;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>

    <img src="_sys/img/logo.png" alt="Watermark" class="watermark">

    <div class="header">
        <h1>SMK Negeri 1 Sumberasih</h1>
        <p>TERAKREDITASI "A"</p>
        <p>NPSN: 20554925</p>
        <p class="address">Jl. Brawijaya No. 78, Kec. Sumberasih, Kab. Probolinggo</p>
        <hr>
    </div>

    <h3 style="text-align:center; text-decoration: underline; margin-bottom:10px;">DATA SISWA</h3>

    <table>
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Tempat Lahir</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th>Alamat Siswa</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $siswa)
            <tr>
                <td>{{ $siswa->nisn }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->tanggal_lahir }}</td>
                <td>{{ $siswa->tempat_lahir }}</td>
                <td>{{ $siswa->kelas->nama_kelas }}</td>
                <td>{{ ucfirst($siswa->jenis_kelamin) }}</td>
                <td>{{ $siswa->alamat }}</td>
                <td>{{ $siswa->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
