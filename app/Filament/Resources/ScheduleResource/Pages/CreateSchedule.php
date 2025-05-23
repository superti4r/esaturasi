<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Archive;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum membuat jadwal.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(ScheduleResource::getUrl());
        }
    }
}
