<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Log::info("Menghapus foto lama: " . $user->avatar_url);
            Storage::disk('public')->delete($user->avatar_url);
        } else {
            Log::warning("Foto lama tidak ditemukan: " . $user->avatar_url);
        }

        $path = $request->file('foto_profil')->store('student', 'public');

        $user->avatar_url = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui.',
            'avatar_url' => asset("storage/$path"),
            'foto_path' => $path,
        ]);
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $user->avatar_url = null;
        $user->save();

        return response()->json(['message' => 'Foto profil berhasil dihapus.'], 200);
    }
}
