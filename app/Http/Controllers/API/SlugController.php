<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slugs; // <- PAKAI Slugs (sesuai model kamu)

class SlugController extends Controller
{
    public function getByJadwal($id)
    {
        $slugs = Slugs::where('jadwal_id', $id)->get(); // <- juga pakai Slugs

        if ($slugs->isEmpty()) {
            return response()->json(['message' => 'Slug tidak ditemukan'], 404);
        }

        return response()->json($slugs);
    }
}
