<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Archive;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip tahun ajaran terlebih dahulu sebelum menambah siswa.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(StudentResource::getUrl());
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['password'])) {
            $data['password'] = bcrypt($data['nisn']);
        }

        $data['address']    = !empty($data['address']) ? $data['address'] : null;
        $data['email']      = !empty($data['email']) ? $data['email'] : null;
        $data['avatar_url'] = !empty($data['avatar_url']) ? $data['avatar_url'] : null;

        return $data;
    }
}