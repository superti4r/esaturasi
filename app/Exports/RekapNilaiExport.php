<?php

namespace App\Exports;

use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\SubmissionAndAssessment;
use App\Models\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapNilaiExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        public readonly ?int $classroomId,
        public readonly ?int $scheduleId,
    ) {}

    public function sheets(): array
    {
        $slugIds = Schedule::find($this->scheduleId)
            ?->slugs()
            ->pluck('id')
            ->toArray() ?? [];

        $siswaList = Student::where('classroom_id', $this->classroomId)
            ->orderBy('name')
            ->get();

        return [
            new RekapNilaiSheetExport($siswaList, $slugIds, 'pretest',  'Pre Test'),
            new RekapNilaiSheetExport($siswaList, $slugIds, 'tugas',    'Tugas'),
            new RekapNilaiSheetExport($siswaList, $slugIds, 'posttest', 'Post Test'),
        ];
    }
}