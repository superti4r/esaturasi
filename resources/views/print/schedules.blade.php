<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>DATA JADWAL</title>
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

        .class-section {
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .class-name {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-align: left;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        <h3>DATA JADWAL</h3>
        <p>Dicetak tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>

    @foreach ($schedules->groupBy('classroom.name') as $className => $classSchedules)
        <div class="class-section">
            <div class="class-name">{{ $className }}</div>

            @foreach ($classSchedules->groupBy('subject.name') as $subjectName => $subjectSchedules)
                <table class="data">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Hari</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $shown = []; @endphp

                        @foreach ($subjectSchedules as $schedule)
                            @php
                                $teacher = $schedule->teacher->name ?? '-';
                                $key = $subjectName . '|' . $teacher;
                            @endphp

                            @foreach ($schedule->schedule as $i => $item)
                                <tr>
                                    @if (!in_array($key, $shown))
                                        <td rowspan="{{ count($schedule->schedule) }}">{{ $subjectName }}</td>
                                        <td rowspan="{{ count($schedule->schedule) }}">{{ $teacher }}</td>
                                        @php $shown[] = $key; @endphp
                                    @endif
                                    @if (in_array($key, $shown) && $i > 0)
                                    @endif
                                    <td>{{ ucfirst($item['day']) }}</td>
                                    <td>{{ $item['start'] }} - {{ $item['end'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @endforeach

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
