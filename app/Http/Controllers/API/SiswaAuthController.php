<?php

namespace App\Http\Controllers\API;

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

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa || !Hash::check($request->password, $siswa->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NISN atau password salah'
            ], 401);
        }

<<<<<<< HEAD:app/Http/Controllers/mobile/SiswaAuthController.php
        
        // Buat token otentikasi
=======
>>>>>>> dbd2cc1526c0bbe74162f4a4add31b2a009062dd:app/Http/Controllers/API/SiswaAuthController.php
        $token = Str::random(80);
        $siswa->api_token = $token;
        $siswa->save();
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
        $siswa = Siswa::where('api_token', $token)->first();

        if ($siswa) {
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
        $siswa = Siswa::with(['kelas', 'jurusan'])->where('api_token', $token)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $siswaData = $siswa->makeHidden(['password', 'remember_token', 'api_token']);

        return response()->json([
            'status' => 'success',
            'data' => $siswaData
        ]);
    }
}
