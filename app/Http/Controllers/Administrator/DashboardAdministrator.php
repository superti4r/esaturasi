<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;

class DashboardAdministrator extends Controller
{
    function index(){

        $totalUser = User::count();
        $totalSiswa = Siswa::count();
        $totalMapel = MataPelajaran::count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();

        return view('administrator.dashboard', compact(
            'totalUser',
            'totalSiswa',
            'totalMapel',
            'totalKelas',
            'totalJurusan',
        ));
    }
}
