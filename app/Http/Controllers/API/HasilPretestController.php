<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HasilPretest;
use App\Models\Pretest;
use Illuminate\Http\Request;

class HasilPretestController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'pretest_id' => 'required|integer',
            'jawaban'    => 'required|array',
        ]);

        // Cek apakah sudah pernah mengerjakan
        $sudahDikerjakan = HasilPretest::where('student_id', $request->student_id)
            ->where('pretest_id', $request->pretest_id)
            ->exists();

        if ($sudahDikerjakan) {
            return response()->json([
                'success'          => false,
                'message'          => 'Kamu sudah pernah mengerjakan pretest ini.',
                'sudah_dikerjakan' => true,
            ], 403);
        }

        // Ambil pretest beserta soal
        $pretest = Pretest::with('soal')->find($request->pretest_id);

        if (!$pretest) {
            return response()->json([
                'success' => false,
                'message' => 'Pretest tidak ditemukan.'
            ], 404);
        }

        // Hitung nilai
        $totalSoal = $pretest->soal->count();
        $benar     = 0;

        foreach ($request->jawaban as $jawaban) {
            $soal = $pretest->soal->firstWhere('id', $jawaban['soal_id']);
            if ($soal && strtoupper($soal->jawaban) === strtoupper($jawaban['jawaban'])) {
                $benar++;
            }
        }

        $nilai = $totalSoal > 0 ? round(($benar / $totalSoal) * 100) : 0;
        $lulus = $nilai >= $pretest->kkm;

        // Simpan hasil
        HasilPretest::create([
            'student_id' => $request->student_id,
            'pretest_id' => $request->pretest_id,
            'nilai'      => $nilai,
            'lulus'      => $lulus,
        ]);

        return response()->json([
            'success' => true,
            'message' => $lulus
                ? 'Selamat! Kamu lulus pretest.'
                : 'Belum lulus. Semangat belajar lagi!',
            'data' => [
                'nilai'      => $nilai,
                'lulus'      => $lulus,
                'kkm'        => $pretest->kkm,
                'benar'      => $benar,
                'total_soal' => $totalSoal,
            ]
        ]);
    }

    public function cekHasil($studentId, $pretestId)
    {
        $hasil = HasilPretest::where('student_id', $studentId)
            ->where('pretest_id', $pretestId)
            ->first();

        if (!$hasil) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada hasil pretest.'
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