<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PembagianJadwal;

class DataJadwal extends Controller
{
    public function getJadwalByKelas($idKelas)
    {
        // Mengambil jadwal berdasarkan ID kelas
        $jadwal = PembagianJadwal::where('kelas_id', $idKelas)->get(); 
    
        // Log data for debugging
        \Log::info('Fetched jadwal: ', ['jadwal' => $jadwal]);
    
        // Mengembalikan data jadwal dalam format JSON
        return response()->json($jadwal);
    }
    
    
}
