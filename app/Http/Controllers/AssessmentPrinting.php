<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubmissionAndAssessment;
use Barryvdh\DomPDF\Facade\Pdf;

class AssessmentPrinting extends Controller
{
    public function print(Request $request)
    {
        $query = SubmissionAndAssessment::with(['task', 'student']);

        if ($request->has('tableFilters')) {
            $filters = $request->input('tableFilters');

            if (isset($filters['task_id']['value']) && !empty($filters['task_id']['value'])) {
                $query->where('task_id', $filters['task_id']['value']);
            }

            if (isset($filters['student_id']['value']) && !empty($filters['student_id']['value'])) {
                $query->where('student_id', $filters['student_id']['value']);
            }
        }

        $assessments = $query->get();

        $fileName = 'penilaian_tugas_siswa-' . now()->format('Y-m-d_H-i-s') . '.pdf';

        $pdf = Pdf::loadView('print.assessments', compact('assessments'))
                  ->setPaper('A4', 'potrait');

        return $pdf->download($fileName);
    }
}
