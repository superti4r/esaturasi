<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classroom;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentPrinting extends Controller
{
    public function print(Request $request)
    {
        $classroomId = $request->query('classroom_id');

        if ($classroomId) {
            // Cetak 1 kelas saja
            $classroom = Classroom::with(['students' => function ($q) {
                $q->orderBy('name');
            }])->findOrFail($classroomId);

            $classrooms = collect([$classroom]);
            $fileName = 'data-siswa_' . str($classroom->name)->slug() . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        } else {
            // Cetak semua kelas
            $classrooms = Classroom::with(['students' => function ($q) {
                $q->orderBy('name');
            }])
                ->orderBy('name')
                ->get()
                ->filter(fn($c) => $c->students->isNotEmpty());

            $fileName = 'data-siswa_semua-kelas_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        }

        $pdf = Pdf::loadView('print.students', compact('classrooms'))
            ->setPaper('A4', 'portrait');

        return $pdf->download($fileName);
    }
}