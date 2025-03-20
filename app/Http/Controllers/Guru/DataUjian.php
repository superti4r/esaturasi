<?php

namespace App\Http\Controllers\Guru;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Ujian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataUjian extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ujian = Ujian::where(function ($query) use ($user) {
                $query->where('guru_id', $user->id)
                      ->orWhereNull('guru_id');
            })
            ->whereHas('mataPelajaran', function ($query) {
                $query->whereHas('arsip', function ($subQuery) {
                    $subQuery->where('status', 'aktif');
                });
            }, '>=', 0)
            ->with(['mataPelajaran.arsip', 'kelas', 'guru'])
            ->get();

        return view('guru.ujian.index', compact('ujian'));
    }

    public function add()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('guru.ujian.add', compact('mataPelajaran', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:UTS,UAS',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'waktu_pengerjaan' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        Ujian::create([
            'jenis' => $request->jenis,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'guru_id' => Auth::id(),
            'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'status' => $request->status,
            'token' => Str::random(8),
        ]);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();

        return view('guru.ujian.edit', compact('ujian', 'mataPelajaran', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|in:UTS,UAS',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'waktu_pengerjaan' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $ujian = Ujian::findOrFail($id);
        $ujian->update([
            'jenis' => $request->jenis,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'guru_id' => auth()->user()->id,
            'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'status' => $request->status,
        ]);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }
}
