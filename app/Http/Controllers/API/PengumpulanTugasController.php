<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengumpulanTugas;

class PengumpulanTugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Pastikan autentikasi
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'siswa_id' => 'required|integer|exists:siswas,id', // Pastikan siswa_id ada
            'tugas_id' => 'required|integer|exists:tugas,id',  // Pastikan tugas_id ada
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Simpan file
        try {
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('tugas', 'public');
                $url = Storage::url($path); // URL file yang disimpan
            } else {
                return response()->json(['message' => 'File tidak ditemukan'], 400);
            }

            // Simpan data pengumpulan tugas ke database
            $pengumpulan = PengumpulanTugas::create([
                'siswa_id' => $request->siswa_id,
                'tugas_id' => $request->tugas_id,
                'file_path' => $url,
            ]);

            return response()->json([
                'message' => 'Tugas berhasil dikumpulkan',
                'data' => $pengumpulan,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}


