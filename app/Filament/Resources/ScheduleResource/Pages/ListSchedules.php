<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Archive;
use App\Models\Schedule;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ScheduleResource;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;
    protected static string $view = 'custom.schedule-card';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('export')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(route('print.schedules'))
                ->openUrlInNewTab(),

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
        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])->get();

        return [
            'groupedSchedules' => $schedules->groupBy(fn($item) => $item->classroom->name ?? 'Tanpa Kelas'),
            'recordPageUrl' => fn(Schedule $schedule) => ScheduleResource::getUrl('edit', ['record' => $schedule]),
            'headerActions' => $this->getHeaderActions(),
        ];
    }
}
