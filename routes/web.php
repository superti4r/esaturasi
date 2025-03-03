<?php

use App\Http\Controllers\SatriaAI;
use App\Http\Controllers\Session\MainAuth;
use App\Http\Controllers\Administrator\DashboardAdministrator;
use App\Http\Controllers\Administrator\DataProfile;
use App\Http\Controllers\Administrator\DataUser;
use App\Http\Controllers\Administrator\DataJurusan;
use App\Http\Controllers\Administrator\DataKelas;
use App\Http\Controllers\Administrator\DataMapelPerkelas;
use App\Http\Controllers\Administrator\DataMataPelajaran;
use App\Http\Controllers\Administrator\DataPembagianJadwal;
use App\Http\Controllers\Administrator\DataPengumuman;
use App\Http\Controllers\Administrator\DataSiswa;
use App\Http\Controllers\Administrator\DataArsip;
use App\Http\Controllers\Guru\DashboardGuru;
use App\Http\Controllers\Guru\DataJadwal;
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
    Route::get('/', function () {return view('welcome');});
    Route::post('/', [SatriaAI::class, 'chat'])->name('chatbot');
    Route::get('/login', [MainAuth::class, 'indexlogin'])->name('login');
    Route::post('/login', [MainAuth::class, 'login']);
    Route::get('/register', [MainAuth::class, 'indexregister'])->name('register');
    Route::post('/register', [MainAuth::class, 'register']);
    Route::get('/verifikasi/{verify_token}', [MainAuth::class, 'verify']);
    Route::get('/lupa-password', [MainAuth::class, 'indexForgotPassword'])->name('lupa-password');
    Route::post('/lupa-password', [MainAuth::class, 'forgotPassword']);
    Route::get('/reset-password/{token}', [MainAuth::class, 'indexResetPassword'])->name('reset-password');
    Route::post('/reset-password', [MainAuth::class, 'resetPassword']);

});

