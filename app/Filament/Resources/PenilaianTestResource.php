<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianTestResource\Pages;
use App\Models\Classroom;
use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PenilaianTestResource extends Resource
{
    
    protected static ?string $model = HasilPretest::class;

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int    $navigationSort  = 11;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Penilaian Pre & Post Test';
    protected static ?string $pluralModelLabel = 'Penilaian Pre & Post Test';
    protected static ?string $modelLabel       = 'Penilaian Test';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([])->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaianTests::route('/'),
        ];
    }
}