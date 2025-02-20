<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Arsip;
use Illuminate\Http\Request;
use App\Models\MapelPerKelas;
use App\Models\MataPelajaran;
use App\Models\PembagianJadwal;
use App\Http\Controllers\Controller;

class DataPembagianJadwal extends Controller
{
    public function index()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->back()->with('error', 'Tidak ada arsip aktif.');
        }

        $jadwal = PembagianJadwal::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('arsip_id', $arsipAktif->id)
            ->get();

        $kelas = Kelas::all();

        return view('administrator.pembagianjadwal.index', compact('jadwal', 'kelas', 'arsipAktif'));
    }

    public function add()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.jadwal')->with('error', 'Tidak ada arsip aktif.');
        }

        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::where('arsip_id', $arsipAktif->id)->get();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.pembagianjadwal.add', compact('kelas', 'mataPelajaran', 'guru', 'arsipAktif'));
    }

    public function store(Request $request)
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.jadwal')->with('error', 'Tidak ada arsip aktif.');
        }

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        PembagianJadwal::create([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'arsip_id' => $arsipAktif->id,
        ]);

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = PembagianJadwal::findOrFail($id);
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::where('arsip_id', $jadwal->arsip_id)->get();
        $guru = User::where('role', 'guru')->get();

        return view('administrator.pembagianjadwal.edit', compact('jadwal', 'kelas', 'mataPelajaran', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = PembagianJadwal::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jadwal = PembagianJadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('administrator.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function resetall()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.jadwal')->with('error', 'Tidak ada arsip aktif.');
        }

        PembagianJadwal::where('arsip_id', $arsipAktif->id)->delete();

        return redirect()->route('administrator.jadwal')->with('success', 'Semua jadwal berhasil dihapus.');
    }

    public function sinkronisasi()
    {
        $arsipAktif = Arsip::where('status', 'aktif')->first();

        if (!$arsipAktif) {
            return redirect()->route('administrator.jadwal')->with('error', 'Tidak ada arsip aktif.');
        }

        $kelas = Kelas::all();
        $jumlahSinkronisasi = 0;

        foreach ($kelas as $k) {
            $mapelKelas = MapelPerKelas::where('kelas_id', $k->id)
                ->where('arsip_id', $arsipAktif->id)
                ->get();

            foreach ($mapelKelas as $mapel) {
                $existingJadwal = PembagianJadwal::where([
                    'kelas_id' => $k->id,
                    'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                    'guru_id' => $mapel->guru_id,
                    'arsip_id' => $arsipAktif->id,
                ])->first();

                if (!$existingJadwal) {
                    PembagianJadwal::create([
                        'kelas_id' => $k->id,
                        'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                        'guru_id' => $mapel->guru_id,
                        'hari' => null,
                        'jam_mulai' => null,
                        'jam_selesai' => null,
                        'arsip_id' => $arsipAktif->id,
                    ]);

                    $jumlahSinkronisasi++;
                }
            }
        }

        return redirect()->route('administrator.jadwal')->with('success', "Sinkronisasi berhasil! $jumlahSinkronisasi jadwal baru ditambahkan.");
    }
}
