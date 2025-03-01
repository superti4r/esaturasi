<?php
<<<<<<< HEAD:app/Http/Controllers/mobile/PengumumanController.php
=======
namespace App\Http\Controllers\API;
>>>>>>> dbd2cc1526c0bbe74162f4a4add31b2a009062dd:app/Http/Controllers/API/PengumumanController.php

namespace App\Http\Controllers\mobile; // Sesuaikan dengan lokasi file

use App\Http\Controllers\Controller; // Tambahkan ini!
use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
<<<<<<< HEAD:app/Http/Controllers/mobile/PengumumanController.php
    public function getPengumuman()
{
    $pengumuman = Pengumuman::all()->map(function ($item) {
        $gambarPath = null;

        if (preg_match('/<img src="data:image\/png;base64,([^"]+)"/', $item->content_pengumuman, $matches)) {
            $gambarPath = $matches[1]; // Base64 image
        }

        return [
            'id' => $item->id,
            'judul_pengumuman' => $item->judul_pengumuman,
            'deskripsi_pengumuman' => strip_tags($item->content_pengumuman), // Hapus tag HTML
            'gambar' => $gambarPath, // Base64 gambar
            'created_at' => $item->created_at,
        ];
    });

    return response()->json($pengumuman);
}

=======
    public function index()
    {
        $pengumuman = Pengumuman::with('arsip')->orderBy('created_at', 'desc')->get();
        $pengumuman = $pengumuman->map(function ($item) {
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
>>>>>>> dbd2cc1526c0bbe74162f4a4add31b2a009062dd:app/Http/Controllers/API/PengumumanController.php
}
