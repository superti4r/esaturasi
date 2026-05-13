<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Imports\UsersImport;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel Guru')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required()
                        ->helperText('Upload file .xlsx dengan kolom: NO, NAMA, GOL, NIP'),
                ])
                ->action(function (array $data) {
                    $path = storage_path('app/public/' . $data['file']);

                    $import = new UsersImport();
                    Excel::import($import, $path);

                    Notification::make()
                        ->title('Import Berhasil!')
                        ->body("✅ {$import->importedCount} guru berhasil diimport. {$import->skippedCount} data dilewati.")
                        ->success()
                        ->duration(6000)
                        ->send();
                })
                ->modalHeading('Import Data Guru dari Excel')
                ->modalButton('Import Sekarang'),
        ];
    }
}