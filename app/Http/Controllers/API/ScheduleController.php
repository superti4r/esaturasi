<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function getScheduleByClassroom($idKelas)
    {
        $schedule = Schedule::with(['classroom', 'teacher', 'subject'])
            ->where('classroom_id', $idKelas)
            ->get();

        Log::info('Fetched jadwal: ', ['schedule' => $schedule]);

        return response()->json($schedule);
    }
}
