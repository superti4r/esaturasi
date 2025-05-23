<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Archive;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;

    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum membuat mata pelajaran.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(SubjectResource::getUrl());
        }
    }
}
