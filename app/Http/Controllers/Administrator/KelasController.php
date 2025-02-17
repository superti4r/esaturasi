<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('jurusan')->get();
        return view('administrator.kelas.index', compact('kelas'));
    }

    public function add()
    {
        $jurusan = Jurusan::all();
        return view('administrator.kelas.add', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusan,id',
        ]);

        Kelas::create($request->all());

        return redirect()->route('administrator.kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $jurusan = Jurusan::all();
        return view('administrator.kelas.edit', compact('kelas', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusan,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->route('administrator.kelas')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('administrator.kelas')->with('success', 'Kelas berhasil dihapus.');
    }

    public function bulkdelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:kelas,id',
        ]);

        Kelas::whereIn('id', $request->selected_ids)->delete();

        return redirect()->route('administrator.kelas')->with('success', 'Beberapa kelas berhasil dihapus.');
    }
}
