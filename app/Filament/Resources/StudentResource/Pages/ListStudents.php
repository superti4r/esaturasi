<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Imports\StudentsImport;
use App\Models\Classroom;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StudentResource;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

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
                        ->label('File Excel')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required()
                        ->helperText('Upload file .xlsx atau .xls. Pastikan kolom: Nama, NISN, JK, Tempat Lahir, Tanggal Lahir, Rombel Saat Ini'),
                ])
                ->action(function (array $data) {
                    $file = $data['file'];
                    $path = storage_path('app/public/' . $file);

                    $import = new StudentsImport();
                    Excel::import($import, $path);

                    Notification::make()
                        ->title('Import Berhasil!')
                        ->body("✅ {$import->importedCount} siswa berhasil diimport. {$import->skippedCount} data dilewati (duplikat/kosong).")
                        ->success()
                        ->duration(6000)
                        ->send();
                })
                ->modalHeading('Import Data Siswa dari Excel')
                ->modalButton('Import Sekarang'),

            Action::make('export')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Select::make('classroom_id')
                        ->label('Pilih Kelas')
                        ->placeholder('— Cetak Semua Kelas —')
                        ->options(
                            Classroom::orderBy('name')->pluck('name', 'id')->toArray()
                        )
                        ->searchable()
                        ->nullable(),
                ])
                ->action(function (array $data) {
                    $classroomId = $data['classroom_id'] ?? null;

                    $url = $classroomId
                        ? route('print.students', ['classroom_id' => $classroomId])
                        : route('print.students');

                    $this->redirect($url, navigate: false);
                })
                ->modalHeading('Cetak Data Siswa')
                ->modalDescription('Pilih kelas yang ingin dicetak, atau kosongkan untuk mencetak semua kelas.')
                ->modalButton('Cetak Sekarang'),
        ];
    }
}