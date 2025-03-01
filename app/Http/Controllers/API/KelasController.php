<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\Controller; // Tambahkan ini!
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function getKelas($id)
    {
        $kelas = Kelas::where('id', $id)->first();

        if ($kelas) {
            return response()->json([
                'nama_kelas' => $kelas->nama_kelas
            ]);
        } else {
            return response()->json([
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }
    }
}
