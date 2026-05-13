<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate kode guru jika tidak diisi manual
        if (empty($data['kode_guru'])) {
            $last = User::whereNotNull('kode_guru')
                ->orderByDesc('kode_guru')
                ->value('kode_guru');

            $number = $last ? ((int) substr($last, 2)) + 1 : 1;
            $data['kode_guru'] = 'GR' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        return $data;
    }
}