<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slugs;

class SlugController extends Controller
{
    public function getByJadwal($id)
    {
        $slugs = Slugs::where('schedule_id', $id)->get();

        if ($slugs->isEmpty()) {
            return response()->json(['message' => 'Slug tidak ditemukan'], 404);
        }

        return response()->json($slugs);
    }
}
