<?php

namespace App\Http\Controllers\API;

use App\Models\MapelPerkelas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataMataPelajaran extends Controller
{
        public function getMataPelajaran($kelasId)
    {
        $mapelPerKelas = MapelPerKelas::where('kelas_id', $kelasId)
            ->with('mataPelajaran')
            ->get();

        if ($mapelPerKelas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan untuk kelas ini',
            ], 404);
        }

        $mataPelajaran = $mapelPerKelas->map(function ($item) {
            return [
                'id' => $item->mataPelajaran->id,
                'nama_mapel' => $item->mataPelajaran->nama_mapel,
            ];
        });

        return response()->json([
            'status' => 'success',
            'mata_pelajaran' => $mataPelajaran,
        ]);
    }
}
