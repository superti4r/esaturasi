<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\Arsip;
use App\Http\Controllers\Controller;

class DataMataPelajaran extends Controller
{
    public function index()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->back()->with('error', 'Tidak ada arsip aktif.');
        }

        $mataPelajaran = MataPelajaran::where('arsip_id', $arsipAktif->id)
            ->orderBy('nama_mapel', 'asc')
            ->get();

        return view('administrator.mapel.index', compact('mataPelajaran', 'arsipAktif'));
    }

    public function add()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.mapel')->with('error', 'Tidak ada arsip aktif.');
        }

        return view('administrator.mapel.add', compact('arsipAktif'));
    }

    public function store(Request $request)
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.mapel')->with('error', 'Tidak ada arsip aktif.');
        }

        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel,NULL,id,arsip_id,' . $arsipAktif->id,
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
            'arsip_id' => $arsipAktif->id,
        ]);

        return redirect()->route('administrator.mapel')
                         ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        return view('administrator.mapel.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel,' . $id . ',id,arsip_id,' . $mataPelajaran->arsip_id,
        ]);

        $mataPelajaran->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('administrator.mapel')
                         ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return redirect()->route('administrator.mapel')
                         ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function bulkdelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:mata_pelajaran,id',
        ]);

        MataPelajaran::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => 'Mata Pelajaran yang dipilih berhasil dihapus.']);
    }
}
