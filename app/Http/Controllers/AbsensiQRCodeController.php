<?php

namespace App\Http\Controllers;

use App\Models\AbsensiQrCode;
use App\Models\QrCodeGen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class AbsensiQRCodeController extends Controller
{
    public function json()
    {
        $userId = auth()->id();
        $users = AbsensiQrCode::where('Qr_code_id', $userId)
            ->select(['id', 'tanggal', 'waktu_datang_Qr_code', 'waktu_pulang_Qr_code']); // Retrieve the records using get()
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
        $absensi = AbsensiQrCode::all(); // Sesuaikan dengan cara Anda mengambil data absensi

        // Kirim data absensi ke tampilan
        return view('Karyawan.Absensi.Qr_Code.index', ['absensi' => $absensi]);
    }


    public function datang()
    {
        return view('Karyawan.Absensi.Qr_Code.datang');
    }

    public function scanQrCodeDatang(Request $request)
    {
        $user = auth()->user(); // Mendapatkan informasi pengguna yang melakukan aksi
        $qrCodeData = $request->input('qr_code_datang');
        $qrCodes = QrCodeGen::where('code_datang', $qrCodeData)
            ->where('user_id', $user->id) // Sesuaikan dengan nama kolom yang sesuai dalam tabel QR code
            ->first();
        Log::info('Nilai $qrCodeData: ' . $qrCodeData);
        // $qrCodes = QrCodeGen::where('code_datang', $qrCodeData)->first();
        Log::info('Nilai $qrCodeData 2: ' . $qrCodes->code_datang);
        if (!$qrCodes) {
            return response()->json(['message' => 'Qr Code not found'], 400);
        } // Mendapatkan data dari QR code yang discan
        AbsensiQrCode::create([
            'Qr_code_id' => $qrCodes->id,
            'tanggal' => now()->toDateString(),
            'waktu_datang_Qr_code' => now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Absensi berhasil dicatat.'], 200);
    }
    public function uploadQrCodeDatang(Request $request)
    {
        $user = auth()->user(); // Mendapatkan informasi pengguna yang melakukan aksi
        $uploadedFile = $request->file('qr_code_file'); // Mendapatkan file QR code yang diunggah

        // Lakukan sesuai kebutuhan Anda, misalnya validasi file, dekode QR code, dll.

        // Catat waktu saat QR code diunggah sebagai waktu kedatangan
        AbsensiQrCode::create([
            'user_id' => $user->id,
            'tanggal' => now()->toDateString(),
            'waktu_datang_Qr_code' => now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Absensi berhasil dicatat.'], 200);
    }
    public function scanQrCodePulang(Request $request, $id)
    {
        $absensi = AbsensiQrCode::findOrFail($id);
        $user = auth()->user(); // Mendapatkan informasi pengguna yang melakukan aksi
        $qrCodeData = $request->input('qr_code_data'); // Mendapatkan data dari QR code yang discan

        // Lakukan sesuai kebutuhan Anda, misalnya verifikasi QR code, validasi waktu, dll.

        // Perbarui waktu_pulang_Qr_code untuk entri absensi yang ditemukan
        $absensi->update([
            'waktu_pulang_Qr_code' => now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Waktu pulang berhasil diperbarui.']);
    }

    public function uploadQrCodePulang(Request $request, $id)
    {
        $absensi = AbsensiQrCode::findOrFail($id);
        $user = auth()->user(); // Mendapatkan informasi pengguna yang melakukan aksi
        $uploadedFile = $request->file('qr_code_file'); // Mendapatkan file QR code yang diunggah

        // Lakukan sesuai kebutuhan Anda, misalnya validasi file, dekode QR code, dll.

        // Perbarui waktu_pulang_Qr_code untuk entri absensi yang ditemukan
        $absensi->update([
            'waktu_pulang_Qr_code' => now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Waktu pulang berhasil diperbarui.']);
    }
}
