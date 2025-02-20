<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Bab;
use App\Models\Arsip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataBab extends Controller
{
    public function index()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->back()->with('error', 'Tidak ada arsip aktif.');
        }

        $babs = Bab::where('arsip_id', $arsipAktif->id)->orderBy('created_at', 'desc')->get();

        return view('administrator.bab.index', compact('babs', 'arsipAktif'));
    }

    public function add()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.bab')->with('error', 'Tidak ada arsip aktif.');
        }

        return view('administrator.bab.add', compact('arsipAktif'));
    }

    public function store(Request $request)
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.bab')->with('error', 'Tidak ada arsip aktif.');
        }

        $validated = $request->validate([
            'nama_bab' => 'required|string|max:255|unique:bab,nama_bab,NULL,id,arsip_id,' . $arsipAktif->id,
        ]);

        Bab::create([
            'nama_bab' => $validated['nama_bab'],
            'arsip_id' => $arsipAktif->id,
        ]);

        return redirect()->route('administrator.bab')->with('success', 'Bab berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $bab = Bab::findOrFail($id);
        $bab->delete();

        return redirect()->route('administrator.bab')->with('success', 'Bab berhasil dihapus.');
    }
}
