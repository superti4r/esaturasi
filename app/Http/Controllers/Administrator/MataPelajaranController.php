<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mataPelajaran = MataPelajaran::all();
        return view('administrator.mapel.index', compact('mataPelajaran'));
    }

    public function add()
    {
        return view('administrator.mapel.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        MataPelajaran::create($request->all());

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
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->update($request->all());

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
        $ids = $request->ids;

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid data format.'], 400);
        }

        MataPelajaran::whereIn('id', $ids)->delete();

        return response()->json(['success' => "Mata Pelajaran yang dipilih berhasil dihapus."]);
    }
}
