<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiManualController extends Controller
{
    public function json()
    {
        $userId = auth()->id();
        $users = Absensi::where('user_id', $userId)
            ->select(['id', 'tanggal', 'waktu_datang', 'waktu_pulang']); // Retrieve the records using get()
        // $users->transform(function ($item) {
        //     // Jika waktu_pulang null, ganti dengan "belum absensi"
        //     if (is_null($item->waktu_pulang)) {
        //         $item->waktu_pulang = 'belum absensi';
        //     }
        //     return $item;
        // });
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
        $absensi = Absensi::all(); // Sesuaikan dengan cara Anda mengambil data absensi

        // Kirim data absensi ke tampilan
        return view('Karyawan.Absensi.Manual.index', ['absensi' => $absensi]);
    }

    public function create()
    {
        return view('Karyawan.Absensi.Manual.create');
    }

    public function storeDatang()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Simpan data absensi datang dengan tanggal dan waktu saat ini
        Absensi::create([
            'user_id' => $userId,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_datang' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Absensi datang berhasil disimpan.');
    }
    public function storePulang(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $tanggalAbsensiDatang = Carbon::parse($absensi->tanggal);
        $tanggalAbsensiPulang = Carbon::now(); // Misalkan ini adalah waktu absensi pulang, ganti dengan waktu yang sesuai dengan aplikasi Anda

        if ($tanggalAbsensiPulang->toDateString() != $tanggalAbsensiDatang->toDateString()) {
            // Jika tanggal absensi pulang tidak sama dengan tanggal absensi datang,
            // beri respon dengan pesan error
            return response()->json(['error' => 'Maaf, Anda tidak dapat submit absensi pulang setelah atau pada tanggal absensi datang.'], 422);
        }
        // Perbarui waktu pulang dengan waktu saat ini
        $absensi->update([
            'waktu_pulang' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['success' => 'Absensi pulang berhasil disimpan.'], 200);
    }
}
