<?php

use App\Http\Controllers\API\DataMataPelajaran;
use App\Http\Controllers\API\SiswaAuthentication;
use App\Http\Controllers\API\DataPengumuman;
use App\Http\Controllers\API\DataJurusan;
Use App\Http\Controllers\API\DataJadwal;
use App\Http\Controllers\API\DataKelas;
use App\Http\Controllers\API\TugasController;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\PengumpulanTugasController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\SlugController;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/api/user', function (Request $request) {
    return $request->user();
});

Route::post('/refresh-token', [AuthController::class, 'refreshToken'])
    ->middleware('throttle:refresh-token');


Route::middleware('auth:sanctum')->get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::prefix('siswa')->group(function () {
    Route::post('/login', [SiswaAuthentication::class, 'login']);
    Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [SiswaAuthentication::class, 'logout']);
    Route::get('/profile', [SiswaAuthentication::class, 'getProfile']);
    });
});

Route::get('/pengumuman', [DataPengumuman::class, 'getPengumuman']);
Route::get('mata-pelajaran/{kelasId}', [DataMataPelajaran::class, 'getMataPelajaran']);
Route::get('/get-kelas/{id}', [DataKelas::class, 'getKelas']);
Route::get('/get-jurusan-by-kelas/{id_kelas}', [DataJurusan::class, 'getJurusanByKelas']);
Route::get('/jadwal/kelas/{id}', [DataJadwal::class, 'getJadwalByKelas']);
Route::middleware('auth:api')->get('/jadwal/{idKelas}', [DataJadwal::class, 'getJadwalByKelas']);

Route::get('/tugas', [TugasController::class, 'getTugas']);

Route::middleware('auth:api')->post('/pengumpulan-tugas', [PengumpulanTugasController::class, 'store']);

Route::middleware('auth.siswa')->delete('/delete-profile-photo', [SiswaAuthentication::class, 'deleteFotoProfil']);
Route::get('/materi/mapel/{id}', [MateriController::class, 'getBabByMapelId']);
Route::get('/slugs/jadwal/{id}', [SlugController::class, 'getByJadwal']);
Route::get('/materials/slug/{id}', [MateriController::class, 'getBySlug']);


