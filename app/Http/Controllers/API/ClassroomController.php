<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;


class ClassroomController extends Controller
{
    public function getClass($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
        }
        return response()->json(['name' => $classroom->name]);
    }
}
