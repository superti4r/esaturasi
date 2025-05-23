<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;

class SchedulePrinting extends Controller
{
    public function print()
    {
        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])->get();
        $fileName = 'data-jadwal_esaturasi-' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $pdf = Pdf::loadView('print.schedules', compact('schedules'))->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }
}
