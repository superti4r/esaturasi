<?php

namespace App\Http\Controllers\Guru;

use App\Models\Slug;
use App\Models\Tugas;
use App\Models\Materi;
use Illuminate\Http\Request;
use App\Models\PembagianJadwal;
use App\Http\Controllers\Controller;
use App\Models\Slugs;
use Illuminate\Support\Facades\Auth;

class DataTugasDanMateri extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jadwal = PembagianJadwal::where('guru_id', $user->id)
            ->whereHas('arsip', function ($query) {
                $query->where('status', 'aktif');
            })
            ->with(['kelas', 'mataPelajaran', 'arsip'])
            ->get();

        return view('guru.tugas-dan-materi.index', compact('jadwal'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:pembagian_jadwal,id',
            'judul' => 'required|string|max:255',
        ]);

        $jadwal = PembagianJadwal::with('arsip')->findOrFail($request->jadwal_id);
        if ($jadwal->arsip->status !== 'aktif') {
            return redirect()->route('guru.tugas-dan-materi.index')
                ->with('error', 'Tidak dapat menambahkan tugas/materi karena jadwal telah diarsipkan.');
        }

        $slugString = substr(md5(uniqid()), 0, 10);

        Slugs::create([
            'jadwal_id' => $request->jadwal_id,
            'judul' => $request->judul,
            'slug' => $slugString,
        ]);

        return redirect()->route('guru.tugas-dan-materi.index')->with('success', 'Tugas/Materi berhasil dibuat.');
    }

    public function show($id)
    {
        $slugData = Slugs::with('jadwal.arsip')->where('slug', $id)->firstOrFail();
        if ($slugData->jadwal->arsip->status !== 'aktif') {
            return redirect()->route('guru.tugas-dan-materi.index')
                ->with('error', 'Tugas/Materi tidak dapat diakses karena jadwal telah diarsipkan.');
        }

        $tugas = Tugas::where('slug_id', $slugData->id)->get();
        $materi = Materi::where('slug_id', $slugData->id)->get();

        return view('guru.tugas-dan-materi.show', compact('slugData', 'tugas', 'materi'));
    }

    public function destroy($id)
    {
        $slug = Slugs::with('jadwal.arsip')->findOrFail($id);
        if ($slug->jadwal->arsip->status !== 'aktif') {
            return redirect()->route('guru.tugas-dan-materi.index')
                ->with('error', 'Tidak dapat menghapus karena jadwal telah diarsipkan.');
        }

        Tugas::where('slug_id', $slug->id)->delete();
        Materi::where('slug_id', $slug->id)->delete();
        $slug->delete();

        return redirect()->route('guru.tugas-dan-materi.index')->with('success', 'Tugas/Materi berhasil dihapus.');
    }
}
