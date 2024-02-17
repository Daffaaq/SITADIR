<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            // Lakukan sesuatu dengan $user
            // dd($user);

            // Kemudian, gunakan $user dalam tampilan atau logika lainnya
            return view('Superadmin.Profiles.index', compact('user'));
        }
        return back();
    }

    public function updateSuperadmin(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8',
        ]);

        // Dapatkan data pengguna yang sedang login
        $user = Auth::user();

        // Update profil pengguna
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // Jika password yang dimasukkan tidak kosong, enkripsi password baru
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);

        // Jika pengguna mencoba mengubah role atau status secara paksa, kembalikan pesan error
        if ($request->role != 'superadmin' || $request->status != 'aktif') {
            return redirect('/dashboardSuperadmin/Profiles')->with('info', 'Role and status cannot be changed, but profile updated successfully.');
        }


        // Redirect ke halaman profil dengan pesan sukses
        return redirect('/dashboardSuperadmin/Profiles')->with('success', 'Profile updated successfully.');
    }
}
