<?php
namespace App\Http\Controllers\API;

use App\Models\MapelPerkelas; // Pastikan menggunakan model yang benar
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function getMataPelajaran($kelasId)
{
    // Ambil data mapel per kelas berdasarkan kelas_id
    $mapelPerKelas = MapelPerKelas::where('kelas_id', $kelasId)
        ->with('mataPelajaran') // Muat relasi mataPelajaran
        ->get();

    // Jika data tidak ditemukan
    if ($mapelPerKelas->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Mata pelajaran tidak ditemukan untuk kelas ini',
        ], 404);
    }

    // Format data mata pelajaran
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
