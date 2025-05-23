<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentPrinting extends Controller
{
    public function print()
    {
        $students = Student::with('classroom')->get();
        $fileName = 'data-siswa_esaturasi-' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('print.students', compact('students'))->setPaper('A4', 'portrait');

        return $pdf->download($fileName);
    }
}
