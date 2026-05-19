<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slugs;
use App\Models\Archive;
use App\Models\Schedule;
use App\Models\Classroom;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use App\Filament\Resources\SlugResource\Pages;
use App\Filament\Resources\SlugResource\RelationManagers\SubjectMatterRelationManager;
use App\Filament\Resources\SlugResource\RelationManagers\TaskRelationManager;
use App\Filament\Resources\SlugResource\RelationManagers\PretestsRelationManager;
use App\Filament\Resources\SlugResource\RelationManagers\PosttestsRelationManager;

class SlugResource extends Resource
{
    protected static ?string $model = Slugs::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Manajemen Tugas & Materi';
    protected static ?string $pluralModelLabel = 'Manajemen Tugas & Materi';
    protected static ?string $modelLabel = 'Manajemen Tugas & Materi';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_slug') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_slug') ?? false;
    }

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        if ($isEdit) {
            return $form->schema([]);
        }

        return $form->schema([
            Card::make()->columns(2)->schema([
                Select::make('classroom_id')
                    ->label('Kelas')
                    ->options(function () {
                        $guruId = auth()->id();
                        return Classroom::whereHas('schedules', function ($q) use ($guruId) {
                            $q->where('teacher_id', $guruId)
                              ->whereHas('archive', fn($q) => $q->where('status', 'Active'));
                        })->orderBy('name')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($set) => $set('schedule_id', null)),

                Select::make('schedule_id')
                    ->label('Mata Pelajaran')
                    ->options(function (Get $get) {
                        $classroomId = $get('classroom_id');
                        $guruId = auth()->id();
                        if (!$classroomId) return [];

                        return Schedule::with('subject')
                            ->where('classroom_id', $classroomId)
                            ->where('teacher_id', $guruId)
                            ->whereHas('archive', fn($q) => $q->where('status', 'Active'))
                            ->get()
                            ->mapWithKeys(fn($s) => [$s->id => $s->subject->name ?? '-']);
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->disabled(fn(Get $get) => !$get('classroom_id'))
                    ->hint(fn(Get $get) => !$get('classroom_id') ? 'Pilih kelas terlebih dahulu' : null),

                TextInput::make('title')
                    ->label('Judul')
                    ->placeholder('Masukkan judul')
                    ->required()
                    ->columnSpanFull(),
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
                    ->label('Bab')
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
            PretestsRelationManager::class,
            PosttestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSlugs::route('/'),
            'create' => Pages\CreateSlug::route('/create'),
            'edit'   => Pages\EditSlug::route('/{record}/edit'),
        ];
    }
}