<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\User;

class SupervisorPermissionController extends Controller
{
    public function json($userId)
    {
        $attendances = Permission::where('user_id', $userId)
            ->select([
                'explanation',
                'permission_type',
                'start_date',
                'end_date',
                'status',
            ])
            ->get();
        $index = 1;
        return DataTables::of($attendances)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Menambahkan nomor urutan baris
            })
            ->addColumn('action', function ($attendance) {
                // You can add additional columns or actions here
                return '<button class="btn btn-sm btn-info" onclick="deleteAttendance(' . $attendance->id . ')">Delete</button>';
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
            ->make(true);
    }
    public function index()
    {
        // Ambil semua user yang memiliki role 'pegawai'
        $karyawanUsers = User::where('role', 'karyawan')->get();

        return view('Supervisor.Permission.index', ['karyawanUsers' => $karyawanUsers]);
    }
    public function approve(Request $request, $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        // Periksa apakah pengguna yang melakukan approval adalah supervisor yang sesuai
        // Misalnya, di sini kita menganggap supervisor memiliki role dengan nama 'supervisor'
        if (!$request->user()->hasRole('supervisor')) {
            return redirect()->back()->with('error', 'You are not authorized to approve permissions.');
        }

        // Lakukan validasi terhadap data yang diterima dari form approval
        $request->validate([
            'supervisor_comment' => 'nullable|string',
            'supervisor_letter' => 'nullable|string',
        ]);

        // Update data permission dengan informasi dari form approval
        $permission->update([
            'status' => 'approved',
            'supervisor_comment' => $request->supervisor_comment,
            'supervisor_letter' => $request->supervisor_letter,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Permission approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        // Periksa apakah pengguna yang melakukan penolakan adalah supervisor yang sesuai
        // Misalnya, di sini kita menganggap supervisor memiliki role dengan nama 'supervisor'
        if (!$request->user()->hasRole('supervisor')) {
            return redirect()->back()->with('error', 'You are not authorized to reject permissions.');
        }

        // Lakukan validasi terhadap data yang diterima dari form penolakan
        $request->validate([
            'supervisor_comment' => 'nullable|string',
            'supervisor_letter' => 'nullable|string',
        ]);

        // Update data permission dengan informasi dari form penolakan
        $permission->update([
            'status' => 'rejected',
            'supervisor_comment' => $request->supervisor_comment,
            'supervisor_letter' => $request->supervisor_letter,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Permission rejected successfully.');
    }
}
