<?php

namespace App\Filament\Resources\SubmissionAndAssessmentResource\Pages;

use App\Filament\Resources\SubmissionAndAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubmissionAndAssessment extends EditRecord
{
    protected static string $resource = SubmissionAndAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
