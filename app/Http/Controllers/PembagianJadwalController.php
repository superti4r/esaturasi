<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembagianJadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;

class PembagianJadwalController extends Controller
{
    public function index()
    {
        $jadwal = PembagianJadwal::with(['kelas', 'mataPelajaran', 'guru'])->get();
        $kelas = Kelas::all(); // Tambahkan ini agar $kelas tidak undefined

        return view('administrator.pembagian-jadwal.index', compact('jadwal', 'kelas'));
    }

    public function add()
    {
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.pembagian-jadwal.add', compact('kelas', 'mataPelajaran', 'guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        PembagianJadwal::create($request->all());

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = PembagianJadwal::findOrFail($id);
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.pembagian-jadwal.edit', compact('jadwal', 'kelas', 'mataPelajaran', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal = PembagianJadwal::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function reset($id)
    {
        $jadwal = PembagianJadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function resetall()
    {
        PembagianJadwal::truncate();

        return redirect()->route('administrator.jadwal')->with('success', 'Semua jadwal berhasil dihapus.');
    }
}
