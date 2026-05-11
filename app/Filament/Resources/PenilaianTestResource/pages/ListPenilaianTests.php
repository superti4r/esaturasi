<?php

namespace App\Filament\Resources\PenilaianTestResource\Pages;

use App\Filament\Resources\PenilaianTestResource;
use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use App\Models\JawabanPretest;
use App\Models\JawabanPosttest;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\Schedule;
use App\Models\SoalPretest;
use App\Models\SoalPosttest;
use App\Models\SubmissionAndAssessment;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ListPenilaianTests extends Page
{
    protected static string $resource = PenilaianTestResource::class;
    protected ?string $heading = 'Penilaian';
    protected static ?string $title = 'Penilaian';

    protected static string $view     = 'filament.resources.penilaian-test-resource.pages.list-penilaian-tests';

    public string $level = 'mapel';

    public ?int   $selectedScheduleId = null;
    public string $selectedMapelLabel = '';

    public string $activeTab = 'pretest';

    public ?int   $selectedSlugId    = null;
    public string $selectedSlugLabel = '';

    public ?int   $selectedId    = null;
    public string $selectedLabel = '';

    public ?int   $selectedStudentId  = null;
    public string $selectedSiswaLabel = '';

    public ?int  $editNilai  = null;
    public bool  $nilaiSaved = false;

    public function selectMapel(int $scheduleId, string $label): void
    {
        $this->selectedScheduleId = $scheduleId;
        $this->selectedMapelLabel = $label;
        $this->activeTab          = 'pretest';
        $this->level              = 'tab';
        $this->resetSlug();
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->level     = 'tab';
        $this->resetSlug();
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function selectSlug(int $slugId, string $label): void
    {
        $this->selectedSlugId    = $slugId;
        $this->selectedSlugLabel = $label;
        $this->level             = 'slug';
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function selectItem(int $id, string $label): void
    {
        $this->selectedId    = $id;
        $this->selectedLabel = $label;
        $this->level         = 'detail';
        $this->resetSiswa();
    }

    public function selectSiswa(int $studentId, string $label): void
    {
        $this->selectedStudentId  = $studentId;
        $this->selectedSiswaLabel = $label;
        $this->level              = 'siswa';
        $this->editNilai          = null;
        $this->nilaiSaved         = false;
    }

    public function backToMapel(): void
    {
        $this->level              = 'mapel';
        $this->selectedScheduleId = null;
        $this->selectedMapelLabel = '';
        $this->resetSlug();
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function backToTab(): void
    {
        $this->level = 'tab';
        $this->resetSlug();
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function backToSlug(): void
    {
        $this->level = 'slug';
        $this->resetDetail();
        $this->resetSiswa();
    }

    public function backToDetail(): void
    {
        $this->level = 'detail';
        $this->resetSiswa();
    }

    private function resetSlug(): void
    {
        $this->selectedSlugId    = null;
        $this->selectedSlugLabel = '';
    }

    private function resetDetail(): void
    {
        $this->selectedId    = null;
        $this->selectedLabel = '';
    }

    private function resetSiswa(): void
    {
        $this->selectedStudentId  = null;
        $this->selectedSiswaLabel = '';
        $this->editNilai          = null;
        $this->nilaiSaved         = false;
    }

    public function saveNilai(): void
    {
        $this->validate([
            'editNilai' => 'required|numeric|min:0|max:100',
        ]);

        SubmissionAndAssessment::where('task_id', $this->selectedId)
            ->where('student_id', $this->selectedStudentId)
            ->update([
                'assignment' => $this->editNilai,
                'status'     => 'graded',
            ]);

        $this->nilaiSaved = true;

        Notification::make()
            ->title('Nilai berhasil disimpan!')
            ->success()
            ->send();
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('cetak_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->visible(fn () => $this->level === 'detail')
                ->action(function () {
                    $url = match ($this->activeTab) {
                        'tugas'    => route('print.assessment'),
                        'posttest' => route('pdf.rekap-posttest', ['posttest_id' => $this->selectedId]),
                        default    => route('pdf.rekap-test', ['pretest_id' => $this->selectedId]),
                    };
                    $this->redirect($url, navigate: false);
                }),
        ];
    }

    public function getMapelData(): \Illuminate\Support\Collection
    {
        return Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', Auth::id())
            ->get()
            ->groupBy(fn ($s) => $s->subject_id . '-' . $s->classroom_id)
            ->map(fn ($group) => [
                'schedule_id' => $group->first()->id,
                'label'       => ($group->first()->subject?->name ?? '-') . ' - ' . ($group->first()->classroom?->name ?? '-'),
                'mapel'       => $group->first()->subject?->name ?? '-',
                'kelas'       => $group->first()->classroom?->name ?? '-',
            ])
            ->values();
    }

    public function getSlugData(): \Illuminate\Support\Collection
    {
        if (!$this->selectedScheduleId) return collect();

        $schedule = Schedule::find($this->selectedScheduleId);
        if (!$schedule) return collect();

        return $schedule->slugs()
            ->with(['pretests', 'posttests', 'task'])
            ->get()
            ->filter(function ($slug) {
                return match ($this->activeTab) {
                    'tugas'    => $slug->task !== null,
                    'posttest' => $slug->posttests->isNotEmpty(),
                    default    => $slug->pretests->isNotEmpty(),
                };
            })
            ->map(fn ($slug) => [
                'id'    => $slug->id,
                'judul' => $slug->title,
            ])
            ->values();
    }

    public function getListData(): \Illuminate\Support\Collection
    {
        if (!$this->selectedSlugId) return collect();

        return match ($this->activeTab) {
            'tugas' => Task::withCount('submissions')
                ->where('slug_id', $this->selectedSlugId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($t) => [
                    'id'     => $t->id,
                    'judul'  => $t->title,
                    'jumlah' => $t->submissions_count,
                ]),

            'posttest' => Posttest::withCount('hasil')
                ->where('slug_id', $this->selectedSlugId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($p) => [
                    'id'     => $p->id,
                    'judul'  => $p->judul,
                    'jumlah' => $p->hasil_count,
                ]),

            default => Pretest::withCount('hasil')
                ->where('slug_id', $this->selectedSlugId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($p) => [
                    'id'     => $p->id,
                    'judul'  => $p->judul,
                    'jumlah' => $p->hasil_count,
                ]),
        };
    }

    public function getDetailData(): \Illuminate\Support\Collection
    {
        if (!$this->selectedId) return collect();

        return match ($this->activeTab) {
            'tugas' => SubmissionAndAssessment::with(['student.classroom'])
                ->where('task_id', $this->selectedId)
                ->orderBy('assignment', 'desc')
                ->get()
                ->map(fn ($s) => [
                    'student_id' => $s->student_id,
                    'siswa'      => $s->student?->name ?? '-',
                    'kelas'      => $s->student?->classroom?->name ?? '-',
                    'nilai'      => $s->assignment,
                    'status'     => $s->status,
                ]),

            'posttest' => HasilPosttest::with(['student.classroom'])
                ->where('posttest_id', $this->selectedId)
                ->orderBy('nilai', 'desc')
                ->get()
                ->map(fn ($h) => [
                    'student_id' => $h->student_id,
                    'siswa'      => $h->student?->name ?? '-',
                    'kelas'      => $h->student?->classroom?->name ?? '-',
                    'nilai'      => $h->nilai,
                    'status'     => $h->lulus,
                ]),

            default => HasilPretest::with(['student.classroom'])
                ->where('pretest_id', $this->selectedId)
                ->orderBy('nilai', 'desc')
                ->get()
                ->map(fn ($h) => [
                    'student_id' => $h->student_id,
                    'siswa'      => $h->student?->name ?? '-',
                    'kelas'      => $h->student?->classroom?->name ?? '-',
                    'nilai'      => $h->nilai,
                    'status'     => $h->lulus,
                ]),
        };
    }

    public function getSiswaData(): array
    {
        if (!$this->selectedStudentId || !$this->selectedId) return [];

        return match ($this->activeTab) {
            'tugas'    => $this->getSiswaTugasData(),
            'posttest' => $this->getSiswaTestData('posttest'),
            default    => $this->getSiswaTestData('pretest'),
        };
    }

    private function getSiswaTugasData(): array
    {
        $submission = SubmissionAndAssessment::with(['task', 'student'])
            ->where('task_id', $this->selectedId)
            ->where('student_id', $this->selectedStudentId)
            ->first();

        if (!$submission) return ['type' => 'tugas', 'data' => null];

        if ($this->editNilai === null) {
            $this->editNilai = $submission->assignment;
        }

        $task        = $submission->task;
        $deadline    = $task?->deadline ?? null;
        $submittedAt = $submission->submitted_at ?? $submission->created_at;
        $isTelat     = $deadline && $submittedAt && \Carbon\Carbon::parse($submittedAt)->gt(\Carbon\Carbon::parse($deadline));

        return [
            'type'         => 'tugas',
            'nilai'        => $submission->assignment,
            'status'       => $submission->status,
            'submitted_at' => $submittedAt ? \Carbon\Carbon::parse($submittedAt)->translatedFormat('d M Y, H:i') : null,
            'deadline'     => $deadline    ? \Carbon\Carbon::parse($deadline)->translatedFormat('d M Y, H:i')    : null,
            'is_telat'     => $isTelat,
            'file_path'    => $submission->file_path,
            'file_url'     => $submission->file_path
                ? \Illuminate\Support\Facades\Storage::url($submission->file_path)
                : null,
            'file_ext'  => $submission->file_path
                ? strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION))
                : null,
            'file_name' => $submission->file_path
                ? basename($submission->file_path)
                : null,
        ];
    }

    private function getSiswaTestData(string $type): array
    {
        if ($type === 'pretest') {
            $soalList    = SoalPretest::where('pretest_id', $this->selectedId)->get();
            $jawabanList = JawabanPretest::where('pretest_id', $this->selectedId)
                ->where('student_id', $this->selectedStudentId)
                ->get()
                ->keyBy('soal_pretest_id');
            $hasil = HasilPretest::where('pretest_id', $this->selectedId)
                ->where('student_id', $this->selectedStudentId)
                ->first();
        } else {
            $soalList    = SoalPosttest::where('posttest_id', $this->selectedId)->get();
            $jawabanList = JawabanPosttest::where('posttest_id', $this->selectedId)
                ->where('student_id', $this->selectedStudentId)
                ->get()
                ->keyBy('soal_posttest_id');
            $hasil = HasilPosttest::where('posttest_id', $this->selectedId)
                ->where('student_id', $this->selectedStudentId)
                ->first();
        }

        $soalData = $soalList->map(function ($soal) use ($jawabanList, $type) {
            $jawaban = $jawabanList->get($soal->id);
            return [
                'no'            => $soal->id,
                'soal'          => $soal->soal,
                'opsi_a'        => $soal->opsi_a,
                'opsi_b'        => $soal->opsi_b,
                'opsi_c'        => $soal->opsi_c,
                'opsi_d'        => $soal->opsi_d,
                'kunci'         => $soal->jawaban,
                'poin'          => $soal->poin,
                'jawaban_siswa' => $jawaban?->jawaban_siswa ?? null,
                'is_benar'      => $jawaban?->is_benar ?? null,
            ];
        });

        return [
            'type'  => $type,
            'nilai' => $hasil?->nilai ?? 0,
            'lulus' => $hasil?->lulus ?? false,
            'soal'  => $soalData,
        ];
    }
}
