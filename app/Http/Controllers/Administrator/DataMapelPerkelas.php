<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Arsip;
use App\Models\MapelPerkelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DataMapelPerkelas extends Controller
{
    public function index()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->back()->with('error', 'Tidak ada arsip aktif.');
        }

        $mapelPerKelas = MapelPerkelas::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('arsip_id', $arsipAktif->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $kelas = Kelas::all();

        return view('administrator.mapelperkelas.index', compact('mapelPerKelas', 'kelas', 'arsipAktif'));
    }

    public function add()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.mapelperkelas')->with('error', 'Tidak ada arsip aktif.');
        }

        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.mapelperkelas.add', compact('kelas', 'mapel', 'guru', 'arsipAktif'));
    }

    public function store(Request $request)
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.mapelperkelas')->with('error', 'Tidak ada arsip aktif.');
        }

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
        ]);

        $existingData = MapelPerkelas::where([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
            'arsip_id' => $arsipAktif->id,
        ])->exists();

        if ($existingData) {
            return redirect()->back()->with('error', 'Data ini sudah ada dalam arsip aktif.');
        }

        MapelPerkelas::create([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
            'arsip_id' => $arsipAktif->id,
        ]);

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapelPerKelas = MapelPerkelas::findOrFail($id);
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.mapelperkelas.edit', compact('mapelPerKelas', 'kelas', 'mapel', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $mapelPerKelas = MapelPerkelas::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
        ]);

        $mapelPerKelas->update([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $mapelPerKelas = MapelPerkelas::findOrFail($id);
        $mapelPerKelas->delete();

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Data berhasil dihapus.');
    }

    public function reset()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.mapelperkelas')->with('error', 'Tidak ada arsip aktif.');
        }

        MapelPerkelas::where('arsip_id', $arsipAktif->id)->delete();

        return redirect()->route('administrator.mapelperkelas')->with('success', 'Semua data dalam arsip aktif berhasil direset.');
    }
}
