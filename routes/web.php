<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\GuruController;
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
    Route::post('/administrator/user/delete/{id}', [DataUserController::class, 'delete'])->name('administrator.user.delete');
});

Route::middleware(['auth', 'roleaccess:guru'])->group(function () {
    Route::redirect('/home', '/guru');
    Route::get('/guru', [GuruController::class, 'index'])->name('dashboard.guru');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
