<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class DataJurusan extends Controller
{
   // Controller API Laravel
public function getJurusanByKelas($idKelas)
{
    // Cari kelas berdasarkan ID
    $kelas = Kelas::with('jurusan')->find($idKelas); // Menggunakan relasi dengan jurusan

    if (!$kelas) {
        return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
    }

    // Mengambil data jurusan dari relasi
    return response()->json(['nama_jurusan' => $kelas->jurusan->nama_jurusan]);
}



}

