<?php

namespace App\Filament\Resources\SubmissionAndAssessmentResource\Pages;

use App\Filament\Resources\SubmissionAndAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubmissionAndAssessments extends ListRecords
{
    protected static string $resource = SubmissionAndAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('print.assessment', request()->query()))
                ->requiresConfirmation(),
        ];
    }
}
