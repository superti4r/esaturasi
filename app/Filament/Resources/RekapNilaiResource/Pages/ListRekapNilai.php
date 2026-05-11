<?php

namespace App\Filament\Resources\RekapNilaiResource\Pages;

use App\Filament\Resources\RekapNilaiResource;
use App\Models\Classroom;
use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\SubmissionAndAssessment;
use App\Models\Task;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapNilaiExport;

class ListRekapNilai extends Page
{
    protected static string $resource = RekapNilaiResource::class;
    protected static string $view     = 'filament.resources.rekap-nilai-resource.pages.list-rekap-nilai';
    protected ?string $heading        = 'Rekap Nilai';

    // State filter
    public ?int    $selectedClassroomId = null;
    public ?int    $selectedScheduleId  = null;
    public string  $selectedKelasLabel  = '';
    public string  $selectedMapelLabel  = '';
    public string  $activeTab           = 'pretest';

    // Level navigasi: 'kelas' | 'mapel' | 'rekap'
    public string $level = 'kelas';

    public function selectKelas(int $classroomId, string $label): void
    {
        $this->selectedClassroomId = $classroomId;
        $this->selectedKelasLabel  = $label;
        $this->selectedScheduleId  = null;
        $this->selectedMapelLabel  = '';
        $this->activeTab           = 'pretest';
        $this->level               = 'mapel';
    }

