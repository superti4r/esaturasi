<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectMatter;


class SubjectMatterController extends Controller{
    
    public function getBabByMapelId($id)
{
    $babList = SubjectMatter::where('subject_id', $id)->get();

    if ($babList->isEmpty()) {
        return response()->json(['message' => 'Bab tidak ditemukan'], 404);
    }

    return response()->json($babList);
}

public function getBySlug($id)
{
    $materials = SubjectMatter::where('slug_id', $id)->get();
    return response()->json($materials);
}
}