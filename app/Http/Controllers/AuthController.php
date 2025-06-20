<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login (GET /login)
     */
    public function login()
    {
        return view('auth.login');   // resources/views/auth/login.blade.php
    }

    /**
     * Proses login via AJAX (POST /login)
     */
    public function postLogin(Request $request)
    {
        // 1) Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // 2) Kredensial—sesuaikan kolom di tabel users
        $credentials = [
            'username' => $request->username,   // ganti ke 'email' jika pakai email
            'password' => $request->password,
        ];

        // 3) Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'status'   => true,
                'message'  => 'Login berhasil!',
                'redirect' => url('/')            // arahkan ke dashboard
            ]);
        }

        // 4) Gagal login
        return response()->json([
            'status'   => false,
            'message'  => 'Username atau password salah.',
            'msgField' => [
                'username' => ['Username atau password salah.']
            ]
        ]);
    }

    /**
     * Proses logout (GET /logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
