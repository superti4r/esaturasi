<?php

namespace App\Http\Controllers\API;

use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TugasController extends Controller
{
    public function getTugas()
    {
        $tugas = Tugas::all(); // Mengambil semua data tugas
        return response()->json($tugas); // Mengembalikan data dalam format JSON
    }
}
