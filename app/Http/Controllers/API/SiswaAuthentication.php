<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SiswaAuthentication extends Controller
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

    public function deletePhoto(Request $request)
    {
        $siswa = $request->user(); // Pastikan ini siswa (gunakan guard sesuai kebutuhan)
    
        if ($siswa->foto_profil && Storage::exists('public/' . $siswa->foto_profil)) {
            Storage::delete('public/' . $siswa->foto_profil);
            $siswa->foto_profil = null;
            $siswa->save();
    
            return response()->json(['message' => 'Foto profil berhasil dihapus.']);
        }
    
        return response()->json(['message' => 'Foto profil tidak ditemukan.'], 404);
    }

    
    public function deleteFotoProfil(Request $request)
{
    // Ambil data siswa yang sedang login
    $siswa = $request->user(); // jika kamu pakai guard 'siswa', sesuaikan di middleware

    if ($siswa->foto_profil) {
        $path = 'public/' . $siswa->foto_profil;

        // Hapus file dari storage jika ada
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        // Set kolom foto_profil ke null dan simpan
        $siswa->foto_profil = null;
        $siswa->save();

        return response()->json([
            'message' => 'Foto profil berhasil dihapus.',
        ], 200);
    }

    return response()->json([
        'message' => 'Foto profil tidak ditemukan.',
    ], 404);
}

public function uploadFotoProfil(Request $request)
{
    $siswa = $request->user();

    if ($request->hasFile('foto_profil')) {
        $file = $request->file('foto_profil');
        $filename = 'siswa_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/foto_siswa', $filename);

        // Hapus foto lama jika ada
        if ($siswa->foto_profil && Storage::exists('public/' . $siswa->foto_profil)) {
            Storage::delete('public/' . $siswa->foto_profil);
        }

        // Simpan nama file ke database (tanpa "public/")
        $siswa->foto_profil = 'foto_siswa/' . $filename;
        $siswa->save();

        return response()->json([
            'message' => 'Foto profil berhasil diupload.',
            'foto_profil' => asset('storage/' . $siswa->foto_profil)
        ]);
    }

    return response()->json([
        'message' => 'Tidak ada file foto yang dikirim.'
    ], 400);
}


}
