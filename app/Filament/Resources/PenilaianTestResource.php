<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianTestResource\Pages;
use App\Models\HasilPretest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PenilaianTestResource extends Resource
{
    protected static ?string $model              = HasilPretest::class;
    protected static ?string $navigationGroup    = 'Master Data';
    protected static ?int    $navigationSort     = 11;
    protected static ?string $navigationIcon     = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel    = 'Penilaian';
    protected static ?string $pluralModelLabel   = 'Penilaian';
    protected static ?string $modelLabel         = 'Penilaian';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_penilaian::test') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_penilaian::test') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaianTests::route('/'),
        ];
    }
}
