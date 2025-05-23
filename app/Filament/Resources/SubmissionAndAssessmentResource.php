<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Models\SubmissionAndAssessment;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\SubmissionAndAssessmentResource\Pages;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\Action as TablesAction;
use Filament\Tables\Actions\ActionGroup;

class SubmissionAndAssessmentResource extends Resource
{
    protected static ?string $model = SubmissionAndAssessment::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Penilaian Tugas Siswa';
    protected static ?string $pluralModelLabel = 'Penilaian Tugas Siswa';
    protected static ?string $modelLabel = 'Penilaian Tugas Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('task_id')
                            ->label('Tugas')
                            ->relationship('task', 'title')
                            ->searchable()
                            ->required(),

                        Select::make('student_id')
                            ->label('Siswa')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->required(),

                        FileUpload::make('file_path')
                            ->label('Bukti Tugas')
                            ->directory('pengumpulan_tugas')
                            ->preserveFilenames()
                            ->previewable()
                            ->downloadable()
                            ->maxSize(5120),

                        TextInput::make('assignment')
                            ->label('Nilai')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.title')->label('Tugas'),
                TextColumn::make('student.name')->label('Siswa'),
                TextColumn::make('assignment')->label('Nilai')->wrap(),
                TextColumn::make('created_at')->label('Dikirim')->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('task_id')
                    ->label('Tugas')
                    ->relationship('task', 'title'),
                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),     
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissionAndAssessments::route('/'),
            'edit' => Pages\EditSubmissionAndAssessment::route('/{record}/edit'),
        ];
    }
}
