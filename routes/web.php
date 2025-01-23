<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
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

Route::middleware(['guest'])->group(function(){
    Route::get('/', function () {return view('welcome');});
    Route::get('/auth', [AuthController::class, 'indexlogin'])->name('login');
    Route::post('/auth', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'indexregister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify/{verify_token}', [AuthController::class, 'verify']);
});

Route::middleware(['auth', 'roleaccess:admin'])->group(function(){
    Route::redirect('/home', '/administrator');
    Route::get('/administrator', [AdminController::class, 'index'])->name('dashboard.admin');
});

Route::middleware(['auth', 'roleaccess:guru'])->group(function(){
    Route::redirect('/home', '/guru');
    Route::get('/guru', [GuruController::class, 'index'])->name('dashboard.guru');
});
