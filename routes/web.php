<?php

use App\Http\Controllers\AssessmentPrinting;
use App\Http\Controllers\SchedulePrinting;
use App\Http\Controllers\StudentPrinting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('m/students/print', [StudentPrinting::class, 'print'])->name('print.students');
Route::get('m/schedules/print', [SchedulePrinting::class, 'print'])->name('print.schedules');
Route::get('m/submission-and-assessments/print', [AssessmentPrinting::class, 'print'])->name('print.assessment');
