<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
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
