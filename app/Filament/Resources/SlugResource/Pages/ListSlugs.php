<?php

namespace App\Filament\Resources\SlugResource\Pages;

use App\Filament\Resources\SlugResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSlugs extends ListRecords
{
    protected static string $resource = SlugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
