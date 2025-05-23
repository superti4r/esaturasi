<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Classroom;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClassroomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassroomResource\RelationManagers;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                TextInput::make('name')
                    ->label('Nama Kelas')
                    ->placeholder('Masukkan nama kelas')
                    ->required()
                    ->maxLength(255)
                    ->rule(function ($record) {
                        return Rule::unique('classroom', 'name')->ignore($record?->id);
                    }),            

                Select::make('major_id')
                    ->label('Jurusan')
                    ->relationship('major', 'name')
                    ->placeholder('Pilih jurusan')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable(),

                TextColumn::make('major.name')
                    ->label('Jurusan')
                    ->badge()
                    ->colors(['primary'])
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
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
