<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
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
