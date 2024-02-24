<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Models\AbsensiLiveLocation;
use Illuminate\Support\Facades\Auth;

class LaporanAbsensiController extends Controller
{
    public function index()
    {
        return view('Karyawan.Laporan.index');
    }

    public function json()
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        $absensi = Absensi::where('user_id', $userId)
            ->select(['id', 'tanggal', 'waktu_datang', 'waktu_pulang']); // Retrieve records from Absensi model

        $absensiLive = AbsensiLiveLocation::where('user_id', $userId)
            ->select(['id', 'tanggal', 'waktu_datang_LiveLoc as waktu_datang', 'waktu_pulang_LiveLoc as waktu_pulang']); // Retrieve records from AbsensiLiveLocation model

        $combinedData = $absensi->union($absensiLive); // Combine the results

        $index = 1; // Initialize index counter

        return DataTables::of($combinedData)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Increment the index for each row
            })
            ->toJson();
    }
}
