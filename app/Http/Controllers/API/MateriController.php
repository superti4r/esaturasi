<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;


class MateriController extends Controller{
    
    public function getBabByMapelId($id)
{
    $babList = Bab::where('mapel_id', $id)->get();

    if ($babList->isEmpty()) {
        return response()->json(['message' => 'Bab tidak ditemukan'], 404);
    }

    return response()->json($babList);
}

public function getBySlug($id)
{
    $materials = Materi::where('slug_id', $id)->get();
    return response()->json($materials);
}
}