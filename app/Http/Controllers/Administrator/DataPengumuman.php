<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Pengumuman;
use App\Models\Arsip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataPengumuman extends Controller
{
    public function index()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->back()->with('error', 'Tidak ada arsip aktif.');
        }

        $pengumuman = Pengumuman::where('arsip_id', $arsipAktif->id)->get();
        return view('administrator.pengumuman.index', compact('pengumuman', 'arsipAktif'));
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
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.pengumuman')->with('error', 'Tidak ada arsip aktif.');
        }

        $request->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'content_pengumuman' => 'required|string',
        ]);

        Pengumuman::create([
            'judul_pengumuman' => $request->judul_pengumuman,
            'content_pengumuman' => $request->content_pengumuman,
            'arsip_id' => $arsipAktif->id,
        ]);

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
            'content_pengumuman' => 'required|string',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update([
            'judul_pengumuman' => $request->judul_pengumuman,
            'content_pengumuman' => $request->content_pengumuman,
        ]);

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
        if (!$request->has('ids') || empty($request->ids)) {
            return redirect()->route('administrator.pengumuman')->with('error', 'Tidak ada pengumuman yang dipilih untuk dihapus.');
        }

        Pengumuman::whereIn('id', $request->ids)->delete();

        return redirect()->route('administrator.pengumuman')->with('success', 'Pengumuman yang dipilih berhasil dihapus.');
    }
}
