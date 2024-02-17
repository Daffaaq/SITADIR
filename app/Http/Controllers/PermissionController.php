<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function json()
    {
        $users = Permission::select(['id', 'explanation', 'permission_type', 'start_date', 'end_date', 'status']);
        $index = 1;
        return DataTables::of($users)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Menambahkan nomor urutan baris
            })
            ->addColumn('period', function ($data) {
                // Menghitung periode dari end_date dikurangi start_date
                $start = Carbon::parse($data->start_date);
                $end = Carbon::parse($data->end_date);
                $period = 0;

                // Iterasi melalui setiap hari dalam rentang tanggal
                for ($date = $start; $date->lte($end); $date->addDay()) {
                    // Periksa apakah hari saat ini adalah hari libur (Sabtu atau Minggu)
                    if (!$date->isWeekend()) {
                        $period++;
                    }
                }
                return $period;
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
        return view('Karyawan.Permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Karyawan.Permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'explanation' => 'required',
            'permission_type' => 'required',
            'start_date' => ['required', 'date', function ($attribute, $value, $fail) {
                // Periksa apakah tanggal mulai jatuh pada hari Sabtu atau Minggu
                $startDate = Carbon::parse($value);
                if ($startDate->isWeekend()) {
                    $fail('The start date cannot be on a weekend.');
                }
            }],
            'end_date' => ['required', 'date', 'after:start_date', function ($attribute, $value, $fail) use ($request) {
                // Periksa apakah tanggal akhir jatuh pada hari Sabtu atau Minggu
                $endDate = Carbon::parse($value);
                if ($endDate->isWeekend()) {
                    $fail('The end date cannot be on a weekend.');
                }
                // Periksa apakah tanggal akhir sama dengan tanggal mulai
                if ($request->start_date === $value) {
                    $fail('The end date cannot be the same as the start date.');
                }
            }],
        ]);

        // Mendapatkan ID pengguna yang sedang diautentikasi
        $user_id = Auth::id();

        // Menyimpan permission baru ke database dengan user_id dari pengguna yang diautentikasi
        Permission::create([
            'user_id' => $user_id,
            'explanation' => $request->explanation,
            'permission_type' => $request->permission_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', // Atur status menjadi 'pending' secara default
        ]);

        // Mengembalikan response dengan pesan sukses
        return redirect('/dashboardkaryawan/Permission')->with('success', 'Permission created successfully.');
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
        $permission = Permission::find($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data input
        $request->validate([
            'explanation' => 'required',
            'permission_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        // Periksa apakah pengguna yang mengakses memiliki akses untuk mengedit permission
        if ($permission->user_id !== Auth::id()) {
            return redirect()->route('permissions.index')->with('error', 'You are not authorized to update this permission.');
        }

        // Update permission dengan data baru
        $permission->update($request->all());

        // Mengembalikan response dengan pesan sukses
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully.']);
    }
}
