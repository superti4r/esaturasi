<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        return view('administrator.jurusan.index', compact('jurusan'));
    }

    public function add()
    {
        return view('administrator.jurusan.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jurusan' => 'required|string|max:5|unique:jurusan',
            'nama_jurusan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Jurusan::create($request->all());

        return redirect()->route('administrator.jurusan')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('administrator.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_jurusan' => 'required|string|max:5|unique:jurusan,kode_jurusan,' . $jurusan->id,
            'nama_jurusan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jurusan->update($request->all());

        return redirect()->route('administrator.jurusan')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('administrator.jurusan')->with('success', 'Jurusan berhasil dihapus.');
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['error' => 'Tidak ada data yang dipilih.'], 400);
        }

        Jurusan::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'Jurusan yang dipilih berhasil dihapus.']);
    }
}
