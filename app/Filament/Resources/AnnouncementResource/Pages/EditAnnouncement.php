<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Archive;
use Filament\Notifications\Notification;

class EditAnnouncement extends EditRecord
{
    protected static string $resource = AnnouncementResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum mengubah pengumuman.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(AnnouncementResource::getUrl());
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
