<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;


class DataKelas extends Controller
{
    public function getKelas($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
        }

        return response()->json(['nama_kelas' => $kelas->nama_kelas]);
    }
}
