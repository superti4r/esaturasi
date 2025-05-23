<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Archive;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu agar data pengumuman dapat diatur.')
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
