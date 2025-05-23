<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;

class MajorController extends Controller
{
    public function getJurusanByKelas($idKelas)
    {
        $kelas = Classroom::with('major')->find($idKelas);

        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
        }

        return response()->json(['name' => $kelas->major->name]);
    }
}
