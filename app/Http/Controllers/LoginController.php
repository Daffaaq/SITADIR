<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Mengganti 'username' dengan 'email' dan menambahkan validasi email
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi', // Mengganti pesan validasi
            'email.email' => 'Format email tidak valid', // Menambahkan pesan validasi untuk format email
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email, // Menggunakan 'email' dari form input
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            $user = Auth::user();
            if ($user->status === 'aktif') {
                if ($user->role === 'pegawai') {
                    return redirect('dashboardPegawai');
                } elseif ($user->role === 'superadmin') {
                    return redirect('dashboardSuperadmin');
                } elseif ($user->role === 'kasubag umum') {
                    return redirect('dashboardKasubag');
                }
            } else {
                return redirect()->route('login')->withErrors('Akun Anda tidak aktif. Harap hubungi administrator.')->withInput();
            }
        } else {
            return redirect()->route('login')->withErrors('Email dan password tidak sesuai')->withInput(); // Mengubah pesan error
        }
    }


    function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
