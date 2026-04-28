<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Posttest;

class PosttestController extends Controller
{
    public function getBySlugId($slugId)
    {
        $posttest = Posttest::where('slug_id', $slugId)
            ->with('soal')
            ->first();

        if (!$posttest) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada Posttest untuk materi ini.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $posttest
        ]);
    }
}