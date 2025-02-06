<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User as ModelUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DataUserController extends Controller
{
    public function index()
    {
        $data = ModelUser::all();
        if (auth()->user()->role !== 'administrator') {
            return redirect('/')->withErrors('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('administrator.user.index', ['data' => $data]);
    }

    public function add()
    {
        return view('administrator.user.add');
    }

    public function delete(Request $request)
    {
        ModelUser::where('id', $request->id)->delete();
        Session::flash('success', 'Berhasil hapus data');
        return redirect('/administrator/user');
    }

    public function bulkdelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ]);

        try {
            ModelUser::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data yang dipilih berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:users,nik',
            'nama' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string',
            'alamat' => 'nullable|string',
            'role' => 'required',
            'cropped_image' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'verified' => 'required|boolean',

        ], [
            'nik.unique' => 'NIK sudah terdaftar.',
        ]);

        if (ModelUser::where('nik', $request->nik)->exists()) {
            return redirect()->back()->with('error', 'NIK sudah terdaftar!')->withInput();
        }

        $data = new ModelUser();
        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->tanggal_lahir = $request->tanggal_lahir;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->alamat = $request->alamat;
        $data->role = $request->role;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);

        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));
            $imageName = 'foto_profil/' . uniqid() . '.jpg';
            $imagePath = storage_path('app/public/' . $imageName);

            if (!is_dir(storage_path('app/public/foto_profil'))) {
                mkdir(storage_path('app/public/foto_profil'), 0775, true);
            }

            if (file_put_contents($imagePath, $image)) {
                $data->foto_profil = $imageName;
            } else {
                return redirect()->back()->with('error', 'Gambar gagal diunggah.');
            }
        }

        if ($request->verified) {
            $data->email_verified_at = now();
        }

        $data->save();
        return redirect('/administrator/user')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = ModelUser::findOrFail($id);
        return view('administrator.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|unique:users,nik,' . $id,
            'nama' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string',
            'alamat' => 'nullable|string',
            'role' => 'required',
            'cropped_image' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $id,
            'verified' => 'required|boolean',
            'password' => 'nullable|min:8',
        ]);

        if (ModelUser::where('nik', $request->nik)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with('error', 'NIK sudah terdaftar!')->withInput();
        }

        $user = ModelUser::findOrFail($id);
        $user->nik = $request->nik;
        $user->nama = $request->nama;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->tempat_lahir = $request->tempat_lahir;
        $user->alamat = $request->alamat;
        $user->role = $request->role;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));
            $imageName = 'foto_profil/' . uniqid() . '.jpg';
            $imagePath = storage_path('app/public/' . $imageName);

            if (!is_dir(storage_path('app/public/foto_profil'))) {
                mkdir(storage_path('app/public/foto_profil'), 0775, true);
            }

            if (file_put_contents($imagePath, $image)) {
                if ($user->foto_profil && file_exists(storage_path('app/public/' . $user->foto_profil))) {
                    unlink(storage_path('app/public/' . $user->foto_profil));
                }
                $user->foto_profil = $imageName;
            } else {
                return redirect()->back()->with('error', 'Gambar gagal diunggah.');
            }
        }

        if ($request->verified) {
            $user->email_verified_at = now();
        } else {
            $user->email_verified_at = null;
        }

        $user->save();
        return redirect('/administrator/user')->with('success', 'User berhasil diperbarui!');
    }

    public function view($id)
    {
        $user = ModelUser::findOrFail($id);
        return view('administrator.user.view', compact('user'));
    }
}
