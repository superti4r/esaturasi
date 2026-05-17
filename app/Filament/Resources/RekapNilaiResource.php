<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapNilaiResource\Pages;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class RekapNilaiResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup  = 'Master Data';
    protected static ?int    $navigationSort   = 12;
    protected static ?string $navigationIcon   = 'heroicon-o-table-cells';
    protected static ?string $navigationLabel  = 'Rekap Nilai';
    protected static ?string $pluralModelLabel = 'Rekap Nilai';
    protected static ?string $modelLabel       = 'Rekap Nilai';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if ($user?->hasRole(config('filament-shield.super_admin.name'))) {
            return true;
        }

        return $user?->can('view_any_rekap::nilai') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapNilai::route('/'),
        ];
    }
}