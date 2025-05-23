<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Archive;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ArchiveResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArchiveResource\RelationManagers;

class ArchiveResource extends Resource
{
    protected static ?string $model = Archive::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Arsip Data';
    protected static ?string $pluralModelLabel = 'Arsip Data';
    protected static ?string $modelLabel = 'Arsip Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Arsip')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama arsip')
                            ->rule(function ($record) {
                                return Rule::unique('archive', 'name')->ignore($record?->id);
                            }),

                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Active' => 'Aktif',
                                'Not Active' => 'Tidak Aktif',
                            ])
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'Active',
                        'gray' => 'Not Active',
                    ])
                    ->formatStateUsing(fn(string $state) => $state === 'Active' ? 'Aktif' : 'Tidak Aktif')
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArchives::route('/'),
            'create' => Pages\CreateArchive::route('/create'),
            'edit' => Pages\EditArchive::route('/{record}/edit'),
        ];
    }
}
