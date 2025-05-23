<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slugs;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\SlugResource\Pages;
use App\Filament\Resources\SlugResource\RelationManagers\SubjectMatterRelationManager;
use App\Filament\Resources\SlugResource\RelationManagers\TaskRelationManager;

class SlugResource extends Resource
{
    protected static ?string $model = Slugs::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Manajemen Tugas & Materi';
    protected static ?string $pluralModelLabel = 'Manajemen Tugas & Materi';
    protected static ?string $modelLabel = 'Manajemen Tugas & Materi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->columns(2)->schema([
                    Select::make('schedule_id')
                        ->label('Jadwal')
                        ->options(function () {
                            return Schedule::with(['classroom', 'subject'])
                                ->get()
                                ->mapWithKeys(function ($schedule) {
                                    return [
                                        $schedule->id => $schedule->classroom->name . ' - ' . $schedule->subject->name,
                                    ];
                                });
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('title')
                        ->label('Judul')
                        ->placeholder('Masukkan judul')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('schedule.classroom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('schedule.subject.name')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slug')
                    ->label('Identitas')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubjectMatterRelationManager::class,
            TaskRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlugs::route('/'),
            'create' => Pages\CreateSlug::route('/create'),
            'edit' => Pages\EditSlug::route('/{record}/edit'),
        ];
    }
}
