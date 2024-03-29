<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiLiveLocation;
use App\Models\Absensi;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            ->addColumn('action', function ($row) {
                $editUrl = url('/dashboardkaryawan/Absensi/LiveLocation/pulang/' . $row->id);
                $showUrl = url('/dashboardkaryawan/Absensi/LiveLocation/show/' . $row->id);
                $updateletterUrl = url('/dashboardkaryawan/Absensi/LiveLocation/edit/letter/' . $row->id);
                return '<a href="' . $editUrl . '">Pulang</a> | <a href="' . $showUrl . '">Detail</a> | <a href="' . $updateletterUrl . '">Update Letter</a>';
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
            'longitude_datang_real' => 'required',
            'latitude_datang_real' => 'required',
            'letter_of_assignment' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10000', // Atur ukuran maksimal file sesuai kebutuhan
        ]);

        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        $existingLiveLocationAbsensi = AbsensiLiveLocation::where('user_id', $userId)
            ->whereDate('tanggal', Carbon::now()->toDateString())
            ->exists();

        // Jika pengguna telah melakukan absensi melalui lokasi langsung, kembalikan dengan pesan kesalahan
        if ($existingLiveLocationAbsensi) {
            return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'Anda telah melakukan absensi datang via live location hari ini.Sistem kami tidak memungkinkan Anda untuk melakukan absensi pulang lebih dari sekali dalam satu hari. Terimakasih');
        }

        $existingManualAbsensi = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', Carbon::now()->toDateString())
            ->exists();

        // Jika pengguna telah melakukan absensi manual, kembalikan dengan pesan kesalahan
        if ($existingManualAbsensi) {
            return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'Anda telah melakukan absensi datang manual hari ini. Tidak dapat melakukan absensi via live location. Terimakasih');
        }
        // Simpan data absensi datang dengan tanggal dan waktu saat ini
        AbsensiLiveLocation::create([
            'user_id' => $userId,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_datang_LiveLoc' => Carbon::now()->toTimeString(),
            'longitude_datang' => $request->longitude_datang,
            'latitude_datang' => $request->latitude_datang,
            'longitude_datang_real' => $request->longitude_datang_real,
            'latitude_datang_real' => $request->latitude_datang_real,
            'letter_of_assignment' => $request->file('letter_of_assignment')->store('letters_of_assignment', 'public'),
        ]);
        return redirect('/dashboardkaryawan/Absensi/LiveLocation')
            ->with('success', 'Terima kasih telah absen saat kedatangan! Data absensimu via live location sudah disimpan.');
    }

    public function edit_letter(string $id)
    {
        $absensi = AbsensiLiveLocation::find($id);
        return view('Karyawan.Absensi.LiveLocation.edit_letter', compact('absensi'));
    }

    public function update_letter(Request $request, $id)
    {
        $absensi = AbsensiLiveLocation::find($id);

        // Memeriksa apakah file telah di-upload
        if ($request->hasFile('letter_of_assignment')) {
            // Menghapus file yang ada sebelumnya
            Storage::delete($absensi->letter_of_assignment);

            // Menyimpan file yang baru di-upload
            $absensi->update([
                'letter_of_assignment' => $request->file('letter_of_assignment')->store('letters_of_assignment', 'public'),
            ]);

            return redirect('/dashboardkaryawan/Absensi/LiveLocation')
                ->with('success', 'Surat tugas berhasil diperbaharui!');
        } else {
            // Jika file tidak di-upload, kembalikan ke halaman sebelumnya dengan pesan error
            return back()->withErrors(['error' => 'File surat tugas tidak di-upload.']);
        }
    }
    public function edit(string $id)
    {
        $absensi = AbsensiLiveLocation::find($id);
        return view('Karyawan.Absensi.LiveLocation.pulang', compact('absensi'));
    }
    public function storePulang(Request $request, $id)
    {
        $absensi = AbsensiLiveLocation::findOrFail($id);

        $existingPulangAbsensi = AbsensiLiveLocation::where('user_id', $absensi->user_id)
            ->whereDate('tanggal', Carbon::now()->toDateString())
            ->whereNotNull('waktu_pulang_LiveLoc')
            ->exists();

        // Jika sudah ada absensi pulang pada tanggal yang sama, kembalikan dengan pesan kesalahan
        if ($existingPulangAbsensi) {
            return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'Mohon maaf, Anda telah melakukan absensi pulang pada hari ini. Sistem kami tidak memungkinkan Anda untuk melakukan absensi pulang lebih dari sekali dalam satu hari. Selain itu, Anda juga tidak dapat mengubah waktu absensi pulang yang telah dilakukan. Terimakasih');
        }

        $request->validate([
            'longitude_pulang' => 'required',
            'latitude_pulang' => 'required',
            'longitude_pulang_real' => 'required',
            'latitude_pulang_real' => 'required',
        ]);
        $tanggalAbsensiDatang = Carbon::parse($absensi->tanggal);
        $tanggalAbsensiPulang = Carbon::now(); // Misalkan ini adalah waktu absensi pulang, ganti dengan waktu yang sesuai dengan aplikasi Anda

        if ($tanggalAbsensiPulang->toDateString() != $tanggalAbsensiDatang->toDateString()) {
            // Jika tanggal absensi pulang tidak sama dengan tanggal absensi datang,
            // beri respon dengan pesan error
            return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'Mohon maaf, Anda tidak dapat submit absensi pulang setelah atau pada tanggal absensi datang. Terimakasih.');
        }
        // Perbarui waktu pulang dengan waktu saat ini
        $absensi->update([
            'waktu_pulang_LiveLoc' => Carbon::now()->toTimeString(),
            'longitude_pulang' => $request->longitude_pulang,
            'latitude_pulang' => $request->latitude_pulang,
            'longitude_pulang_real' => $request->longitude_pulang_real,
            'latitude_pulang_real' => $request->latitude_pulang_real,
        ]);

        return redirect('/dashboardkaryawan/Absensi/LiveLocation')
            ->with('success', 'Terima kasih telah absen saat Kepulangan! Data absensimu via live location sudah disimpan.');
    }

    public function show($id)
    {
        $absensi = AbsensiLiveLocation::findOrFail($id);

        // Pastikan bahwa absensi dimiliki oleh pengguna yang sedang login
        if ($absensi->user_id !== Auth::id()) {
            return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'Anda tidak memiliki izin untuk mengakses absensi ini.');
        }

        return view('Karyawan.Absensi.LiveLocation.show', compact('absensi'));
    }
}
