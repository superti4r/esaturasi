<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Bab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BabController extends Controller
{
    public function index()
    {
        $babs = Bab::all();
        return view('administrator.bab.index', compact('babs'));
    }

    public function add()
    {
        return view('administrator.bab.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bab' => 'required|string|max:255',
        ]);

        Bab::create($validated);

        return redirect()->route('administrator.bab')->with('success', 'Bab berhasil ditambahkan');
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:bab,id',
        ]);

        $bab = Bab::findOrFail($validated['id']);
        $bab->delete();

        return redirect()->route('administrator.bab')->with('success', 'Bab berhasil dihapus');
    }
}
