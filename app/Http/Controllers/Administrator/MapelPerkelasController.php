<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\MapelPerKelas;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MapelPerkelasController extends Controller
{
    public function index()
    {
        $mapelPerKelas = MapelPerKelas::with(['kelas', 'mataPelajaran', 'guru'])->get();
        $kelas = Kelas::all();

        return view('administrator.mapelperkelas.index', compact('mapelPerKelas', 'kelas'));
    }

    public function add()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.mapelperkelas.add', compact('kelas', 'mapel', 'guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
        ]);

        MapelPerKelas::create([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapelPerKelas = MapelPerKelas::with(['kelas', 'mataPelajaran', 'guru'])->findOrFail($id);
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.mapelperkelas.edit', compact('mapelPerKelas', 'kelas', 'mapel', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
        ]);

        $mapelPerKelas = MapelPerKelas::findOrFail($id);
        $mapelPerKelas->update([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $mapelPerKelas = MapelPerKelas::findOrFail($id);
        $mapelPerKelas->delete();

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil dihapus.');
    }

    public function reset()
    {
        DB::table('mapel_perkelas')->truncate();

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Semua data berhasil direset.');
    }
}
