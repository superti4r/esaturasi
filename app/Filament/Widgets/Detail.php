<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Detail extends BaseWidget
{
    protected function getStats(): array
    {
        $aktifDalamDetik = 15 * 60;
        $timestampSekarang = now()->timestamp;

        $penggunaAktif = DB::table('sessions')
            ->where('last_activity', '>=', $timestampSekarang - $aktifDalamDetik)
            ->distinct('user_id')
            ->count('user_id');

        return [
            Stat::make('Pengguna', User::count())
                ->description('Semua pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->icon('heroicon-o-user-group'),

            Stat::make('Pengguna Aktif', $penggunaAktif)
                ->description('Aktif dalam 15 menit terakhir')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success')
                ->icon('heroicon-o-bolt'),

            Stat::make('Siswa', Student::count())
                ->description('Jumlah seluruh siswa')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Kelas', Classroom::count())
                ->description('Kelas aktif saat ini')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning')
                ->icon('heroicon-o-building-library'),
        ];
    }
}
