<?php

use App\Http\Controllers\AssessmentPrinting;
use App\Http\Controllers\StudentPrinting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfTestController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('m/students/print', [StudentPrinting::class, 'print'])->name('print.students');
Route::get('m/submission-and-assessments/print', [AssessmentPrinting::class, 'print'])->name('print.assessment');

Route::get('/pdf/rekap-test', [PdfTestController::class, 'cetakSemua'])->name('pdf.rekap-test');
Route::get('/pdf/rekap-posttest', [PdfTestController::class, 'cetakSemuaPosttest'])->name('pdf.rekap-posttest');
Route::get('/pdf/rekap-test/siswa/{studentId}', [PdfTestController::class, 'cetakSiswa'])->name('pdf.rekap-siswa');
Route::get('/pdf/rekap-posttest/siswa/{studentId}', [PdfTestController::class, 'cetakSiswaPosttest'])->name('pdf.rekap-siswa-posttest');

Route::get('/download-materi/{file}', function ($file) {
    return Storage::download('materi/' . $file);
})->name('download.materi');


Route::get('/download-tugas/{file}', function ($file) {
    return Storage::download('tugas/' . $file);
})->name('download.tugas');