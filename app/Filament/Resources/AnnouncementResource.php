<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Archive;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Announcement;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 8;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?string $pluralModelLabel = 'Pengumuman';
    protected static ?string $modelLabel = 'Pengumuman';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->placeholder('Masukkan judul pengumuman')
                            ->maxLength(255),

                        Select::make('archive_id')
                            ->label('Arsip Semester')
                            ->options(
                                Archive::where('status', 'Active')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->placeholder('Pilih Arsip Aktif')
                            ->required(),

                        RichEditor::make('content')
                            ->label('Konten Pengumuman')
                            ->columnSpanFull()
                            ->placeholder('Masukkan isi pengumuman')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->query(
                Announcement::query()->whereHas('archive', function ($query) {
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
