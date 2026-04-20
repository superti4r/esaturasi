<?php

use App\Http\Controllers\AssessmentPrinting;
use App\Http\Controllers\SchedulePrinting;
use App\Http\Controllers\StudentPrinting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfTestController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('m/students/print', [StudentPrinting::class, 'print'])->name('print.students');
Route::get('m/schedules/print', [SchedulePrinting::class, 'print'])->name('print.schedules');
Route::get('m/submission-and-assessments/print', [AssessmentPrinting::class, 'print'])->name('print.assessment');

Route::get('/pdf/rekap-test', [PdfTestController::class, 'cetakSemua'])->name('pdf.rekap-test');
Route::get('/pdf/rekap-posttest', [PdfTestController::class, 'cetakSemuaPosttest'])->name('pdf.rekap-posttest');
Route::get('/pdf/rekap-test/siswa/{studentId}', [PdfTestController::class, 'cetakSiswa'])->name('pdf.rekap-siswa');
Route::get('/pdf/rekap-posttest/siswa/{studentId}', [PdfTestController::class, 'cetakSiswaPosttest'])->name('pdf.rekap-siswa-posttest');