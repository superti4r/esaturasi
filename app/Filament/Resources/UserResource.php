<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('Administrator') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->placeholder('Masukkan NIP anda (Maksimal 16 digit)')
                                    ->required()
                                    ->minLength(16)
                                    ->maxLength(16)
                                    ->numeric()
                                    ->rule(function ($record) {
                                        return [
                                            'digits:16',
                                            Rule::unique('users', 'nip')->ignore($record?->id),
                                        ];
                                    }),

                                TextInput::make('kode_guru')
                                    ->label('Kode Guru')
                                    ->placeholder('Contoh: GR001')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10)
                                    ->nullable(),

                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->placeholder('Masukkan nama anda')
                                    ->maxLength(255),

                                TextInput::make('gol')
                                    ->label('Golongan')
                                    ->placeholder('Contoh: Pembina, IV/a')
                                    ->nullable(),

                                DatePicker::make('date_of_birth')
                                    ->label('Tanggal Lahir')
                                    ->nullable(),

                                TextInput::make('place_of_birth')
                                    ->label('Tempat Lahir')
                                    ->placeholder('Masukkan tempat lahir anda')
                                    ->nullable()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->placeholder('Masukkan alamat email anda')
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('address')
                            ->label('Alamat')
                            ->nullable()
                            ->placeholder('Masukkan alamat anda saat ini')
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->placeholder('Masukkan password anda (gunakan password yang kuat)')
                            ->dehydrateStateUsing(fn(string $state): string => bcrypt($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->columnSpanFull(),

                        Select::make('roles')
                            ->label('Peran')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->columnSpanFull(),
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
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=random'),

                TextColumn::make('kode_guru')
                    ->label('Kode Guru')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('gol')
                    ->label('Golongan')
                    ->searchable(),

                TextColumn::make('roles')
                    ->label('Role')
                    ->getStateUsing(fn($record) => $record->roles->pluck('name')->implode(', '))
                    ->badge()
                    ->colors(['primary']),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}