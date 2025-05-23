<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Archive;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ScheduleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ScheduleResource\RelationManagers;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal';
    protected static ?string $modelLabel = 'Jadwal';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->label('Kelas')
                    ->required(),

                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('Mata Pelajaran')
                    ->required(),

                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->label('Guru Pengajar')
                    ->required(),

                Select::make('archive_id')
                    ->relationship('archive', 'name')
                    ->label('Arsip')
                    ->options(fn() => Archive::where('status', 'Active')->pluck('name', 'id'))
                    ->required(),

                Repeater::make('schedule')
                    ->label('Jadwal')
                    ->schema([
                        Select::make('day')
                            ->label('Hari')
                            ->options([
                                'Senin' => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu' => 'Rabu',
                                'Kamis' => 'Kamis',
                                'Jumat' => 'Jumat',
                            ])
                            ->required(),

                        TimePicker::make('start')
                            ->label('Jam Mulai')
                            ->seconds(false)
                            ->required(),

                        TimePicker::make('end')
                            ->label('Jam Selesai')
                            ->seconds(false)
                            ->required(),
                    ])
                    ->createItemButtonLabel('Tambah Jadwal')
                    ->columns(3)
                    ->required(),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Schedule::query()->whereHas('archive', function ($query) {
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
