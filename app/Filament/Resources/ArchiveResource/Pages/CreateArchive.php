<?php

namespace App\Filament\Resources\ArchiveResource\Pages;

use App\Models\Archive;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArchiveResource;

class CreateArchive extends CreateRecord
{
    protected static string $resource = ArchiveResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['status'] === 'Active') {
            Archive::where('status', 'Active')->update(['status' => 'Not Active']);
        }

        return $data;
    }
}
