<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\HasilPretest;
use App\Models\HasilPosttest;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfTestController extends Controller
{
    public function cetakSemua(Request $request)
    {
        $classroomId = $request->classroom_id;
        $lulus       = $request->lulus;
        $pretestId   = $request->pretest_id;
        $info        = [];

        $pretests = HasilPretest::with([
            'student.classroom',
            'pretest.slug.schedule.subject',
            'pretest.slug.schedule.teacher',
        ])
            ->when($classroomId, fn($q) => $q->whereHas(
                'student', fn($q) => $q->where('classroom_id', $classroomId)
            ))
            ->when($lulus !== null && $lulus !== '', fn($q) => $q->where('lulus', $lulus))
            ->when($pretestId, fn($q) => $q->where('pretest_id', $pretestId))
            ->get();

        if ($classroomId) {
            $info['Kelas'] = Classroom::find($classroomId)?->name ?? '-';
        }
        if ($lulus !== null && $lulus !== '') {
            $info['Status'] = $lulus == '1' ? 'Lulus' : 'Tidak Lulus';
        }
        if ($pretestId) {
            $info['Pretest'] = $pretests->first()?->pretest?->judul ?? '-';
        }

        $grouped = $pretests->groupBy('pretest_id');
        $guru    = $pretests->first()?->pretest?->slug?->schedule?->teacher?->name ?? '-';

        $fileName = 'rekap-pretest_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('pdf.rekap-test', compact('grouped', 'info', 'guru'))
            ->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }

    public function cetakSemuaPosttest(Request $request)
    {
        $classroomId = $request->classroom_id;
        $lulus       = $request->lulus;
        $posttestId  = $request->posttest_id;
        $info        = [];

        $posttests = HasilPosttest::with([
            'student.classroom',
            'posttest.slug.schedule.subject',
            'posttest.slug.schedule.teacher',
        ])
            ->when($classroomId, fn($q) => $q->whereHas(
                'student', fn($q) => $q->where('classroom_id', $classroomId)
            ))
            ->when($lulus !== null && $lulus !== '', fn($q) => $q->where('lulus', $lulus))
            ->when($posttestId, fn($q) => $q->where('posttest_id', $posttestId))
            ->get();

        if ($classroomId) {
            $info['Kelas'] = Classroom::find($classroomId)?->name ?? '-';
        }
        if ($lulus !== null && $lulus !== '') {
            $info['Status'] = $lulus == '1' ? 'Lulus' : 'Tidak Lulus';
        }
        if ($posttestId) {
            $info['Posttest'] = $posttests->first()?->posttest?->judul ?? '-';
        }

        $grouped = $posttests->groupBy('posttest_id');
        $guru    = $posttests->first()?->posttest?->slug?->schedule?->teacher?->name ?? '-';

        $fileName = 'rekap-posttest_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('pdf.rekap-posttest', compact('grouped', 'info', 'guru'))
            ->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }

    public function cetakSiswa(int $studentId)
    {
        $pretests = HasilPretest::with([
            'student.classroom',
            'pretest.slug.schedule.subject',
            'pretest.slug.schedule.teacher',
        ])
            ->where('student_id', $studentId)
            ->get();

        $student = $pretests->first()?->student
            ?? Student::find($studentId);

        $info = [
            'Siswa' => $student?->name ?? '-',
            'Kelas' => $student?->classroom?->name ?? '-',
        ];

        $grouped = $pretests->groupBy('pretest_id');
        $guru    = $pretests->first()?->pretest?->slug?->schedule?->teacher?->name ?? '-';

        $fileName = 'rekap-pretest-' . str($student?->name)->slug() . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('pdf.rekap-test', compact('grouped', 'info', 'guru'))
            ->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }

    public function cetakSiswaPosttest(int $studentId)
    {
        $posttests = HasilPosttest::with([
            'student.classroom',
            'posttest.slug.schedule.subject',
            'posttest.slug.schedule.teacher',
        ])
            ->where('student_id', $studentId)
            ->get();

        $student = $posttests->first()?->student
            ?? Student::find($studentId);

        $info = [
            'Siswa' => $student?->name ?? '-',
            'Kelas' => $student?->classroom?->name ?? '-',
        ];

        $grouped = $posttests->groupBy('posttest_id');
        $guru    = $posttests->first()?->posttest?->slug?->schedule?->teacher?->name ?? '-';

        $fileName = 'rekap-posttest-' . str($student?->name)->slug() . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('pdf.rekap-posttest', compact('grouped', 'info', 'guru'))
            ->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }
}