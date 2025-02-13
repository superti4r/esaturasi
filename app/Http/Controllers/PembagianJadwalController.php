<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembagianJadwal;
use App\Models\Kelas;
use App\Models\MapelPerKelas;
use App\Models\MataPelajaran;
use App\Models\User;

class PembagianJadwalController extends Controller
{
    public function index()
    {
        $jadwal = PembagianJadwal::with(['kelas', 'mataPelajaran', 'guru'])->get();
        $kelas = Kelas::all();

        return view('administrator.pembagianjadwal.index', compact('jadwal', 'kelas'));
    }

    public function add()
    {
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.pembagianjadwal.add', compact('kelas', 'mataPelajaran', 'guru'));
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

        return view('administrator.pembagianjadwal.edit', compact('jadwal', 'kelas', 'mataPelajaran', 'guru'));
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

    public function sinkronisasi()
    {
        $kelas = Kelas::all();
        $jumlahSinkronisasi = 0;

        foreach ($kelas as $k) {
            $mapelKelas = MapelPerKelas::where('kelas_id', $k->id)->get();

            foreach ($mapelKelas as $mapel) {
                $existingJadwal = PembagianJadwal::where('kelas_id', $k->id)
                    ->where('mata_pelajaran_id', $mapel->mata_pelajaran_id)
                    ->where('guru_id', $mapel->guru_id)
                    ->first();

                if (!$existingJadwal) {
                    PembagianJadwal::create([
                        'kelas_id' => $k->id,
                        'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                        'guru_id' => $mapel->guru_id,
                        'hari' => null,
                        'jam_mulai' => null,
                        'jam_selesai' => null,
                    ]);

                    $jumlahSinkronisasi++;
                }
            }
        }

        return redirect()->route('administrator.jadwal')->with('success', "Sinkronisasi berhasil! $jumlahSinkronisasi jadwal baru ditambahkan.");
    }

}
