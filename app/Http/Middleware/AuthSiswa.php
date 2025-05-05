<?php 
// app/Http/Middleware/AuthSiswa.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Siswa;

class AuthSiswa
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $siswa = Siswa::where('api_token', $token)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->setUserResolver(function () use ($siswa) {
            return $siswa;
        });

        return $next($request);
    }
}
