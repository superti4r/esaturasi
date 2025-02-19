<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Arsip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataArsip extends Controller
{
    public function index()
    {
        $arsip = Arsip::all();
        return view('administrator.arsip.index', compact('arsip'));
    }

    public function add()
    {
        return view('administrator.arsip.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        Arsip::create($request->all());

        return redirect()->route('administrator.arsip')->with('success', 'Data arsip berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        return view('administrator.arsip.edit', compact('arsip'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $arsip = Arsip::findOrFail($id);
        $arsip->update($request->all());

        return redirect()->route('administrator.arsip')->with('success', 'Data arsip berhasil diperbarui.');
    }

    public function delete($id)
    {
        $arsip = Arsip::findOrFail($id);
        $arsip->delete();

        return redirect()->route('administrator.arsip')->with('success', 'Data arsip berhasil dihapus.');
    }
}
