<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Archive;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $pluralModelLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel = 'Mata Pelajaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Mata Pelajaran')
                            ->required()
                            ->placeholder('Masukkan nama mata pelajaran')
                            ->maxLength(255)
                            ->rule(function ($record) {
                                return Rule::unique('subject', 'name')->ignore($record?->id);
                            }),

                        Select::make('archive_id')
                            ->label('Arsip Semester')
                            ->options(function () {
                                return Archive::where('status', 'Active')->pluck('name', 'id');
                            })
                            ->searchable()
                            ->placeholder('Pilih Arsip Aktif')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Mata Pelajaran')
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
            ])
            ->query(
                Subject::query()->whereHas('archive', function ($query) {
                    $query->where('status', 'Active');
                })
            );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
