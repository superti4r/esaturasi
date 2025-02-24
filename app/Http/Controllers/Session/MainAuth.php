<?php

namespace App\Http\Controllers\Session;

use App\Models\User;
use App\Mail\AuthMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class MainAuth extends Controller
{
    public function indexlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->email_verified_at != null) {
                $nama = Auth::user()->nama;
                if (Auth::user()->role === 'administrator') {
                    return redirect()->route('administrator')->with('success', "Selamat datang, $nama");
                } else if (Auth::user()->role === 'guru') {
                    return redirect()->route('guru')->with('success', "Selamat datang, $nama");
                }
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors('Akun Anda belum terverifikasi. Harap verifikasi terlebih dahulu.');
            }
        } else {
            return redirect()->route('login')->withErrors('Email atau password salah');
        }
    }

    public function indexregister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $str = Str::random(100);
        $request->validate([
            'nik' => 'required|max:16',
            'nama' => 'required|min:5',
            'role' => 'required|in:administrator,guru',
            'token' => 'required|string',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'role.required' => 'Role wajib diisi',
            'token.required' => 'Token wajib diisi untuk memverifikasi',
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($request->token !== env('REGISTER_TOKEN')) {
            return back()->withErrors(['token' => 'Token yang Anda masukkan tidak Valid'])->withInput();
        }

        $inforegister = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_token' => $str,
            'verify_token_expired_at' => now()->addMinutes(30),
        ];

        User::create($inforegister);

        $details = [
            'nik' => $inforegister['nik'],
            'nama' => $inforegister['nama'],
            'role' => $inforegister['role'],
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'E-Saturasi - Verifikasi',
            'url' => url("/verify/{$inforegister['verify_token']}"),
        ];

        Mail::to($inforegister['email'])->send(new AuthMail($details));

        return redirect()->route('login')->with('success', 'Link verifikasi telah dikirim ke email Anda.');
    }


    public function verify($verify_token)
    {
        $user = User::where('verify_token', $verify_token)
                    ->where('verify_token_expired_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors('Token verifikasi tidak valid atau telah kedaluwarsa.');
        }

        $user->update([
            'email_verified_at' => now(),
            'verify_token' => null,
            'verify_token_expired_at' => null
        ]);

        return redirect()->route('login')->with('success', 'Verifikasi Berhasil.');
    }


    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function indexForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan dalam sistem',
        ]);

        $token = Str::random(100);
        $expiredAt = now()->addMinutes(30);

        $user = User::where('email', $request->email)->first();
        $user->update([
            'verify_token' => $token,
            'verify_token_expired_at' => $expiredAt
        ]);

        $details = [
            'nama' => $user->nama,
            'email' => $request->email,
            'url' => url("/reset-password/$token"),
            'datetime' => now()->format('d M Y H:i'),
            'website' => 'E-Saturasi - Reset Password'
        ];

        Mail::to($request->email)->send(new ForgotPasswordMail($details));

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function indexResetPassword($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $user = User::where('verify_token', $request->token)
                    ->where('verify_token_expired_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors('Token reset password tidak valid atau telah kedaluwarsa.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'verify_token' => null,
            'verify_token_expired_at' => null
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }

}
