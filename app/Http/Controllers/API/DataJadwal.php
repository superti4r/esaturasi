<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PembagianJadwal;

class DataJadwal extends Controller
{
    public function getJadwalByKelas($idKelas)
    {
        $jadwal = PembagianJadwal::where('kelas_id', $idKelas)->get();

        \Log::info('Fetched jadwal: ', ['jadwal' => $jadwal]);

        return response()->json($jadwal);
    }


}
