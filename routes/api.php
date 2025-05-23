<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\ClassroomController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\SlugController;
use App\Http\Controllers\API\SubjectMatterController;
use App\Http\Controllers\API\MajorController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\SubmissiontaskController;
use App\Http\Controllers\Api\PasswordController;


Route::get('/tugas', [TaskController::class, 'getTugas']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pengumpulan-tugas', [SubmissiontaskController::class, 'store']);
});

 Route::get('/status-pengumpulan/{taskId}', [SubmissiontaskController::class, 'getSubmissionStatus']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/announcement', [AnnouncementController::class, 'getAnnouncement']);
Route::get('/get-class/{id}', [ClassroomController::class, 'getClass']);
Route::get('/schedule/classroom/{id}', [ScheduleController::class, 'getScheduleByClassroom']);
Route::get('/slugs/schedule/{id}', [SlugController::class, 'getByJadwal']);
Route::get('/materials/slug/{id}', [SubjectMatterController::class, 'getBySlug']);
Route::get('/materi/mapel/{id}', [SubjectMatterController::class, 'getBabByMapelId']);
Route::get('/get-major-by-classroom/{id_kelas}', [MajorController::class, 'getJurusanByKelas']);
Route::middleware('auth:sanctum')->delete('/delete-profile-photo', [StudentController::class, 'deleteProfilePhoto']);
Route::middleware('auth:sanctum')->post('/update-profile-photo', [StudentController::class, 'updateProfilePhoto']);
Route::post('/change-password', [PasswordController::class, 'changePassword']);
Route::get('/tasks/{classroomId}/pending/{studentId}', [TaskController::class, 'getPendingTasks']);


