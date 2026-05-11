<?php

namespace App\Filament\Resources\SlugResource\Pages;

use App\Filament\Resources\SlugResource;
use Filament\Resources\Pages\EditRecord;

class EditSlug extends EditRecord
{
    protected static string $resource = SlugResource::class;

    protected ?string $heading = 'Detail Bab & Aktivitas';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()->hidden();
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()->hidden();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $schedule = \App\Models\Schedule::find($data['schedule_id']);
        if ($schedule) {
            $data['classroom_id'] = $schedule->classroom_id;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['classroom_id']);
        return $data;
    }
}
