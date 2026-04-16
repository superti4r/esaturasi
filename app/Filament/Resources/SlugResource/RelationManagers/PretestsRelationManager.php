<?php

namespace App\Filament\Resources\SlugResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SoalPretestImport;
use Illuminate\Support\Facades\Storage;

class PretestsRelationManager extends RelationManager
{
    protected static string $relationship = 'pretests';

    public function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\TextInput::make('judul')
                ->required(),

            Forms\Components\TextInput::make('kkm')
                ->numeric()
                ->default(75),

            Forms\Components\DateTimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->seconds(false)
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->seconds(false)
                ->after('waktu_mulai')
                ->required(),

            // BAGIAN SOAL MANUAL
            Forms\Components\Repeater::make('soal')
                ->relationship()
                ->label('Daftar Soal')
                ->schema([
                    Forms\Components\Textarea::make('soal')
                        ->label('Pertanyaan')
                        ->required(),

                    Forms\Components\TextInput::make('opsi_a')->label('Opsi A')->required(),
                    Forms\Components\TextInput::make('opsi_b')->label('Opsi B')->required(),
                    Forms\Components\TextInput::make('opsi_c')->label('Opsi C')->required(),
                    Forms\Components\TextInput::make('opsi_d')->label('Opsi D')->required(),

                    Forms\Components\Select::make('jawaban')
                        ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'])
                        ->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->createItemButtonLabel('Tambah Soal'),

            // UPLOAD EXCEL
            Forms\Components\FileUpload::make('file_soal')
                ->label('Upload Soal via Excel')
                ->directory('bank-soal')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ])
                ->helperText('Format kolom: soal, opsi_a, opsi_b, opsi_c, opsi_d, jawaban'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->columns([
                Tables\Columns\TextColumn::make('judul'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($record) {
                        // Jalankan import SETELAH record pretest tersimpan
                        if ($record->file_soal) {
                            $path = Storage::disk('public')->path($record->file_soal);

                            if (file_exists($path)) {
                                Excel::import(
                                    new SoalPretestImport($record->id),
                                    $path
                                );
                            }
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        // Juga handle saat edit & ganti file
                        if ($record->file_soal) {
                            $path = Storage::disk('public')->path($record->file_soal);

                            if (file_exists($path)) {
                                Excel::import(
                                    new SoalPretestImport($record->id),
                                    $path
                                );
                            }
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}