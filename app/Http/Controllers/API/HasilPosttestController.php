<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HasilPosttest;
use App\Models\JawabanPosttest;
use App\Models\Posttest;
use Illuminate\Http\Request;

class HasilPosttestController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|integer',
            'posttest_id' => 'required|integer',
            'jawaban'     => 'required|array',
        ]);

        // Cek apakah sudah pernah mengerjakan
        $sudahDikerjakan = HasilPosttest::where('student_id', $request->student_id)
            ->where('posttest_id', $request->posttest_id)
            ->exists();

        if ($sudahDikerjakan) {
            return response()->json([
                'success'          => false,
                'message'          => 'Kamu sudah pernah mengerjakan posttest ini.',
                'sudah_dikerjakan' => true,
            ], 403);
        }

        $posttest = Posttest::with('soal')->find($request->posttest_id);

        if (!$posttest) {
            return response()->json([
                'success' => false,
                'message' => 'Posttest tidak ditemukan.'
            ], 404);
        }

        // Hitung nilai dan simpan jawaban per soal
        $totalSoal = $posttest->soal->count();
        $benar     = 0;

        foreach ($request->jawaban as $jawaban) {
            $soal = $posttest->soal->firstWhere('id', $jawaban['soal_id']);

            if (!$soal) continue;

            $isBenar = strtoupper($soal->jawaban) === strtoupper($jawaban['jawaban']);

            if ($isBenar) $benar++;

            // Simpan jawaban per soal ke tabel jawaban_posttests
            JawabanPosttest::create([
                'posttest_id'      => $request->posttest_id,
                'soal_posttest_id' => $soal->id,
                'student_id'       => $request->student_id,
                'jawaban_siswa'    => strtoupper($jawaban['jawaban']),
                'is_benar'         => $isBenar,
            ]);
        }

        $nilai = $totalSoal > 0 ? round(($benar / $totalSoal) * 100) : 0;
        $lulus = $nilai >= $posttest->kkm;

        // Simpan hasil akhir ke tabel hasil_posttests
        HasilPosttest::create([
            'student_id'  => $request->student_id,
            'posttest_id' => $request->posttest_id,
            'nilai'       => $nilai,
            'lulus'       => $lulus,
        ]);

        return response()->json([
            'success' => true,
            'message' => $lulus
                ? 'Selamat! Kamu lulus posttest.'
                : 'Belum lulus. Semangat belajar lagi!',
            'data' => [
                'nilai'      => $nilai,
                'lulus'      => $lulus,
                'kkm'        => $posttest->kkm,
                'benar'      => $benar,
                'total_soal' => $totalSoal,
            ]
        ]);
    }

    public function cekHasil($studentId, $posttestId)
    {
        $hasil = HasilPosttest::where('student_id', $studentId)
            ->where('posttest_id', $posttestId)
            ->first();

        if (!$hasil) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada hasil posttest.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'nilai'      => $hasil->nilai,
                'lulus'      => $hasil->lulus,
                'created_at' => $hasil->created_at->format('d M Y'),
            ]
        ]);
    }
}