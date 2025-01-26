<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MataPelajaranController;
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
    Route::get('/auth', [AuthController::class, 'indexlogin'])->name('login');
    Route::post('/auth', [AuthController::class, 'login']);
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

    Route::get('/administrator/jurusan', [JurusanController::class, 'index'])->name('administrator.jurusan');
    Route::get('/administrator/jurusan/add', [JurusanController::class, 'add'])->name('administrator.jurusan.add');
    Route::post('/administrator/jurusan/add/store', [JurusanController::class, 'store'])->name('administrator.jurusan.add.post');
    Route::get('/administrator/jurusan/edit/{jurusan}', [JurusanController::class, 'edit'])->name('administrator.jurusan.edit');
    Route::put('/administrator/jurusan/edit/item/{jurusan}', [JurusanController::class, 'update'])->name('administrator.jurusan.update');
    Route::delete('/administrator/jurusan/delete/{jurusan}', [JurusanController::class, 'delete'])->name('administrator.jurusan.delete');
    Route::post('/administrator/jurusan/bulkdelete', [JurusanController::class, 'bulkdelete'])->name('administrator.jurusan.bulkdelete');

    Route::get('/administrator/mapel', [MataPelajaranController::class, 'index'])->name('administrator.mapel');
    Route::get('/administrator/mapel/add', [MataPelajaranController::class, 'add'])->name('administrator.mapel.add');
    Route::post('/administrator/mapel/add/store', [MataPelajaranController::class, 'store'])->name('administrator.mapel.post');
    Route::get('/administrator/mapel/edit/{id}', [MataPelajaranController::class, 'edit'])->name('administrator.mapel.edit');
    Route::put('/administrator/mapel/edit/item/{id}', [MataPelajaranController::class, 'update'])->name('administrator.mapel.update');
    Route::delete('/administrator/mapel/delete/{id}', [MataPelajaranController::class, 'delete'])->name('administrator.mapel.delete');
    Route::post('/administrator/mapel/bulkdelete', [MataPelajaranController::class, 'bulkdelete'])->name('administrator.mapel.bulkdelete');
});

Route::middleware(['auth', 'roleaccess:guru'])->group(function () {
    Route::redirect('/home', '/guru');
    Route::get('/guru', [GuruController::class, 'index'])->name('guru');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
