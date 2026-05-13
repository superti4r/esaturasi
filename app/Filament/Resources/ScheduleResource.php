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
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal';
    protected static ?string $modelLabel = 'Jadwal';

    // ✅ Hanya Administrator dan guru yang lihat menu ini
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasAnyRole(['Administrator', 'guru']);
    }

    // ✅ Hanya Administrator yang bisa create/edit/delete
    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole('Administrator') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasRole('Administrator') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->hasRole('Administrator') ?? false;
    }

    public static function form(Form $form): Form
    {
        $activeArchive = Archive::where('status', 'Active')->first();

        $jamMap = [
            '1'  => ['start' => '06:50', 'end' => '07:35'],
            '2'  => ['start' => '07:35', 'end' => '08:15'],
            '3'  => ['start' => '08:15', 'end' => '08:55'],
            '4'  => ['start' => '08:55', 'end' => '09:35'],
            '5'  => ['start' => '09:50', 'end' => '10:30'],
            '6'  => ['start' => '10:30', 'end' => '11:10'],
            '7'  => ['start' => '11:10', 'end' => '11:50'],
            '8'  => ['start' => '12:20', 'end' => '12:55'],
            '9'  => ['start' => '12:55', 'end' => '13:30'],
            '10' => ['start' => '13:30', 'end' => '14:05'],
            '11' => ['start' => '14:05', 'end' => '14:40'],
            '12' => ['start' => '14:40', 'end' => '15:15'],
        ];

        return $form->schema([
            Card::make([
                Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('subject_id')
    ->label('Mata Pelajaran')
    ->options(function () use ($activeArchive) {
        return \App\Models\Subject::where('archive_id', $activeArchive?->id)
            ->pluck('name', 'id');
    })
    ->searchable()
    ->required(),

                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->label('Guru Pengajar')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('archive_id')
                    ->label('Arsip (Tahun Aktif)')
                    ->options(fn() => Archive::where('status', 'Active')->pluck('name', 'id'))
                    ->default($activeArchive?->id)
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Repeater::make('schedule')
                    ->label('Jadwal')
                    ->schema([
                        Select::make('day')
                            ->label('Hari')
                            ->options([
                                'Senin'  => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu'   => 'Rabu',
                                'Kamis'  => 'Kamis',
                                'Jumat'  => 'Jumat',
                            ])
                            ->searchable()
                            ->required(),

                        Select::make('jam_mulai')
                            ->label('Jam Mulai')
                            ->options([
                                '1'  => 'Jam 1  (06:50)',
                                '2'  => 'Jam 2  (07:35)',
                                '3'  => 'Jam 3  (08:15)',
                                '4'  => 'Jam 4  (08:55)',
                                '5'  => 'Jam 5  (09:50)',
                                '6'  => 'Jam 6  (10:30)',
                                '7'  => 'Jam 7  (11:10)',
                                '8'  => 'Jam 8  (12:20)',
                                '9'  => 'Jam 9  (12:55)',
                                '10' => 'Jam 10 (13:30)',
                                '11' => 'Jam 11 (14:05)',
                                '12' => 'Jam 12 (14:40)',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) use ($jamMap) {
                                if (isset($jamMap[$state])) {
                                    $set('start', $jamMap[$state]['start']);
                                }
                            }),

                        Select::make('jam_selesai')
                            ->label('Jam Selesai')
                            ->options([
                                '1'  => 'Jam 1  (07:35)',
                                '2'  => 'Jam 2  (08:15)',
                                '3'  => 'Jam 3  (08:55)',
                                '4'  => 'Jam 4  (09:35)',
                                '5'  => 'Jam 5  (10:30)',
                                '6'  => 'Jam 6  (11:10)',
                                '7'  => 'Jam 7  (11:50)',
                                '8'  => 'Jam 8  (12:55)',
                                '9'  => 'Jam 9  (13:30)',
                                '10' => 'Jam 10 (14:05)',
                                '11' => 'Jam 11 (14:40)',
                                '12' => 'Jam 12 (15:15)',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) use ($jamMap) {
                                if (isset($jamMap[$state])) {
                                    $set('end', $jamMap[$state]['end']);
                                }
                            }),

                        Forms\Components\Hidden::make('start'),
                        Forms\Components\Hidden::make('end'),
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
            ->columns([
                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('schedule')
                    ->label('Jadwal')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        return collect($state)
                            ->map(fn($s) => "{$s['day']} {$s['start']}-{$s['end']}")
                            ->join(' | ');
                    })
                    ->wrap(),
            ])
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
            'index'  => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit'   => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}