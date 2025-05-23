<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Archive;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

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
                ->body('Silakan tambahkan arsip terlebih dahulu agar data mata pelajaran dapat diatur.')
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
