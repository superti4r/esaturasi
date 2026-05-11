<?php

namespace App\Filament\Resources\SlugResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SoalPosttestImport;
use App\Models\SoalPosttest;
use Illuminate\Support\Facades\Storage;

class PosttestsRelationManager extends RelationManager
{
    protected static string $relationship = 'posttests';

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

            Forms\Components\Repeater::make('soal')
                ->relationship()
                ->label('Daftar Soal')
                ->columnSpanFull()
                ->grid([
                    'md' => 2,
                    'xl' => 3,
                ])
                ->itemLabel(function (array $state, \Filament\Forms\Components\Repeater $component) {
                    $items = $component->getState() ?? [];
                    foreach (array_values($items) as $index => $item) {
                        if ($item === $state) {
                            return 'Pertanyaan ' . ($index + 1);
                        }
                    }
                    return 'Pertanyaan';
                })
                ->schema([
                    Forms\Components\Textarea::make('soal')
                        ->label('Pertanyaan')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('opsi_a')->label('Opsi A')->required(),
                    Forms\Components\TextInput::make('opsi_b')->label('Opsi B')->required(),
                    Forms\Components\TextInput::make('opsi_c')->label('Opsi C')->required(),
                    Forms\Components\TextInput::make('opsi_d')->label('Opsi D')->required(),

                    Forms\Components\Select::make('jawaban')
                        ->label('Jawaban Benar')
                        ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'])
                        ->required(),

                    Forms\Components\TextInput::make('poin')
                        ->label('Poin')
                        ->numeric()
                        ->default(10)
                        ->required()
                        ->suffix('point'),
                ])
                ->columns(2)
                ->collapsible()
                ->createItemButtonLabel('Tambah Soal')
                ->rule(function () {
                    return function ($attribute, $value, $fail) {
                        $total = collect($value)->sum(fn($item) => (int) ($item['poin'] ?? 0));
                        if ($total > 100) {
                            $fail("Total poin melebihi batas (100). Sekarang: $total");
                        }
                    };
                }),

            Forms\Components\Placeholder::make('total_poin')
                ->label('Total Poin')
                ->content(function ($get) {
                    $total = collect($get('soal') ?? [])->sum(fn($item) => (int) ($item['poin'] ?? 0));
                    return $total > 100
                        ? "Total poin: $total (Melebihi batas!)"
                        : "Total poin: $total / 100";
                }),

            Forms\Components\FileUpload::make('file_soal')
                ->label('Upload Soal via Excel')
                ->directory('bank-soal')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])
                ->helperText('Format kolom: soal, opsi_a, opsi_b, opsi_c, opsi_d, jawaban, poin'),
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
                        $this->importExcelIfExists($record);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        $this->importExcelIfExists($record);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * ✅ Import Excel dan hapus soal lama sebelum insert
     * Logika delete sudah ada di SoalPosttestImport::registerEvents()
     */
    private function importExcelIfExists($record): void
    {
        if (empty($record->file_soal)) return;

        $path = Storage::disk('public')->path($record->file_soal);

        if (!file_exists($path)) return;

        Excel::import(new SoalPosttestImport($record->id), $path);
    }
}