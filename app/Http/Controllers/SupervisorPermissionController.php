<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SupervisorPermissionController extends Controller
{
    public function json($userId)
    {
        $attendances = Permission::where('user_id', $userId)
            ->select([
                'id',
                'explanation',
                'permission_type',
                'start_date',
                'end_date',
                'status',
            ])
            ->distinct()
            ->get();

        $index = 1;
        return DataTables::of($attendances)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Menambahkan nomor urutan baris
            })
            ->addColumn('action', function ($row) {
                $accUrl = url('/dashboardsupervisor/Rekap_Permission/accepted/' . $row->id);
                $rejectedUrl = url('/dashboardsupervisor/Rekap_Permission/rejected/' . $row->id);
                $showUrl = url('/dashboardsupervisor/Rekap_Permission/show/' . $row->id);

                return '<a href="' . $accUrl . '">Approve</a> | <a href="' . $rejectedUrl . '">Reject</a> | <a href="' . $showUrl . '">Show</a>';
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

    public function approveindex(string $id)
    {
        $permission = Permission::find($id);
        return view('Supervisor.Permission.Approve', compact('permission'));
    }
    public function rejectindex(string $id)
    {
        $permission = Permission::find($id);
        return view('Supervisor.Permission.rejectede', compact('permission'));
    }

    // public function approve(Request $request, $id)
    // {
    //     // Temukan permission berdasarkan ID
    //     $permission = Permission::findOrFail($id);

    //     if (Auth::user()->role !== 'supervisor') {
    //         return response()->json(['error' => 'You are not authorized to reject permissions.'], 403);
    //     }
    //     // Lakukan validasi terhadap data yang diterima dari form approval
    //     $request->validate([
    //         'supervisor_comment' => 'nullable|string',
    //         'supervisor_letter' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:10000',
    //         // Validasi file
    //     ]);
    //     $path = null;
    //     // Simpan file supervisor_letter
    //     if ($request->hasFile('supervisor_letter')) {
    //         $file = $request->file('supervisor_letter');
    //         $path = $file->store('supervisor_letters', 'public'); // Simpan file ke penyimpanan 'public'
    //     }


    //     // Update data permission dengan informasi dari form approval
    //     $permission->status = 'approved';
    //     $permission->supervisor_comment = $request->input('supervisor_comment');
    //     $permission->supervisor_letter = $path;
    //     $permission->save();

    //     // Mengembalikan respons JSON
    //     return redirect('/dashboardsupervisor/Rekap_Permission')
    //         ->with('success', 'Permission approved successfully.');
    // }

    public function approve(Request $request, $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        if (Auth::user()->role !== 'supervisor') {
            return response()->json(['error' => 'You are not authorized to reject permissions.'], 403);
        }

        // Update status permission menjadi 'approved'
        $permission->status = 'approved';
        $permission->save();

        // Memanggil fungsi generateletter untuk menghasilkan surat supervisor
        $this->generateletter($request, $id);

        // Mengembalikan respons redirect
        return redirect('/dashboardsupervisor/Rekap_Permission')
        ->with('success', 'Permission approved successfully.');
    }

    public function generateletter(Request $request, $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);
        $templatePath = public_path('assets/format/TEMPLATE_SURAT_PERIZINAN.docx');
        $fileName = 'SURAT_SELDIK_' . $permission->id . '.docx';
        $filePath = public_path('assets/result/surat/' . $fileName);
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $nama = $permission->user->name;
        $email = $permission->user->email;
        $explanation = $permission->explanation;
        $permission_type = $permission->permission_type;
        $start_date = $permission->start_date;
        $end_date = $permission->end_date;
        $date = Carbon::now()->format('d-m-Y');
        $status = $permission->status;
        $supervisor_comment = $permission->supervisor_comment;

        $phpWord->setValue('nama', $nama);
        $phpWord->setValue('email', $email);
        $phpWord->setValue('explanation', $explanation);
        $phpWord->setValue('permission_type', $permission_type);
        $phpWord->setValue('start_date', $start_date);
        $phpWord->setValue('end_date', $end_date);
        $phpWord->setValue('date', $date);
        $phpWord->setValue('status', $status);
        $phpWord->setValue('supervisor_comment', $supervisor_comment);

        $phpWord->saveAs($filePath);
        // Mengembalikan respons JSON
        return Response::download($filePath, $fileName);
    }


    public function reject(Request $request, $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::findOrFail($id);

        // Periksa apakah pengguna yang melakukan penolakan adalah supervisor yang sesuai
        // Misalnya, di sini kita menganggap supervisor memiliki role dengan nama 'supervisor'
        if (Auth::user()->role !== 'supervisor') {
            return response()->json(['error' => 'You are not authorized to reject permissions.'], 403);
        }

        // Lakukan validasi terhadap data yang diterima dari form penolakan
        $request->validate([
            'supervisor_comment' => 'nullable|string',
            // 'supervisor_letter' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:10000',
        ]);
        $path = null;
        // Simpan file supervisor_letter
        if ($request->hasFile('supervisor_letter')) {
            $file = $request->file('supervisor_letter');
            $path = $file->store('supervisor_letters', 'public'); // Simpan file ke penyimpanan 'public'
        }

        // Update data permission dengan informasi dari form penolakan
        $permission->status = 'rejected';
        $permission->supervisor_comment = $request->supervisor_comment;
        $permission->supervisor_letter = $path;
        $permission->save();

        // Mengembalikan respons JSON
        return redirect('/dashboardsupervisor/Rekap_Permission')
            ->with('success', 'Permission rejected successfully.');
    }

    public function show(string $id)
    {
        // Temukan permission berdasarkan ID
        $permission = Permission::find($id);

        // Periksa apakah permission ditemukan
        if (!$permission) {
            return redirect('/dashboardkaryawan/Permission')->with('error', 'Permission not found.');
        }

        // Hitung periode
        $period = $this->calculatePeriod($permission->start_date, $permission->end_date);

        // Kembalikan view dengan data permission dan periode
        return view('Supervisor.Permission.show', compact('permission', 'period'));
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
}
