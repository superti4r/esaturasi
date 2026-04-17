<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function getScheduleByClassroom($id)
{
    $schedules = Schedule::where('classroom_id', $id)
                  ->with(['subject', 'classroom', 'teacher'])
                  ->get();

    // ← Tambah ini untuk inject slug_id
    $schedules->transform(function ($schedule) {
        $slug = \App\Models\Slugs::where('schedule_id', $schedule->id)->first();
        $schedule->slug_id = $slug ? $slug->id : null;
        return $schedule;
    });

    return response()->json($schedules);
}
}
