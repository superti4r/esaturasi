<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PembagianJadwal;

class DataJadwal extends Controller
{

    function index(){
        $user = auth()->user();
        $jadwal = PembagianJadwal::where('guru_id', $user->id)
        ->with(['kelas', 'jadwalMataPelajaran'])
        ->orderBy('hari')
        ->orderBy('jam_mulai')
        ->get();

        return view('guru.jadwal.index', compact('jadwal'));
    }
}
