<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $student = Student::where('nisn', $request->nisn)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'NISN atau Password salah'], 401);
        }

        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'student' => $student->makeHidden(['password', 'remember_token']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->makeHidden(['password', 'remember_token']));
    }
}
