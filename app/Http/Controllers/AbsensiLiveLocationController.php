<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiLiveLocation;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiLiveLocationController extends Controller
{
    public function json()
    {
        $userId = auth()->id();
        $users = AbsensiLiveLocation::where('user_id', $userId)
            ->select(['id', 'tanggal', 'waktu_datang_LiveLoc', 'waktu_pulang_LiveLoc']); // Retrieve the records using get()
        $index = 1;
        return DataTables::of($users)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Increment the index for each row
            })
            ->addColumn('absensi_id', function ($data) {
                return $data->id; // Use 'id' column as 'absensi_id'
            })
            ->toJson();
    }
    public function index()
    {
        return view('Karyawan.Absensi.LiveLocation.index');
    }

    public function create()
    {
        return view('Karyawan.Absensi.LiveLocation.datang');
    }

    public function storeDatang(Request $request)
    {
        // Validasi request
        $request->validate([
            'longitude_datang' => 'required',
            'latitude_datang' => 'required',
            'letter_of_assignment' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10000', // Atur ukuran maksimal file sesuai kebutuhan
        ]);

        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Simpan data absensi datang dengan tanggal dan waktu saat ini
        AbsensiLiveLocation::create([
            'user_id' => $userId,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_datang_LiveLoc' => Carbon::now()->toTimeString(),
            'longitude_datang' => $request->longitude_datang,
            'latitude_datang' => $request->latitude_datang,
            'letter_of_assignment' => $request->file('letter_of_assignment')->store('letters_of_assignment', 'public'),
        ]);
        return redirect('/dashboardkaryawan/Absensi/LiveLocation')
            ->with('success', 'Attendence live location successfully.');
    }
}
