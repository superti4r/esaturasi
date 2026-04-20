<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\Widget;

class JadwalHariIni extends Widget
{
    protected static string $view = 'filament.widgets.jadwal-hari-ini';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->hasRole('guru');
    }

    public function getJadwal(): array
    {
        $guru = Auth::user();
        $hariIni = Carbon::now()->locale('id')->dayName;
        $sekarang = Carbon::now()->format('H:i');

        $schedules = Schedule::where('teacher_id', $guru->id)
            ->with(['classroom', 'subject'])
            ->get();

        $result = [];

        foreach ($schedules as $schedule) {
            foreach ($schedule->schedule as $slot) {
                if (strtolower($slot['day']) === strtolower($hariIni)) {
                    $start = $slot['start'];
                    $end   = $slot['end'];

                    if ($sekarang > $end) {
                        $status = 'selesai';
                    } elseif ($sekarang >= $start && $sekarang <= $end) {
                        $status = 'berlangsung';
                    } else {
                        $status = 'mendatang';
                    }

                    $result[] = [
                        'start'  => $start,
                        'end'    => $end,
                        'mapel'  => $schedule->subject->name ?? '-',
                        'kelas'  => $schedule->classroom->name ?? '-',
                        'status' => $status,
                    ];
                }
            }
        }

        usort($result, fn($a, $b) => $a['start'] <=> $b['start']);

        return $result;
    }
}