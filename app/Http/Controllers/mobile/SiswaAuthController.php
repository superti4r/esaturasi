<?php

namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SiswaAuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nisn' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari siswa berdasarkan NISN
        $siswa = Siswa::where('nisn', $request->nisn)->first();

        // Jika siswa tidak ditemukan atau password tidak cocok
        if (!$siswa || !Hash::check($request->password, $siswa->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NISN atau password salah'
            ], 401);
        }

        // Buat token otentikasi
        $token = Str::random(80);
        $siswa->api_token = $token;
        $siswa->save();

        // Hapus informasi sensitif dari response
        $siswaData = $siswa->makeHidden(['password', 'remember_token', 'api_token']);

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $token,
            'siswa' => $siswaData
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);

        // Cari siswa berdasarkan token
        $siswa = Siswa::where('api_token', $token)->first();

        if ($siswa) {
            // Hapus token
            $siswa->api_token = null;
            $siswa->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

    public function getProfile(Request $request)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);

        // Cari siswa berdasarkan token
        $siswa = Siswa::with(['kelas', 'jurusan'])->where('api_token', $token)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // Hapus informasi sensitif
        $siswaData = $siswa->makeHidden(['password', 'remember_token', 'api_token']);

        return response()->json([
            'status' => 'success',
            'data' => $siswaData
        ]);
    }
}