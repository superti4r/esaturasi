<?php

namespace App\Http\Controllers\API;

use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TugasController extends Controller
{
    public function getTugas()
    {
        $tugas = Tugas::with([
            'slug.jadwal.guru',
            'slug.jadwal.mataPelajaran' 
        ])->get();

        return response()->json($tugas);
    }
}
