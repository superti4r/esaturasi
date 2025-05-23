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
use Illuminate\Support\Collection;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Filament\Forms\Components\Select as FormSelect;
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
                        ->placeholder('Masukkan alamat')
                        ->required(),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->placeholder('Masukkan email')
                        ->unique(ignoreRecord: true)
                        ->required(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn(string $context): bool => $context === 'create')
                        ->placeholder('Masukkan password anda (gunakan password yang kuat)')
                        ->dehydrateStateUsing(fn(string $state): string => bcrypt($state))
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->columnSpanFull(),

                    FileUpload::make('avatar_url')
                        ->label('Avatar')
                        ->image()
                        ->imageEditor()
                        ->imageCropAspectRatio(fn() => '1:1')
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

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->badge()
                    ->colors(['primary'])
                    ->sortable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('promote')
                        ->label('Naik Kelas')
                        ->form([
                            FormSelect::make('new_classroom_id')
                                ->label('Pilih Kelas Baru')
                                ->options(Classroom::all()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $student) {
                                $student->update([
                                    'classroom_id' => $data['new_classroom_id'],
                                ]);
                            }

                            $classroomName = Classroom::find($data['new_classroom_id'])?->name ?? 'kelas baru';

                            Notification::make()
                                ->title('Sukses')
                                ->body("Siswa berhasil dipindahkan ke {$classroomName}.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->icon('heroicon-o-arrow-up')
                        ->color('success'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
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
