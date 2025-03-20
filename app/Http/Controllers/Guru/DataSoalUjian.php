<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Models\SoalUjian;
use App\Models\Ujian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DataSoalUjian extends Controller
{
    public function index($id)
    {
        $ujian = Ujian::with('soal')->findOrFail($id);
        return view('guru.soal.index', compact('ujian'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'soal' => 'required|string',
            'file_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'kunci_jawaban' => 'required|in:a,b,c,d',
            'bobot_jawaban' => 'required|integer|min:1',
        ]);

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('file_soal', 'public');
        }

        SoalUjian::create([
            'ujian_id' => $id,
            'soal' => $request->soal,
            'file_path' => $filePath,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'kunci_jawaban' => $request->kunci_jawaban,
            'bobot_jawaban' => $request->bobot_jawaban,
        ]);

        return redirect()->route('guru.soal.index', $id)->with('success', 'Soal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $soal = SoalUjian::findOrFail($id);

        $request->validate([
            'soal' => 'required|string',
            'file_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'kunci_jawaban' => 'required|in:a,b,c,d',
            'bobot_jawaban' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('file_path')) {
            if ($soal->file_path) {
                Storage::disk('public')->delete($soal->file_path);
            }
            $filePath = $request->file('file_path')->store('file_soal', 'public');
            $soal->file_path = $filePath;
        }

        $soal->update([
            'soal' => $request->soal,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'kunci_jawaban' => $request->kunci_jawaban,
            'bobot_jawaban' => $request->bobot_jawaban,
        ]);

        return redirect()->route('guru.soal.index', $soal->ujian_id)->with('success', 'Soal berhasil diperbarui!');
    }

    public function reset($id)
    {
        $ujian = Ujian::findOrFail($id);
        $soalUjian = SoalUjian::where('ujian_id', $id)->get();
        foreach ($soalUjian as $soal) {
            if ($soal->file_path) {
                Storage::disk('public')->delete($soal->file_path);
            }
        }

        SoalUjian::where('ujian_id', $id)->delete();

        return redirect()->route('guru.soal.index', $id)->with('success', 'Semua soal berhasil dihapus!');
    }
}
