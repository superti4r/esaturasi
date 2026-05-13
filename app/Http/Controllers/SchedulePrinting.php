<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Archive;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SchedulePrinting extends Controller
{
    protected array $jamSlot = [
        1  => ['start' => '06:50', 'end' => '07:35'],
        2  => ['start' => '07:35', 'end' => '08:15'],
        3  => ['start' => '08:15', 'end' => '08:55'],
        4  => ['start' => '08:55', 'end' => '09:35'],
        5  => ['start' => '09:50', 'end' => '10:30'],
        6  => ['start' => '10:30', 'end' => '11:10'],
        7  => ['start' => '11:10', 'end' => '11:50'],
        8  => ['start' => '12:20', 'end' => '12:55'],
        9  => ['start' => '12:55', 'end' => '13:30'],
        10 => ['start' => '13:30', 'end' => '14:05'],
        11 => ['start' => '14:05', 'end' => '14:40'],
        12 => ['start' => '14:40', 'end' => '15:15'],
    ];

    protected array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function print(Request $request)
    {
        $jenis   = $request->get('jenis', 'guru');
        $pilihan = $request->get('pilihan', 'semua');
        $ids     = $request->get('ids');

        $activeArchive = Archive::where('status', 'Active')->first();
        $archiveId     = $activeArchive?->id;

        if ($jenis === 'kelas') {
            return $this->printKelas($pilihan, $ids, $activeArchive, $archiveId);
        }

        return $this->printGuru($pilihan, $ids, $activeArchive, $archiveId);
    }

    // ─── EXPORT PER GURU ────────────────────────────────────────────────

    protected function printGuru($pilihan, $ids, $activeArchive, $archiveId)
    {
        // Tidak pakai whereHas('schedules') karena relasi mungkin belum ada di User model
        // Langsung whereExists ke tabel schedule
        $guruQuery = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->whereExists(function ($query) use ($archiveId) {
                $query->selectRaw(1)
                    ->from('schedule')
                    ->whereColumn('schedule.teacher_id', 'users.id')
                    ->where('schedule.archive_id', $archiveId);
            })
            ->orderBy('name');

        if ($pilihan === 'pilih' && $ids) {
            $guruQuery->whereIn('id', explode(',', $ids));
        }

        $guruList = $guruQuery->get();
        $pages    = [];

        foreach ($guruList as $guru) {
            $schedules = Schedule::with(['classroom', 'subject'])
                ->where('teacher_id', $guru->id)
                ->where('archive_id', $archiveId)
                ->get();

            $grid       = [];
            $mapelCount = [];

            foreach ($schedules as $schedule) {
                $namaMapel = $schedule->subject->name   ?? '-';
                $namaKelas = $schedule->classroom->name ?? '-';
                $abbr      = $this->makeAbbr($namaMapel);

                foreach ($schedule->schedule ?? [] as $slot) {
                    $startTime = $this->resolveStart($slot);
                    $endTime   = $this->resolveEnd($slot);
                    $hari      = $slot['day'] ?? '';

                    if (!$startTime || !$endTime || !$hari) continue;

                    $jamMulai   = $this->timeToJam($startTime);
                    $jamSelesai = $this->timeToJamEnd($endTime);

                    if (!$jamMulai || !$jamSelesai || $jamMulai > $jamSelesai) continue;

                    for ($j = $jamMulai; $j <= $jamSelesai; $j++) {
                        $grid[$hari][$j] = [
                            'abbr'    => $abbr,
                            'kelas'   => $namaKelas,
                            'mapel'   => $namaMapel,
                            'mulai'   => $jamMulai,
                            'selesai' => $jamSelesai,
                            'first'   => ($j === $jamMulai),
                            'span'    => ($jamSelesai - $jamMulai + 1),
                        ];
                    }

                    $jam = $jamSelesai - $jamMulai + 1;
                    $mapelCount[$namaMapel] = ($mapelCount[$namaMapel] ?? 0) + $jam;
                }
            }

            $pages[] = [
                'guru'       => $guru,
                'grid'       => $grid,
                'mapelCount' => $mapelCount,
                'totalJam'   => array_sum($mapelCount),
            ];
        }

        $pdf = Pdf::loadView('pdf.jadwal-guru', [
            'pages'         => $pages,
            'jamSlot'       => $this->jamSlot,
            'hariList'      => $this->hariList,
            'activeArchive' => $activeArchive,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('jadwal-guru.pdf');
    }

    // ─── EXPORT PER KELAS ───────────────────────────────────────────────

    protected function printKelas($pilihan, $ids, $activeArchive, $archiveId)
    {
        $kelasQuery = Classroom::orderBy('name');

        if ($pilihan === 'pilih' && $ids) {
            $kelasQuery->whereIn('id', explode(',', $ids));
        }

        $kelasList = $kelasQuery->get();
        $pages     = [];

        foreach ($kelasList as $kelas) {
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $kelas->id)
                ->where('archive_id', $archiveId)
                ->get();

            $grid = [];

            foreach ($schedules as $schedule) {
                $namaMapel = $schedule->subject->name ?? '-';
                $namaGuru  = $schedule->teacher->name ?? '-';
                $abbr      = $this->makeAbbr($namaMapel);

                foreach ($schedule->schedule ?? [] as $slot) {
                    $startTime = $this->resolveStart($slot);
                    $endTime   = $this->resolveEnd($slot);
                    $hari      = $slot['day'] ?? '';

                    if (!$startTime || !$endTime || !$hari) continue;

                    $jamMulai   = $this->timeToJam($startTime);
                    $jamSelesai = $this->timeToJamEnd($endTime);

                    if (!$jamMulai || !$jamSelesai || $jamMulai > $jamSelesai) continue;

                    for ($j = $jamMulai; $j <= $jamSelesai; $j++) {
                        $grid[$hari][$j] = [
                            'abbr'    => $abbr,
                            'guru'    => $namaGuru,
                            'mapel'   => $namaMapel,
                            'mulai'   => $jamMulai,
                            'selesai' => $jamSelesai,
                            'first'   => ($j === $jamMulai),
                            'span'    => ($jamSelesai - $jamMulai + 1),
                        ];
                    }
                }
            }

            $pages[] = [
                'kelas' => $kelas,
                'grid'  => $grid,
            ];
        }

        $pdf = Pdf::loadView('pdf.jadwal-kelas', [
            'pages'         => $pages,
            'jamSlot'       => $this->jamSlot,
            'hariList'      => $this->hariList,
            'activeArchive' => $activeArchive,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('jadwal-kelas.pdf');
    }

    // ─── NORMALISE SLOT ─────────────────────────────────────────────────
    // Handle 3 format slot JSON yang mungkin ada di DB:
    // Format 1: { "day":"Senin", "start":"06:50", "end":"07:35" }
    // Format 2: { "day":"Senin", "start_hour":"6", "start_minute":"50", "end_hour":"7", "end_minute":"35" }
    // Format 3: { "day":"Senin", "jam_mulai":"1", "jam_selesai":"2" }

    protected function resolveStart(array $slot): ?string
    {
        if (!empty($slot['start'])) {
            return $this->normaliseTime($slot['start']);
        }

        if (isset($slot['start_hour'])) {
            $h = str_pad((int)$slot['start_hour'], 2, '0', STR_PAD_LEFT);
            $m = str_pad((int)($slot['start_minute'] ?? 0), 2, '0', STR_PAD_LEFT);
            return "{$h}:{$m}";
        }

        if (!empty($slot['jam_mulai'])) {
            return $this->jamSlot[(int)$slot['jam_mulai']]['start'] ?? null;
        }

        return null;
    }

    protected function resolveEnd(array $slot): ?string
    {
        if (!empty($slot['end'])) {
            return $this->normaliseTime($slot['end']);
        }

        if (isset($slot['end_hour'])) {
            $h = str_pad((int)$slot['end_hour'], 2, '0', STR_PAD_LEFT);
            $m = str_pad((int)($slot['end_minute'] ?? 0), 2, '0', STR_PAD_LEFT);
            return "{$h}:{$m}";
        }

        if (!empty($slot['jam_selesai'])) {
            return $this->jamSlot[(int)$slot['jam_selesai']]['end'] ?? null;
        }

        return null;
    }

    protected function normaliseTime(string $time): string
    {
        $parts = explode(':', $time);
        $h = str_pad((int)($parts[0] ?? 0), 2, '0', STR_PAD_LEFT);
        $m = str_pad((int)($parts[1] ?? 0), 2, '0', STR_PAD_LEFT);
        return "{$h}:{$m}";
    }

    // ─── TIME → NOMOR JAM ───────────────────────────────────────────────

    protected function timeToJam(string $time): ?int
    {
        foreach ($this->jamSlot as $no => $slot) {
            if ($slot['start'] === $time) return $no;
        }

        $min = $this->toMinutes($time);
        foreach ($this->jamSlot as $no => $slot) {
            if ($min >= $this->toMinutes($slot['start']) && $min < $this->toMinutes($slot['end'])) {
                return $no;
            }
        }

        return null;
    }

    protected function timeToJamEnd(string $time): ?int
    {
        foreach ($this->jamSlot as $no => $slot) {
            if ($slot['end'] === $time) return $no;
        }

        $min = $this->toMinutes($time);
        foreach ($this->jamSlot as $no => $slot) {
            if ($min > $this->toMinutes($slot['start']) && $min <= $this->toMinutes($slot['end'])) {
                return $no;
            }
        }

        return $this->timeToJam($time);
    }

    protected function toMinutes(string $time): int
    {
        [$h, $m] = explode(':', $time);
        return ((int)$h * 60) + (int)$m;
    }

    // ─── SINGKATAN MAPEL ────────────────────────────────────────────────

    protected function makeAbbr(string $name): string
    {
        $map = [
            'Bahasa Indonesia'                         => 'Bind',
            'Bahasa Inggris'                           => 'Bing',
            'Matematika'                               => 'Mat',
            'IPAS'                                     => 'Ipas',
            'Pendidikan Pancasila'                     => 'PP',
            'Sejarah Indonesia'                        => 'SI',
            'Pendidikan Agama Islam'                   => 'PAI',
            'Pend. Agama dan Budi Pekerti'             => 'PABP',
            'PenjasOrkes'                              => 'PJOK',
            'Seni Budaya'                              => 'SB',
            'Bimbingan Konseling'                      => 'BK',
            'Informatika'                              => 'Infor',
            'Kreativitas, Inovasi, dan Kewirausahaan'  => 'KIK',
            'Dasar Program Keahlian 1'                 => 'DPK1',
            'Dasar Program Keahlian 2'                 => 'DPK2',
            'Dasar Program Keahlian 3'                 => 'DPK3',
            'Konsentrasi Keahlian 1'                   => 'KK 1',
            'Konsentrasi Keahlian 2'                   => 'KK 2',
            'Konsentrasi Keahlian 3'                   => 'KK 3',
            'Mapel Pilihan'                            => 'MP',
            'Koding dan Kecerdasan Artifisial'         => 'KKA',
            'Bahasa Daerah (Madura)'                   => 'BDar M',
            'Bahasa Daerah(Jawa)'                      => 'BDar J',
        ];

        return $map[$name] ?? mb_substr($name, 0, 4);
    }
}