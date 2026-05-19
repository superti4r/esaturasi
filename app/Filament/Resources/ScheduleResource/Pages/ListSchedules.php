<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Models\Schedule;
use App\Models\Archive;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ScheduleResource;
use Illuminate\Support\Facades\Auth;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;
    protected static string $view = 'custom.schedule-card';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        if (!Auth::user()?->can('create_schedule')) {
            return [];
        }

        return [
            CreateAction::make(),
         
        ];
    }

    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu agar data jadwal dapat diatur.')
                ->danger()
                ->persistent()
                ->send();
        }
    }

    public function getViewData(): array
    {
        $user    = Auth::user();
        $isAdmin = $user?->can('view_any_schedule') ?? false;

        $query = Schedule::with(['classroom', 'subject', 'teacher'])
            ->whereHas('archive', fn($q) => $q->where('status', 'Active'));

        if (!$isAdmin) {
            $query->where('teacher_id', $user->id);
        }

        $schedules = $query->get();

        return [
            'groupedSchedules' => $schedules->groupBy(fn($item) => $item->classroom->name ?? 'Tanpa Kelas'),
            'recordPageUrl'    => fn(Schedule $schedule) => ScheduleResource::getUrl('edit', ['record' => $schedule]),
            'headerActions'    => $this->getHeaderActions(),
            'isAdmin'          => $isAdmin,
        ];
    }
}