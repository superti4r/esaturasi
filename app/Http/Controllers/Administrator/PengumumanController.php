<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengumumanController extends Controller
{

    public function index()
    {
        $pengumuman = Pengumuman::all();
        return view('administrator.pengumuman.index', compact('pengumuman'));
    }

    public function add()
    {
        return view('administrator.pengumuman.add');
    }

    public function view($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('administrator.pengumuman.view', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'content_pengumuman' => 'required',
        ]);

        Pengumuman::create($request->all());

        return redirect()->route('administrator.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('administrator.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'content_pengumuman' => 'required',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->all());

        return redirect()->route('administrator.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function delete($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('administrator.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;
        Pengumuman::whereIn('id', $ids)->delete();

        return redirect()->route('administrator.pengumuman')->with('success', 'Pengumuman yang dipilih berhasil dihapus.');
    }
}
