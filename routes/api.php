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
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Validator;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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


