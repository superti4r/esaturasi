<?php

namespace App\Filament\Resources\PenilaianTestResource\Pages;

use App\Filament\Resources\PenilaianTestResource;
use App\Models\Classroom;
use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListPenilaianTests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = PenilaianTestResource::class;
    protected static string $view     = 'filament.resources.penilaian-test-resource.pages.list-penilaian-tests';

    public string $activeTab = 'pretest';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetTable();
    }

 public function getHeaderActions(): array
{
    return [
        Action::make('cetak_pdf')
            ->label(fn() => 'Cetak PDF ' . ($this->activeTab === 'pretest' ? 'Pretest' : 'Posttest'))
            ->icon('heroicon-o-printer')
            ->color('primary')
            ->action(function () {
                $classroomId = $this->tableFilters['classroom']['value'] ?? null;
                $lulus       = $this->tableFilters['lulus']['value'] ?? null;
                $pretestId   = $this->tableFilters['pretest_id']['value'] ?? null;
                $posttestId  = $this->tableFilters['posttest_id']['value'] ?? null;

                $params = array_filter([
                    'classroom_id' => $classroomId,
                    'lulus'        => $lulus,
                    'pretest_id'   => $pretestId,
                    'posttest_id'  => $posttestId,
                ], fn($v) => $v !== null);

                if ($this->activeTab === 'pretest') {
                    $url = route('pdf.rekap-test', $params);
                } else {
                    $url = route('pdf.rekap-posttest', $params);
                }

                $this->redirect($url, navigate: false);
            }),
    ];
}

    public function table(Table $table): Table
    {
        $isPretest = $this->activeTab === 'pretest';

        return $table
            ->query(
                $isPretest
                    ? HasilPretest::query()->with(['student.classroom', 'pretest'])
                    : HasilPosttest::query()->with(['student.classroom', 'posttest'])
            )
            ->columns([
                TextColumn::make($isPretest ? 'pretest.judul' : 'posttest.judul')
                    ->label('Judul Test')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.classroom.name')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable(),

                TextColumn::make('lulus')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Lulus' : 'Tidak Lulus')
                    ->color(fn($state) => $state ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->label('Dikerjakan')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('classroom')
                    ->label('Kelas')
                    ->options(Classroom::pluck('name', 'id'))
                    ->searchable()
                    ->query(
                        fn(Builder $query, array $data) =>
                            $query->when(
                                $data['value'],
                                fn($q, $v) => $q->whereHas(
                                    'student',
                                    fn($q) => $q->where('classroom_id', $v)
                                )
                            )
                    ),

                SelectFilter::make('lulus')
                    ->label('Status')
                    ->options(['1' => 'Lulus', '0' => 'Tidak Lulus']),

                SelectFilter::make($isPretest ? 'pretest_id' : 'posttest_id')
                    ->label('Judul Test')
                    ->relationship($isPretest ? 'pretest' : 'posttest', 'judul')
                    ->searchable()
                    ->preload(),
            ])
            
            ->defaultSort('created_at', 'desc');
    }
}