    public function selectMapel(int $scheduleId, string $label): void
    {
        $this->selectedScheduleId = $scheduleId;
        $this->selectedMapelLabel = $label;
        $this->activeTab          = 'pretest';
        $this->level              = 'rekap';
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function backToKelas(): void
    {
        $this->level               = 'kelas';
        $this->selectedClassroomId = null;
        $this->selectedKelasLabel  = '';
        $this->selectedScheduleId  = null;
        $this->selectedMapelLabel  = '';
    }

    public function backToMapel(): void
    {
        $this->level              = 'mapel';
        $this->selectedScheduleId = null;
        $this->selectedMapelLabel = '';
        $this->activeTab          = 'pretest';
    }

    // -------------------------------------------------------
    // DATA GETTERS
    // -------------------------------------------------------

    /** Daftar kelas yang diajar guru ini */
    public function getKelasData(): \Illuminate\Support\Collection
    {
        return Schedule::with('classroom')
            ->where('teacher_id', Auth::id())
            ->get()
            ->pluck('classroom')
            ->filter()
            ->unique('id')
            ->values()
            ->map(fn ($c) => [
                'id'    => $c->id,
                'label' => $c->name,
            ]);
    }

    /** Daftar mapel di kelas yang dipilih */
    public function getMapelData(): \Illuminate\Support\Collection
    {
        if (!$this->selectedClassroomId) return collect();

        return Schedule::with('subject')
            ->where('teacher_id', Auth::id())
            ->where('classroom_id', $this->selectedClassroomId)
            ->get()
            ->map(fn ($s) => [
                'schedule_id' => $s->id,
                'label'       => $s->subject?->name ?? '-',
            ]);
    }

    /** Siswa di kelas yang dipilih */
    private function getSiswaList(): \Illuminate\Support\Collection
    {
        return Student::where('classroom_id', $this->selectedClassroomId)
            ->orderBy('name')
            ->get();
    }

    /** Semua slug/bab pada schedule yang dipilih */
    private function getSlugIds(): array
    {
        if (!$this->selectedScheduleId) return [];
        return Schedule::find($this->selectedScheduleId)
            ?->slugs()
            ->pluck('id')
            ->toArray() ?? [];
    }

    /**
     * Data rekap: Collection of siswa dengan nilai per bab (kolom dinamis).
     * Tiap item: ['nisn', 'nama', 'kelas', 'nilai' => [bab1 => val, bab2 => val, ...], 'rata']
     */
   public function getRekapData(): array
    {
        $siswaList = $this->getSiswaList();
        $slugIds   = $this->getSlugIds();

        if ($siswaList->isEmpty() || empty($slugIds)) {
            return ['columns' => [], 'rows' => []];
        }

        $columns = match ($this->activeTab) {
            'tugas' => Task::whereIn('slug_id', $slugIds)
                ->orderBy('created_at')
                ->get()
                ->map(fn ($t) => ['id' => $t->id, 'label' => $t->title, 'slug_id' => $t->slug_id]),

            'posttest' => Posttest::whereIn('slug_id', $slugIds)
                ->orderBy('created_at')
                ->get()
                ->map(fn ($p) => ['id' => $p->id, 'label' => $p->judul, 'slug_id' => $p->slug_id]),

            default => Pretest::whereIn('slug_id', $slugIds)
                ->orderBy('created_at')
                ->get()
                ->map(fn ($p) => ['id' => $p->id, 'label' => $p->judul, 'slug_id' => $p->slug_id]),
        };

        if ($columns->isEmpty()) {
            return ['columns' => [], 'rows' => []];
        }

        $colIds = $columns->pluck('id')->toArray();

        // Ambil nilai mentah, simpan sebagai array biasa [student_id][col_id] => nilai
        $nilaiFlat = [];

        if ($this->activeTab === 'tugas') {
            $records = SubmissionAndAssessment::whereIn('task_id', $colIds)->get();
            foreach ($records as $r) {
                $nilaiFlat[$r->student_id][$r->task_id] = $r->assignment;
            }
        } elseif ($this->activeTab === 'posttest') {
            $records = HasilPosttest::whereIn('posttest_id', $colIds)->get();
            foreach ($records as $r) {
                $nilaiFlat[$r->student_id][$r->posttest_id] = $r->nilai;
            }
        } else {
            $records = HasilPretest::whereIn('pretest_id', $colIds)->get();
            foreach ($records as $r) {
                $nilaiFlat[$r->student_id][$r->pretest_id] = $r->nilai;
            }
        }

        $rows = $siswaList->map(function ($siswa) use ($columns, $nilaiFlat) {
            $nilaiPerBab = [];
            $total = 0;
            $count = 0;

            foreach ($columns as $col) {
                $nilai = $nilaiFlat[$siswa->id][$col['id']] ?? null;
                $nilaiPerBab[$col['id']] = $nilai;
                if ($nilai !== null) {
                    $total += $nilai;
                    $count++;
                }
            }

            return [
                'nisn'  => $siswa->nisn ?? '-',
                'nama'  => $siswa->name,
                'kelas' => $siswa->classroom?->name ?? '-',
                'nilai' => $nilaiPerBab,
                'rata'  => $count > 0 ? round($total / $count) : '-',
            ];
        });

        return [
            'columns' => $columns->values()->toArray(),
            'rows'    => $rows->values()->toArray(),
        ];
    }

    // -------------------------------------------------------
    // EXPORT EXCEL
    // -------------------------------------------------------

    public function exportExcel(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $kelasLabel = $this->selectedKelasLabel;
        $mapelLabel = $this->selectedMapelLabel;
        $filename   = 'Rekap_Nilai_' . str_replace(' ', '_', $kelasLabel) . '_' . str_replace(' ', '_', $mapelLabel) . '.xlsx';

        return Excel::download(
            new RekapNilaiExport(
                classroomId: $this->selectedClassroomId,
                scheduleId:  $this->selectedScheduleId,
            ),
            $filename
        );
    }

    public function exportExcelTab(): \Symfony\Component\HttpFoundation\BinaryFileResponse
{
    $kelasLabel = $this->selectedKelasLabel;
    $mapelLabel = $this->selectedMapelLabel;
    $tabLabel   = match($this->activeTab) {
        'tugas'    => 'Tugas',
        'posttest' => 'Post Test',
        default    => 'Pre Test',
    };

    $filename = 'Rekap_' . $tabLabel . '_' . str_replace(' ', '_', $kelasLabel) . '_' . str_replace(' ', '_', $mapelLabel) . '.xlsx';

    $slugIds   = Schedule::find($this->selectedScheduleId)?->slugs()->pluck('id')->toArray() ?? [];
    $siswaList = \App\Models\Student::where('classroom_id', $this->selectedClassroomId)->orderBy('name')->get();

    return Excel::download(
        new \App\Exports\RekapNilaiSheetExport($siswaList, $slugIds, $this->activeTab, $tabLabel),
        $filename
    );
}

   public function getHeaderActions(): array
{
    return [
        \Filament\Actions\Action::make('export_tab')
            ->label(fn () => 'Export ' . match($this->activeTab) {
                'tugas'    => 'Tugas',
                'posttest' => 'Post Test',
                default    => 'Pre Test',
            })
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->visible(fn () => $this->level === 'rekap')
            ->action(fn () => $this->exportExcelTab()),

        \Filament\Actions\Action::make('export_semua')
            ->label('Export Semua')
            ->icon('heroicon-o-table-cells')
            ->color('success')
            ->visible(fn () => $this->level === 'rekap')
            ->action(fn () => $this->exportExcel()),
    ];
}
}