<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pretest;
use Illuminate\Http\Request;

class PretestController extends Controller
{
    public function getBySlugId($slugId)
    {
        $pretest = Pretest::where('slug_id', $slugId)
                    ->with('soal')
                    ->first();

        if (!$pretest) {
            return response()->json([
                'success' => false,
                'message' => 'Pretest tidak ditemukan untuk slug_id: ' . $slugId
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pretest
        ]);
    }
}