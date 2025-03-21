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
        $soalList = SoalUjian::where('ujian_id', $id)->get();
        return view('guru.soal.index', compact('ujian', 'soalList'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required|array',
            'pertanyaan.*' => 'required|string',
            'file_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'pilihan_a' => 'required|array',
            'pilihan_a.*' => 'required|string',
            'pilihan_b' => 'required|array',
            'pilihan_b.*' => 'required|string',
            'pilihan_c' => 'required|array',
            'pilihan_c.*' => 'required|string',
            'pilihan_d' => 'required|array',
            'pilihan_d.*' => 'required|string',
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|in:A,B,C,D',
            'skor' => 'required|array',
            'skor.*' => 'required|integer|min:0',
        ]);

        foreach ($request->pertanyaan as $index => $soal) {
            $filePath = null;
            if ($request->hasFile("file_path.$index")) {
                $filePath = $request->file("file_path.$index")->store('file_soal', 'public');
            }

            SoalUjian::create([
                'ujian_id' => $id,
                'pertanyaan' => $soal,
                'file_path' => $filePath,
                'pilihan_a' => $request->pilihan_a[$index],
                'pilihan_b' => $request->pilihan_b[$index],
                'pilihan_c' => $request->pilihan_c[$index],
                'pilihan_d' => $request->pilihan_d[$index],
                'jawaban' => $request->jawaban[$index],
                'skor' => $request->skor[$index],
            ]);
        }

        return redirect()->route('guru.soal.index', $id)->with('success', 'Soal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        $soalList = SoalUjian::where('ujian_id', $id)->get();

        return view('guru.soal.edit', compact('ujian', 'soalList'));
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        if (!$request->has('pertanyaan')) {
            return redirect()->back()->with('error', 'Tidak ada soal yang dikirim.');
        }

        foreach ($request->pertanyaan as $index => $pertanyaan) {
            $soal = SoalUjian::where('ujian_id', $id)->skip($index)->first();
            if (!$soal) {
                continue;
            }

            $soal->update([
                'pertanyaan' => $pertanyaan,
                'pilihan_a'  => $request->pilihan_a[$index],
                'pilihan_b'  => $request->pilihan_b[$index],
                'pilihan_c'  => $request->pilihan_c[$index],
                'pilihan_d'  => $request->pilihan_d[$index],
                'jawaban'    => $request->jawaban[$index],
                'skor'       => $request->skor[$index],
            ]);

            if ($request->hasFile("file_path.$index")) {
                if ($soal->file_path && Storage::disk('public')->exists($soal->file_path)) {
                    Storage::disk('public')->delete($soal->file_path);
                }

                $file = $request->file("file_path.$index");
                $path = $file->store('file_soal', 'public');
                $soal->update(['file_path' => $path]);
            }
        }

        return redirect()->route('guru.soal.index', $id)->with('success', 'Soal berhasil diperbarui.');
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
