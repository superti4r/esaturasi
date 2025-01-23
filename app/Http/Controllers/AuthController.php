<?php

namespace App\Http\Controllers;

use App\Mail\AuthMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    function indexlogin(){
        return view('auth.login');
    }

    function login(Request $request){
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

        if (Auth::attempt($infologin)){
            if(Auth::user()->email_verified_at != null){
                if(Auth::user()->role === 'admin') {
                    return redirect()->route('dashboard.admin')->with('success', 'Halo Admin');
                } else if (Auth::user()->role === 'guru') {
                    return redirect()->route('dashboard.guru')->with('success', 'Halo Guru');
                }
            } else{
                Auth::logout();
                return redirect()->route('login')->withErrors('Akun anda belum terverifikasi. Harap verifikasi terlebih dahulu.');
            }
        } else {
            return redirect()->route('login')->withErrors('Email atau password salah');
        }
    }
    function indexregister(){
        return view('auth.register');
    }
    function register(Request $request){
        $str = Str::random(100);
        $request->validate([
            'nik' => 'required|max:16',
            'nama' => 'required|min:5',
            'role' => 'required|in:admin,guru',
            'token' => 'required|string',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ],[
            'nik.required' => 'NIK wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'role.required' => 'Role wajib diisi',
            'token.required' => 'Token wajib diisi untuk memverifikasi',
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($request->token !== env('REGISTER_TOKEN')){
            return back()->withErrors(['token' => 'Token yang ada masukkan tidak Valid'])->withInput();
        }

        $inforegister = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'role' => $request->role,
            'email' => $request->email,
            'password' => $request->password,
            'verify_token' => $str
        ];

        User::create($inforegister);
        $details = [
            'nik' => $inforegister['nik'],
            'nama' => $inforegister['nama'],
            'role' => $inforegister['role'],
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'E-Saturasi - Verifikasi',
            'url' => 'http://'. request()->getHttpHost() . "/" . "verify/". $inforegister['verify_token'],
        ];

        Mail::to($inforegister['email'])->send(new AuthMail($details));

        return redirect()->route('login')->with('success','Link verifikasi telah dikirim ke email anda.');
    }

    function verify($verify_token){
        $keycheck = User::select('verify_token')
        ->where('verify_token', $verify_token)
        ->exists();

        if($keycheck){
            $user = User::where('verify_token', $verify_token)->update(['email_verified_at' => date('Y-m-d H:i:s')]);
            return redirect()->route('login')->with('success', 'Verifikasi Berhasil.');
        }else {
            return redirect()->route('login')->withErrors('Key tidak valid. pastikan anda telah melakukan registrasi.')->withInput();
        }
    }
}
