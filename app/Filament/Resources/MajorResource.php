<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Major;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MajorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MajorResource\RelationManagers;

class MajorResource extends Resource
{
    protected static ?string $model = Major::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Jurusan';
    protected static ?string $pluralModelLabel = 'Jurusan';
    protected static ?string $modelLabel = 'Jurusan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                TextInput::make('major_code')
                    ->label('Kode Jurusan')
                    ->required()
                    ->placeholder('Masukkan kode jurusan (misal : TKJ)')
                    ->maxLength(8)
                    ->rule(function ($record) {
                        return Rule::unique('major', 'major_code')->ignore($record?->id);
                    }),

                TextInput::make('name')
                    ->label('Nama Jurusan')
                    ->required()
                    ->placeholder('Masukkan nama jurusan')
                    ->maxLength(255)
                    ->rule(function ($record) {
                        return Rule::unique('major', 'name')->ignore($record?->id);
                    }),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('major_code')
                    ->label('Kode Jurusan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama Jurusan')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMajors::route('/'),
            'create' => Pages\CreateMajor::route('/create'),
            'edit' => Pages\EditMajor::route('/{record}/edit'),
        ];
    }
}
