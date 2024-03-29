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
        $userId = auth()->id();

        $users = Permission::where('user_id', $userId)
            ->select(['id', 'explanation', 'permission_type', 'start_date', 'end_date', 'status'])
            ->get();

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
                $editUrl = url('/dashboardkaryawan/Permission/edit/' . $row->id);
                $deleteUrl = url('/dashboardkaryawan/Permission/destroy/' . $row->id);
                $showUrl = url('/dashboardkaryawan/Permission/show/' . $row->id); // Tautan untuk menampilkan detail izin

                return '<a href="' . $editUrl . '">Edit</a> | <a href="' . $showUrl . '">Show</a> | <a href="#" class="delete-users" data-url="' . $deleteUrl . '">Delete</a>';
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
        // Temukan permission berdasarkan ID
        $permission = Permission::find($id);

        // Periksa apakah permission ditemukan
        if (!$permission) {
            return redirect('/dashboardkaryawan/Permission')->with('error', 'Permission not found.');
        }

        // Periksa apakah pengguna yang mengakses memiliki akses untuk melihat permission
        if ($permission->user_id !== Auth::id()) {
            return redirect('/dashboardkaryawan/Permission')->with('error', 'You are not authorized to view this permission.');
        }

        // Return the view with permission data and calculated period
        return view('Karyawan.Permission.show', [
            'permission' => $permission,
            'period' => $this->calculatePeriod($permission->start_date, $permission->end_date),
        ]);
    }

    private function calculatePeriod($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $period = 0;

        // Iterasi melalui setiap hari dalam rentang tanggal
        for ($date = $start; $date->lte($end); $date->addDay()) {
            // Periksa apakah hari saat ini adalah hari libur (Sabtu atau Minggu)
            if (!$date->isWeekend()) {
                $period++;
            }
        }

        // Return the calculated period
        return $period;
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::find($id);
        if ($permission->status === 'approved' || $permission->status === 'rejected') {
            return redirect('/dashboardkaryawan/Permission')->with('error', 'Permission with status approved or rejected cannot be Updated.');
        }
        return view('Karyawan.Permission.edit', compact('permission'));
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

        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        // Periksa apakah pengguna yang mengakses memiliki akses untuk mengedit permission
        if ($permission->user_id !== Auth::id()) {
            return redirect('/dashboardkaryawan/Permission')->with('error', 'You are not authorized to update this permission.');
        }

        // Update permission dengan data baru
        $permission->update($request->all());

        // Mengembalikan response dengan pesan sukses
        return redirect('/dashboardkaryawan/Permission')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);

        // Periksa apakah status permission sudah approved atau rejected
        if ($permission->status === 'approved' || $permission->status === 'rejected') {
            return response()->json(['error' => 'Permission with status approved or rejected cannot be deleted.'], 403);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully.']);
    }
}
