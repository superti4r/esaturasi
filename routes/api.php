<?php

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\SiswaAuthController;
use App\Http\Controllers\API\PengumumanController;
use App\Http\Controllers\API\JurusanController;
use App\Http\Controllers\API\KelasController;
Use App\Http\Controllers\API\DataJadwal;
use App\Http\Controllers\API\MataPelajaranController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('siswa')->group(function () {
    Route::post('/login', [SiswaAuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [SiswaAuthController::class, 'logout']);
        Route::get('/profile', [SiswaAuthController::class, 'getProfile']);
    });
});



Route::get('/pengumuman', [PengumumanController::class, 'getPengumuman']);

Route::get('mata-pelajaran/{kelasId}', [MataPelajaranController::class, 'getMataPelajaran']);

Route::get('/get-kelas/{id}', [KelasController::class, 'getKelas']);
Route::get('/get-jurusan/{id}', [JurusanController::class, 'getJurusan']);
Route::middleware('auth:api')->get('/jadwal/{idKelas}', [DataJadwal::class, 'getJadwalByKelas']);
