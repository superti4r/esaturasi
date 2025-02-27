<?php
namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('arsip')->orderBy('created_at', 'desc')->get();
        
        // Transformasi data untuk memastikan semua informasi tersedia
        $pengumuman = $pengumuman->map(function ($item) {
            // Pastikan arsip data lengkap dengan path file jika ada
            if ($item->arsip) {
                $item->arsip->file_path = $item->arsip->file_path ?? null;
            }
            return $item;
        });
        
        return response()->json([
            'status' => 'success',
            'pengumuman' => $pengumuman
        ]);
    }
}