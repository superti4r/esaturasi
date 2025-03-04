<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;

class DataPengumuman extends Controller
{
    public function getPengumuman()
{
    $pengumuman = Pengumuman::all()->map(function ($item) {
        $gambarPath = null;

        if (preg_match('/<img src="data:image\/png;base64,([^"]+)"/', $item->content_pengumuman, $matches)) {
            $gambarPath = $matches[1];
        }

        return [
            'id' => $item->id,
            'judul_pengumuman' => $item->judul_pengumuman,
            'deskripsi_pengumuman' => strip_tags($item->content_pengumuman),
            'gambar' => $gambarPath,
            'created_at' => $item->created_at,
        ];
    });

    return response()->json($pengumuman);
}
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
}
