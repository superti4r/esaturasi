<?php

namespace App\Filament\Resources\SlugResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Storage;

class SubjectMatterRelationManager extends RelationManager
{
    protected static string $relationship = 'subjectMatter';

    protected static ?string $title = 'Materi';
    protected static ?string $label = 'Materi';
    protected static ?string $pluralLabel = 'Materi';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul')
                    ->placeholder('Masukkan judul materi')
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Masukkan deskripsi materi')
                    ->maxLength(100),

                FileUpload::make('file_path')
                    ->label('File Materi')
                    ->directory('materi')
                    ->acceptedFileTypes([
                        'image/*', 'video/*',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                    ])
                    ->maxSize(512000),
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
                    ->label('Deskripsi'),

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
