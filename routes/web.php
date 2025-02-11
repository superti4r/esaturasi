<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BabController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/login', [AuthController::class, 'indexlogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'indexregister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify/{verify_token}', [AuthController::class, 'verify']);
});

Route::middleware(['auth', 'roleaccess:administrator'])->group(function () {
    Route::redirect('/home', '/administrator');
    Route::get('/administrator', [AdminController::class, 'index'])->name('administrator');

    Route::get('/administrator/user', [DataUserController::class, 'index'])->name('administrator.user');
    Route::get('/administrator/user/add', [DataUserController::class, 'add'])->name('administrator.user.add');
    Route::post('/administrator/user/add', [DataUserController::class, 'store'])->name('administrator.user.add.post');
    Route::get('/administrator/user/edit/{id}', [DataUserController::class, 'edit'])->name('administrator.user.edit');
    Route::put('/administrator/user/edit/{id}', [DataUserController::class, 'update'])->name('administrator.user.edit.post');
    Route::get('/administrator/user/view/{id}', [DataUserController::class, 'view'])->name('administrator.user.view');
    Route::delete('/administrator/user/delete/{id}', [DataUserController::class, 'delete'])->name('administrator.user.delete');
    Route::post('/administrator/user/delete/selected', [DataUserController::class, 'bulkdelete'])->name('administrator.user.bulkdelete');

    Route::get('/administrator/siswa', [SiswaController::class, 'index'])->name('administrator.siswa');
    Route::get('/administrator/siswa/add', [SiswaController::class, 'add'])->name('administrator.siswa.add');
    Route::post('/administrator/siswa/add', [SiswaController::class, 'store'])->name('administrator.siswa.store');
    Route::get('/administrator/siswa/edit/{id}', [SiswaController::class, 'edit'])->name('administrator.siswa.edit');
    Route::put('/administrator/siswa/edit/{id}', [SiswaController::class, 'update'])->name('administrator.siswa.update');
    Route::get('/administrator/siswa/view/{id}', [SiswaController::class, 'view'])->name('administrator.siswa.view');
    Route::delete('/administrator/siswa/delete/{id}', [SiswaController::class, 'delete'])->name('administrator.siswa.delete');
    Route::post('/administrator/siswa/delete/selected', [SiswaController::class, 'bulkdelete'])->name('administrator.siswa.bulkdelete');

    Route::get('/administrator/jurusan', [JurusanController::class, 'index'])->name('administrator.jurusan');
    Route::get('/administrator/jurusan/add', [JurusanController::class, 'add'])->name('administrator.jurusan.add');
    Route::post('/administrator/jurusan/add', [JurusanController::class, 'store'])->name('administrator.jurusan.add.post');
    Route::get('/administrator/jurusan/edit/{id}', [JurusanController::class, 'edit'])->name('administrator.jurusan.edit');
    Route::put('/administrator/jurusan/edit/{id}', [JurusanController::class, 'update'])->name('administrator.jurusan.update');
    Route::delete('/administrator/jurusan/delete/{id}', [JurusanController::class, 'delete'])->name('administrator.jurusan.delete');
    Route::post('/administrator/jurusan/delete/selected', [JurusanController::class, 'bulkdelete'])->name('administrator.jurusan.bulkdelete');

    Route::get('/administrator/mapel', [MataPelajaranController::class, 'index'])->name('administrator.mapel');
    Route::get('/administrator/mapel/add', [MataPelajaranController::class, 'add'])->name('administrator.mapel.add');
    Route::post('/administrator/mapel/add', [MataPelajaranController::class, 'store'])->name('administrator.mapel.post');
    Route::get('/administrator/mapel/edit/{id}', [MataPelajaranController::class, 'edit'])->name('administrator.mapel.edit');
    Route::put('/administrator/mapel/edit/{id}', [MataPelajaranController::class, 'update'])->name('administrator.mapel.update');
    Route::delete('/administrator/mapel/delete/{id}', [MataPelajaranController::class, 'delete'])->name('administrator.mapel.delete');
    Route::post('/administrator/mapel/delete/selected', [MataPelajaranController::class, 'bulkdelete'])->name('administrator.mapel.bulkdelete');

    Route::get('/administrator/kelas', [KelasController::class, 'index'])->name('administrator.kelas');
    Route::get('/administrator/kelas/add', [KelasController::class, 'add'])->name('administrator.kelas.add');
    Route::post('/administrator/kelas/add', [KelasController::class, 'store'])->name('administrator.kelas.post');
    Route::get('/administrator/kelas/edit/{id}', [KelasController::class, 'edit'])->name('administrator.kelas.edit');
    Route::put('/administrator/kelas/edit/{id}', [KelasController::class, 'update'])->name('administrator.kelas.update');
    Route::delete('/administrator/kelas/delete/{id}', [KelasController::class, 'delete'])->name('administrator.kelas.delete');
    Route::post('/administrator/kelas/delete/selected', [KelasController::class, 'bulkdelete'])->name('administrator.kelas.bulkdelete');

    Route::get('administrator/bab', [BabController::class, 'index'])->name('administrator.bab');
    Route::get('administrator/bab/add', [BabController::class, 'add'])->name('administrator.bab.add');
    Route::post('administrator/bab/add', [BabController::class, 'store'])->name('administrator.bab.post');
    Route::delete('administrator/bab/delete', [BabController::class, 'delete'])->name('administrator.bab.delete');

    Route::get('/administrator/pengumuman', [PengumumanController::class, 'index'])->name('administrator.pengumuman');
    Route::get('/administrator/pengumuman/add', [PengumumanController::class, 'add'])->name('administrator.pengumuman.add');
    Route::post('/administrator/pengumuman/add', [PengumumanController::class, 'store'])->name('administrator.pengumuman.post');
    Route::get('/administrator/pengumuman/edit/{id}', [PengumumanController::class, 'edit'])->name('administrator.pengumuman.edit');
    Route::put('/administrator/pengumuman/edit/{id}', [PengumumanController::class, 'update'])->name('administrator.pengumuman.update');
    Route::get('/administrator/pengumuman/view/{id}', [PengumumanController::class, 'view'])->name('administrator.pengumuman.view');
    Route::delete('/administrator/pengumuman/delete/{id}', [PengumumanController::class, 'delete'])->name('administrator.pengumuman.delete');
    Route::post('/administrator/pengumuman/delete/selected', [PengumumanController::class, 'bulkdelete'])->name('administrator.pengumuman.bulkdelete');
});

Route::middleware(['auth', 'roleaccess:guru'])->group(function () {
    Route::redirect('/home', '/guru');
    Route::get('/guru', [GuruController::class, 'index'])->name('guru');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
