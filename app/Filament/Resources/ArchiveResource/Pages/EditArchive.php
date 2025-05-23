<?php

namespace App\Filament\Resources\ArchiveResource\Pages;

use App\Models\Archive;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ArchiveResource;

class EditArchive extends EditRecord
{
    protected static string $resource = ArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === 'Active' && $this->record->status !== 'Active') {
            Archive::where('status', 'Active')->update(['status' => 'Not Active']);
        }

        return $data;
    }
}
