<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StudentResource;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('export')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function () {
                    return redirect()->away(route('print.students'));
                })
                ->requiresConfirmation()
                ->modalHeading('Cetak Data Siswa')
                ->modalDescription('Apakah Anda yakin ingin mencetak data siswa dalam format PDF?')
                ->modalButton('Cetak sekarang'),
        ];
    }
}
