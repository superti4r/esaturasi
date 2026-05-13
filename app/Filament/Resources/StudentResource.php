<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use App\Models\Classroom;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';
    protected static ?string $modelLabel = 'Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->columns(2)->schema([
                    TextInput::make('nisn')
                        ->label('NISN')
                        ->placeholder('Masukkan NISN')
                        ->required()
                        ->numeric()
                        ->minLength(10)
                        ->maxLength(10)
                        ->rule(function ($record) {
                            return [
                                'digits:10',
                                Rule::unique('student', 'nisn')->ignore($record?->id),
                            ];
                        }),

                    TextInput::make('nipd')
                        ->label('NIPD')
                        ->placeholder('Contoh: 3046/974.016')
                        ->unique(ignoreRecord: true)
                        ->maxLength(20)
                        ->nullable(),

                    TextInput::make('name')
                        ->label('Nama')
                        ->placeholder('Masukkan nama')
                        ->required(),

                    DatePicker::make('date_of_birth')
                        ->label('Tanggal Lahir')
                        ->required(),

                    TextInput::make('place_of_birth')
                        ->label('Tempat Lahir')
                        ->placeholder('Masukkan tempat lahir')
                        ->required(),

                    Select::make('classroom_id')
                        ->label('Kelas')
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Pilih kelas')
                        ->required(),

                    Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Male' => 'Laki-laki',
                            'Female' => 'Perempuan',
                        ])
                        ->placeholder('Pilih jenis kelamin')
                        ->required(),

                    TextInput::make('address')
                        ->label('Alamat')
                        ->placeholder('Masukkan alamat'),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->placeholder('Masukkan email')
                        ->unique(ignoreRecord: true),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->placeholder('Kosongkan untuk pakai NISN sebagai password')
                        ->helperText('Jika dikosongkan, password otomatis menggunakan NISN siswa')
                        ->dehydrateStateUsing(fn (?string $state, $record): string =>
                            filled($state) ? bcrypt($state) : bcrypt($record?->nisn ?? $state)
                        )
                        ->dehydrated(fn (?string $state): bool => true)
                        ->columnSpanFull(),

                    FileUpload::make('avatar_url')
                        ->label('Avatar')
                        ->image()
                        ->imageEditor()
                        ->imageCropAspectRatio(fn () => '1:1')
                        ->directory('student')
                        ->columnSpanFull()
                        ->nullable(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('')
                    ->circular()
                    ->height(50)
                    ->width(50)
                    ->getStateUsing(fn ($record) => $record->avatar_url ?: null)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . 'background=random'),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('nipd')
                    ->label('NIPD')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->badge()
                    ->colors(['primary'])
                    ->sortable(),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}