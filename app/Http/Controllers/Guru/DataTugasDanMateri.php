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
use Illuminate\Support\Facades\Storage;
use App\Models\PengumpulanTugas;

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

    public function indexMateri($id)
    {
        $slugData = Slugs::with('jadwal.arsip')->where('slug', $id)->firstOrFail();

        if ($slugData->jadwal->arsip->status !== 'aktif') {
            return redirect()->route('guru.tugas-dan-materi.index')
                ->with('error', 'Materi tidak dapat diakses karena jadwal telah diarsipkan.');
        }

        $materi = Materi::where('slug_id', $slugData->id)->get();

        return view('guru.tugas-dan-materi.materi.index', compact('slugData', 'materi'));
    }

    public function storeMateri(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,ppt,pptx,mp4,mkv,mov|max:51200',
        ]);

        $slugData = Slugs::where('slug', $id)->firstOrFail();
        $fileData = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'encrypted_name' => $file->store('file_materi', 'public'),
                ];
            }
        }

        Materi::create([
            'slug_id' => $slugData->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => !empty($fileData) ? json_encode($fileData) : null,
        ]);

        return redirect()->route('guru.tugas-dan-materi.show', $id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function updateMateri(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,ppt,pptx,mp4,mkv,mov|max:51200',
        ]);

        $materi = Materi::findOrFail($id);
        $slug = $materi->slug->slug;

        if ($materi->file_path) {
            foreach (json_decode($materi->file_path, true) as $oldFile) {
                Storage::disk('public')->delete($oldFile['encrypted_name']);
            }
        }

        $fileData = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'encrypted_name' => $file->store('file_materi', 'public'),
                ];
            }
        }

        $materi->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => !empty($fileData) ? json_encode($fileData) : null,
        ]);

        return redirect()->route('guru.tugas-dan-materi.show', $slug)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function indexTugas($id)
    {
        $slugData = Slugs::with('jadwal.arsip')->where('slug', $id)->firstOrFail();

        if ($slugData->jadwal->arsip->status !== 'aktif') {
            return redirect()->route('guru.tugas-dan-materi.tugas.index')
                ->with('error', 'Tugas tidak dapat diakses karena jadwal telah diarsipkan.');
        }

        $tugas = Tugas::where('slug_id', $slugData->id)->get();

        return view('guru.tugas-dan-materi.tugas.index', compact('slugData', 'tugas'));
    }

    public function storeTugas(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,ppt,pptx,mp4,mkv,mov|max:51200',
            'deadline' => 'nullable|date',
        ]);

        $slugData = Slugs::where('slug', $id)->firstOrFail();
        $fileData = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'encrypted_name' => $file->store('file_tugas', 'public'),
                ];
            }
        }

        Tugas::create([
            'slug_id' => $slugData->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => !empty($fileData) ? json_encode($fileData) : null,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('guru.tugas-dan-materi.tugas.index', $id)
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function updateTugas(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,ppt,pptx,mp4,mkv,mov|max:51200',
            'deadline' => 'nullable|date',
        ]);

        $tugas = Tugas::findOrFail($id);
        $slug = $tugas->slug->slug;

        if ($tugas->file_path) {
            foreach (json_decode($tugas->file_path, true) as $oldFile) {
                Storage::disk('public')->delete($oldFile['encrypted_name']);
            }
        }

        $fileData = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'encrypted_name' => $file->store('file_tugas', 'public'),
                ];
            }
        }

        $tugas->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => !empty($fileData) ? json_encode($fileData) : null,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('guru.tugas-dan-materi.tugas.index', $slug)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function indexPengumpulanTugas($id)
    {
        $tugas = Tugas::with('pengumpulan.siswa')->find($id);

        if (!$tugas) {
            return redirect()->back()->with('error', 'Silahkan buat tugas terlebih dahulu.');
        }

        return view('guru.tugas-dan-materi.pengumpulan.index', compact('tugas'));
    }

    public function beriNilai(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($id);
        $pengumpulan->update(['nilai' => $request->nilai]);

        return redirect()->back()->with('success', 'Nilai berhasil diberikan.');
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
