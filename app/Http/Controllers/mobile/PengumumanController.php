<?php

namespace App\Http\Controllers\mobile; // Sesuaikan dengan lokasi file

use App\Http\Controllers\Controller; // Tambahkan ini!
use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function getPengumuman()
    {
        $pengumuman = Pengumuman::all();

        foreach ($pengumuman as $item) {
            if (preg_match('/<img src="data:image\/png;base64,([^"]+)"/', $item->content_pengumuman, $matches)) {
                $item->content_pengumuman = $matches[1];
            } else {
                $item->content_pengumuman = null;
            }
        }

        return response()->json($pengumuman);
    }
}
