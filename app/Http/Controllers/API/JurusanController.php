<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\Controller; // Tambahkan ini!
use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function getJurusan($id)
    {
        $jurusan = Jurusan::where('id', $id)->first();

        if ($jurusan) {
            return response()->json([
                'nama_jurusan' => $jurusan->nama_jurusan
            ]);
        } else {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }
    }
}