Route::middleware(['auth', 'roleaccess:administrator'])->group(function () {
    Route::redirect('/home', '/administrator');
    Route::get('/administrator', [DashboardAdministrator::class, 'index'])->name('administrator');
    Route::post('/administrator/token', [DashboardAdministrator::class, 'updateRegisterToken'])->name('administrator.update.register.token');
    Route::post('/administrator/api', [DashboardAdministrator::class, 'updateGeminiApiKey'])->name('administrator.update.gemini.api');
    Route::get('/administrator/token', [DashboardAdministrator::class, 'index']);
    Route::get('/administrator/api', [DashboardAdministrator::class, 'index']);
    Route::get('/administrator/settings', [DataProfile::class, 'index'])->name('administrator.settings');
    Route::put('/administrator/settings', [DataProfile::class, 'update'])->name('administrator.settings.update');

    Route::get('/administrator/user', [DataUser::class, 'index'])->name('administrator.user');
    Route::get('/administrator/user/add', [DataUser::class, 'add'])->name('administrator.user.add');
    Route::post('/administrator/user/add', [DataUser::class, 'store'])->name('administrator.user.add.post');
    Route::get('/administrator/user/edit/{id}', [DataUser::class, 'edit'])->name('administrator.user.edit');
    Route::put('/administrator/user/edit/{id}', [DataUser::class, 'update'])->name('administrator.user.edit.post');
    Route::get('/administrator/user/view/{id}', [DataUser::class, 'view'])->name('administrator.user.view');
    Route::delete('/administrator/user/delete/{id}', [DataUser::class, 'delete'])->name('administrator.user.delete');
    Route::post('/administrator/user/delete/selected', [DataUser::class, 'bulkdelete'])->name('administrator.user.bulkdelete');

    Route::get('/administrator/siswa', [DataSiswa::class, 'index'])->name('administrator.siswa');
    Route::get('/administrator/siswa/add', [DataSiswa::class, 'add'])->name('administrator.siswa.add');
    Route::post('/administrator/siswa/add', [DataSiswa::class, 'store'])->name('administrator.siswa.store');
    Route::get('/administrator/siswa/edit/{id}', [DataSiswa::class, 'edit'])->name('administrator.siswa.edit');
    Route::put('/administrator/siswa/edit/{id}', [DataSiswa::class, 'update'])->name('administrator.siswa.update');
    Route::get('/administrator/siswa/view/{id}', [DataSiswa::class, 'view'])->name('administrator.siswa.view');
    Route::delete('/administrator/siswa/delete/{id}', [DataSiswa::class, 'delete'])->name('administrator.siswa.delete');
    Route::post('/administrator/siswa/delete/selected', [DataSiswa::class, 'bulkdelete'])->name('administrator.siswa.bulkdelete');
    Route::post('/administrator/siswa/naik-kelas', [DataSiswa::class, 'naikkelas'])->name('administrator.siswa.naikkelas');
    Route::get('/administrator/siswa/export/pdf', [DataSiswa::class, 'exportPDF'])->name('administrator.siswa.export.pdf');
    Route::get('/administrator/siswa/export/xlsx', [DataSiswa::class, 'exportExcel'])->name('administrator.siswa.export.excel');
    Route::post('/administrator/siswa/import', [DataSiswa::class, 'import'])->name('administrator.siswa.import');
    Route::get('/administrator/siswa/download-xlsx', [DataSiswa::class, 'downloadTemplate'])->name('administrator.siswa.template');

    Route::get('/administrator/jurusan', [DataJurusan::class, 'index'])->name('administrator.jurusan');
    Route::get('/administrator/jurusan/add', [DataJurusan::class, 'add'])->name('administrator.jurusan.add');
    Route::post('/administrator/jurusan/add', [DataJurusan::class, 'store'])->name('administrator.jurusan.add.post');
    Route::get('/administrator/jurusan/edit/{id}', [DataJurusan::class, 'edit'])->name('administrator.jurusan.edit');
    Route::put('/administrator/jurusan/edit/{id}', [DataJurusan::class, 'update'])->name('administrator.jurusan.update');
    Route::delete('/administrator/jurusan/delete/{id}', [DataJurusan::class, 'delete'])->name('administrator.jurusan.delete');
    Route::post('/administrator/jurusan/delete/selected', [DataJurusan::class, 'bulkdelete'])->name('administrator.jurusan.bulkdelete');

    Route::get('/administrator/mapel', [DataMataPelajaran::class, 'index'])->name('administrator.mapel');
    Route::get('/administrator/mapel/add', [DataMataPelajaran::class, 'add'])->name('administrator.mapel.add');
    Route::post('/administrator/mapel/add', [DataMataPelajaran::class, 'store'])->name('administrator.mapel.post');
    Route::get('/administrator/mapel/edit/{id}', [DataMataPelajaran::class, 'edit'])->name('administrator.mapel.edit');
    Route::put('/administrator/mapel/edit/{id}', [DataMataPelajaran::class, 'update'])->name('administrator.mapel.update');
    Route::delete('/administrator/mapel/delete/{id}', [DataMataPelajaran::class, 'delete'])->name('administrator.mapel.delete');
    Route::post('/administrator/mapel/delete/selected', [DataMataPelajaran::class, 'bulkdelete'])->name('administrator.mapel.bulkdelete');

    Route::get('/administrator/kelas', [DataKelas::class, 'index'])->name('administrator.kelas');
    Route::get('/administrator/kelas/add', [DataKelas::class, 'add'])->name('administrator.kelas.add');
    Route::post('/administrator/kelas/add', [DataKelas::class, 'store'])->name('administrator.kelas.post');
    Route::get('/administrator/kelas/edit/{id}', [DataKelas::class, 'edit'])->name('administrator.kelas.edit');
    Route::put('/administrator/kelas/edit/{id}', [DataKelas::class, 'update'])->name('administrator.kelas.update');
    Route::delete('/administrator/kelas/delete/{id}', [DataKelas::class, 'delete'])->name('administrator.kelas.delete');
    Route::post('/administrator/kelas/delete/selected', [DataKelas::class, 'bulkdelete'])->name('administrator.kelas.bulkdelete');

    Route::get('/administrator/pengumuman', [DataPengumuman::class, 'index'])->name('administrator.pengumuman');
    Route::get('/administrator/pengumuman/add', [DataPengumuman::class, 'add'])->name('administrator.pengumuman.add');
    Route::post('/administrator/pengumuman/add', [DataPengumuman::class, 'store'])->name('administrator.pengumuman.post');
    Route::get('/administrator/pengumuman/edit/{id}', [DataPengumuman::class, 'edit'])->name('administrator.pengumuman.edit');
    Route::put('/administrator/pengumuman/edit/{id}', [DataPengumuman::class, 'update'])->name('administrator.pengumuman.update');
    Route::get('/administrator/pengumuman/view/{id}', [DataPengumuman::class, 'view'])->name('administrator.pengumuman.view');
    Route::delete('/administrator/pengumuman/delete/{id}', [DataPengumuman::class, 'delete'])->name('administrator.pengumuman.delete');
    Route::post('/administrator/pengumuman/delete/selected', [DataPengumuman::class, 'bulkdelete'])->name('administrator.pengumuman.bulkdelete');

    Route::get('/administrator/pembagian-jadwal', [DataPembagianJadwal::class, 'index'])->name('administrator.jadwal');
    Route::get('/administrator/pembagian-jadwal/edit/{id}', [DataPembagianJadwal::class, 'edit'])->name('administrator.jadwal.edit');
    Route::put('/administrator/pembagian-jadwal/edit/{id}', [DataPembagianJadwal::class, 'update'])->name('administrator.jadwal.update');
    Route::post('/administrator/pembagian-jadwal/reset', [DataPembagianJadwal::class, 'resetall'])->name('administrator.jadwal.resetall');
    Route::post('/administrator/pembagian-jadwal/sinkronisasi', [DataPembagianJadwal::class, 'sinkronisasi'])->name('administrator.jadwal.sinkronisasi');

    Route::get('/administrator/mapel-perkelas', [DataMapelPerkelas::class, 'index'])->name('administrator.mapelperkelas');
    Route::get('/administrator/mapel-perkelas/add', [DataMapelPerkelas::class, 'add'])->name('administrator.mapelperkelas.add');
    Route::post('/administrator/mapel-perkelas/add', [DataMapelPerkelas::class, 'store'])->name('administrator.mapelperkelas.store');
    Route::get('/administrator/mapel-perkelas/edit/{id}', [DataMapelPerkelas::class, 'edit'])->name('administrator.mapelperkelas.edit');
    Route::put('/administrator/mapel-perkelas/edit/{id}', [DataMapelPerkelas::class, 'update'])->name('administrator.mapelperkelas.update');
    Route::delete('/administrator/mapel-perkelas/delete/{id}', [DataMapelPerkelas::class, 'delete'])->name('administrator.mapelperkelas.delete');
    Route::post('/administrator/mapel-perkelas/reset', [DataMapelPerkelas::class, 'reset'])->name('administrator.mapelperkelas.reset');

    Route::get('/administrator/arsip', [DataArsip::class, 'index'])->name('administrator.arsip');
    Route::get('/administrator/arsip/add', [DataArsip::class, 'add'])->name('administrator.arsip.add');
    Route::post('/administrator/arsip/add', [DataArsip::class, 'store'])->name('administrator.arsip.store');
    Route::get('/administrator/arsip/edit/{id}', [DataArsip::class, 'edit'])->name('administrator.arsip.edit');
    Route::put('/administrator/arsip/edit/{id}', [DataArsip::class, 'update'])->name('administrator.arsip.update');
    Route::delete('/administrator/arsip/delete/{id}', [DataArsip::class, 'delete'])->name('administrator.arsip.delete');
});

Route::middleware(['auth', 'roleaccess:guru'])->group(function () {
    Route::redirect('/home', '/guru');
    Route::get('/guru', [DashboardGuru::class, 'index'])->name('guru');
    Route::get('/guru/jadwal-saya', [DataJadwal::class, 'index'])->name('guru.jadwal');
});

Route::get('/logout', [MainAuth::class, 'logout'])->name('logout');
