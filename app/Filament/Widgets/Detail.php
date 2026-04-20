<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Detail extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->statsAdmin();
        }

        return $this->statsGuru($user);
    }

    protected function statsAdmin(): array
    {
        return [
            Stat::make('Total Guru', User::role('guru')->count())
                ->description('Guru terdaftar')
                ->descriptionIcon('heroicon-m-user')
                ->chart([1, 2, 3, User::role('guru')->count()])
                ->color('primary')
                ->icon('heroicon-o-user'),

            Stat::make('Total Siswa', Student::count())
                ->description('Seluruh siswa')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([50, 100, 150, Student::count()])
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Total Kelas', Classroom::count())
                ->description('Semua kelas aktif')
                ->descriptionIcon('heroicon-m-building-library')
                ->chart([1, 2, 3, Classroom::count()])
                ->color('warning')
                ->icon('heroicon-o-building-library'),

            Stat::make('Pengumuman', Announcement::count())
                ->description('Total pengumuman')
                ->descriptionIcon('heroicon-m-megaphone')
                ->chart([1, 2, 3, Announcement::count()])
                ->color('success')
                ->icon('heroicon-o-megaphone'),
        ];
    }

    protected function statsGuru($user): array
    {
        $jadwalGuru    = Schedule::where('teacher_id', $user->id)->get();
        $classroomIds  = $jadwalGuru->pluck('classroom_id')->unique();
        $kelasCount    = $classroomIds->count();
        $siswaCount    = Student::whereIn('classroom_id', $classroomIds)->count();
        $jadwalCount   = $jadwalGuru->count();
        $pengumumanCount = Announcement::count();

        return [
            Stat::make('Kelas Diampu', $kelasCount)
                ->description('Kelas yang Anda ampu')
                ->descriptionIcon('heroicon-m-building-library')
                ->chart([1, 2, 3, $kelasCount])
                ->color('warning')
                ->icon('heroicon-o-building-library'),

            Stat::make('Jumlah Siswa', $siswaCount)
                ->description('Siswa di kelas Anda')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([50, 100, 150, $siswaCount])
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Jadwal Mengajar', $jadwalCount)
                ->description('Total jadwal Anda')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([1, 2, 3, $jadwalCount])
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('Pengumuman', $pengumumanCount)
                ->description('Pengumuman aktif')
                ->descriptionIcon('heroicon-m-megaphone')
                ->chart([1, 2, 3, $pengumumanCount])
                ->color('success')
                ->icon('heroicon-o-megaphone'),
        ];
    }
}