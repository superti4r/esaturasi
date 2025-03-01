<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function getJurusan($id)
    {
        $jurusan = Jurusan::find($id);
        
        if (!$jurusan) {
            return response()->json(['message' => 'Jurusan tidak ditemukan'], 404);
        }
        
        return response()->json(['nama_jurusan' => $jurusan->nama_jurusan]);
    }
}