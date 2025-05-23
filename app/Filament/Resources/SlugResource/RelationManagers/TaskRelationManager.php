<?php

namespace App\Filament\Resources\SlugResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class TaskRelationManager extends RelationManager
{
    protected static string $relationship = 'task';

    protected static ?string $title = 'Tugas';
    protected static ?string $label = 'Tugas';
    protected static ?string $pluralLabel = 'Tugas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul Tugas')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(1000),

                FileUpload::make('file_path')
                    ->label('Lampiran Tugas')
                    ->directory('tugas')
                    ->acceptedFileTypes([
                        'image/*', 'video/*',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                    ])
                    ->maxSize(512000),

                DateTimePicker::make('deadline')
                    ->label('Batas Waktu')
                    ->seconds(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul'),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->description),

                TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800">
                                        Tidak ada file
                                    </span>';
                        }
                
                        $url = asset('storage/' . $state);
                        $name = basename($state);
                
                        return '<a href="' . $url . '" target="_blank" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 hover:underline">
                                    ' . $name . '
                                </a>';
                    })
                    ->html(),

                TextColumn::make('deadline')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn ($record) => !empty($record->file_path))
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->color('secondary'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
