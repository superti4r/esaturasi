<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdministratorProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('administrator.profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nik' => 'nullable|string|unique:users,nik,' . $user->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'cropped_image' => 'nullable|string',
        ]);

        $user->nik = $request->nik;
        $user->nama = $request->nama;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->tempat_lahir = $request->tempat_lahir;
        $user->alamat = $request->alamat;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');

            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = strtolower($matches[1]);
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));

                if ($imageData === false) {
                    return redirect()->back()->with('error', 'Data gambar tidak valid.');
                }

                if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return redirect()->back()->with('error', 'Format gambar tidak didukung.');
                }

                if ($imageType === 'jpeg') {
                    $imageType = 'jpg';
                }

                $imageName = 'foto_profil/' . uniqid() . '.' . $imageType;

                Storage::disk('public')->put($imageName, $imageData);

                if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }

                $user->foto_profil = $imageName;
            } else {
                return redirect()->back()->with('error', 'Format gambar tidak valid.');
            }
        }

        $user->save();

        return redirect()->route('administrator.settings')->with('success', 'Profil berhasil diperbarui.');
    }
}
