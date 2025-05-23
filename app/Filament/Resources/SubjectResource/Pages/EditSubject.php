<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Archive;
use Filament\Notifications\Notification;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum mengubah mata pelajaran.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(SubjectResource::getUrl());
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
