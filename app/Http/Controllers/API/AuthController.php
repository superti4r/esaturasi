<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Refresh token untuk autentikasi API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request)
    {
        // Validasi input refresh_token
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $refreshToken = $request->input('refresh_token');

        // Meng-hash refresh token yang dikirimkan
        $hashedRefresh = hash('sha256', $refreshToken);

        // Mencari siswa berdasarkan hashed refresh token
        $siswa = Siswa::where('refresh_token', $hashedRefresh)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token tidak valid'
            ], 401);
        }

        // Cek apakah refresh token kadaluarsa (jika ada kolom expired_at)
        if ($siswa->refresh_token_expired_at && $siswa->refresh_token_expired_at < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token telah kadaluarsa'
            ], 401);
        }

        // Buat token baru
        $newAccessToken = Str::random(80);

        \Log::info('Token baru di-generate: ' . substr($newAccessToken, 0, 10) . '...');

        
        // Hash token baru dan simpan ke database
        $siswa->api_token = hash('sha256', $newAccessToken);
        
        $saved = $siswa->save();
        \Log::info('Token berhasil disimpan: ' . ($saved ? 'Ya' : 'Tidak'));

        $siswa->save();

        // Mengembalikan respons dengan token baru
        return response()->json([
            'status' => 'success',
            'token' => $newAccessToken
        ]);
    }
}

    

