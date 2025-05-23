<?php

namespace App\Filament\Resources\MajorResource\Pages;

use App\Filament\Resources\MajorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMajor extends EditRecord
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
