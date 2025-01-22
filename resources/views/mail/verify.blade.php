<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Akun Anda</title>
</head>
<body>
    <p>
        Halo <b>{{ $details ['nama'] }}</b> !
    </p>
    <br>
    <p>
        Berikut adalah informasi mengenai Data Anda :
    </p>
    <table>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $details ['nama'] }}</td>
        </tr>
        <tr>
            <td>Link Website</td>
            <td>:</td>
            <td>{{ $details ['website'] }}</td>
        </tr>
        <tr>
            <td>Tanggal Pendaftaran</td>
            <td>:</td>
            <td>{{ $details ['datetime'] }}</td>
        </tr>
        <br><br><br>
        <center>
            <h3>Verifikasi Akun Anda :</h3>
            <a href="{{ $details ['url'] }}">Klik Disini.</a>
            <br><br><br>
            <p>
                E-Saturasi.
            </p>
        </center>
    </table>
</body>
</html>
