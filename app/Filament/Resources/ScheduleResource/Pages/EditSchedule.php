<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Archive;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ScheduleResource;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum mengubah jadwal.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(ScheduleResource::getUrl());
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
