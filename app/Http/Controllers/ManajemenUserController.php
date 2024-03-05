<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManajemenUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function json()
    {
        $users = User::select(['id', 'name', 'email', 'role', 'status']);
        $index = 1;
        return DataTables::of($users)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Menambahkan nomor urutan baris
            })
            ->addColumn('action', function ($row) {
                $editUrl = url('/dashboardSuperadmin/Users/edit/' . $row->id);
                $deleteUrl = url('/dashboardSuperadmin/Users/destroy/' . $row->id);

                return '<a href="' . $editUrl . '">Edit</a> | <a href="#" class="delete-users" data-url="' . $deleteUrl . '">Delete</a>';
            })
            ->toJson();
    }
    public function index()
    {
        return view('Superadmin.ManajemenUsers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Superadmin.ManajemenUsers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        // Enkripsi password
        $encryptedPassword = bcrypt($request->password);

        // Buat user dengan password yang dienkripsi
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $encryptedPassword,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect('/dashboardSuperadmin/Users')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::find($id);
        return view('Superadmin.ManajemenUsers.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     // Ambil data user berdasarkan ID
    //     $user = User::findOrFail($id);

    //     // Validasi input
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email,' . $id,
    //         'password' => 'nullable',
    //         'role' => 'required',
    //         'status' => 'required',
    //     ]);

    //     // Jika pengguna yang sedang login sedang mengedit data mereka sendiri,
    //     // peran akan tetap sama seperti sebelumnya
    //     if ($user->id === Auth::id()) {
    //         // Periksa apakah pengguna mengubah email atau password
    //         $changedEmail = $request->email !== $user->email;
    //         $changedPassword = $request->filled('password');

    //         // Update data user
    //         $user->update([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
    //             'role' => 'superadmin',
    //             'status' => 'aktif',
    //         ]);

    //         // Jika pengguna mengubah email atau password, logout setelah 5 detik
    //         if ($changedEmail || $changedPassword) {
    //             return redirect('/dashboardSuperadmin/Users')
    //                 ->with('info', 'User updated successfully. Logging out in 5 seconds...');
    //         }
    //     } else {
    //         // Update data user
    //         $user->update([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
    //             'role' => $request->role,
    //             'status' => $request->status,
    //         ]);
    //     }

    //     // Redirect dengan pesan sukses
    //     return redirect('/dashboardSuperadmin/Users ')->with('success', 'User updated successfully');
    // }
    public function update(Request $request, string $id)
    {
        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([         
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'role' => 'required',
            'status' => 'required',
        ]);

        // Jika pengguna yang sedang login sedang mengedit data mereka sendiri,
        // peran akan tetap sama seperti sebelumnya
        if ($user->id === Auth::id()) {
            // Periksa apakah pengguna mengubah email atau password
            $changedEmail = $request->email !== $user->email;
            $changedPassword = $request->filled('password');
            // Periksa apakah password yang dimasukkan sesuai dengan yang ada di database setelah pembaruan
            $passwordMatch = $changedPassword ? Hash::check($request->password, $user->password) : true;

            if ($changedPassword && $passwordMatch) {
                return redirect('/dashboardSuperadmin/Users')->with('error', 'User update failed because the new password is the same as the current password.');
            }

            // Update data user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                // Jika password yang dimasukkan tidak kosong, enkripsi password baru
                'password' => $changedPassword ? bcrypt($request->password) : $user->password,
                'role' => 'superadmin',
                'status' => 'aktif',
            ]);
            // Jika pengguna mengubah email atau password, logout setelah 5 detik
            if ($changedEmail || $changedPassword) {
                return redirect('/dashboardSuperadmin/Users')->with('info', 'User updated successfully. Logging out in 5 seconds...');
            }
        } else {
            // Periksa apakah ada perubahan pada password pengguna
            $changedPassword = $request->filled('password');

            // Periksa apakah password yang dimasukkan sesuai dengan yang ada di database setelah pembaruan
            $passwordMatch = $changedPassword ? Hash::check($request->password, $user->password) : true;

            // Jika password yang dimasukkan sama dengan yang ada di database
            if ($changedPassword && $passwordMatch) {
                return redirect('/dashboardSuperadmin/Users')->with('error', 'User update failed because the new password is the same as the current password.');
            }

            // Update data user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                // Jika password yang dimasukkan tidak kosong, enkripsi password baru
                'password' => $changedPassword ? bcrypt($request->password) : $user->password,
                'role' => $request->role,
                'status' => $request->status,
            ]);
        }

        return redirect('/dashboardSuperadmin/Users')->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return response()->json([
                'warning' => 'You cannot delete your own account because you are currently logged in as ' . Auth::user()->name . '.'
            ]);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
