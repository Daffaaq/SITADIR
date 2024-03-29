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
        $absensiQrCodes = AbsensiQrCode::whereHas('qrcode', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->select(['id', 'tanggal', 'waktu_datang_Qr_code', 'waktu_pulang_Qr_code'])
            ->get();

        $index = 1;
        return DataTables::of($absensiQrCodes)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Increment the index for each row
            })
            ->addColumn('action', function ($row) {
                $editUrl = url('/dashboardkaryawan/Absensi/QrCode/pulang/' . $row->id);
                return '<a href="' . $editUrl . '">Pulang</a>';
            })
            ->addColumn('absensi_id', function ($data) {
                return $data->id; // Use 'id' column as 'absensi_id'
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function index()
    {
        $absensi = AbsensiQrCode::all(); // Sesuaikan dengan cara Anda mengambil data absensi

        // Kirim data absensi ke tampilan
        return view('Karyawan.Absensi.Qr_Code.index', ['absensi' => $absensi]);
    }
    public function index1()
    {
        $absensi = AbsensiQrCode::all(); // Sesuaikan dengan cara Anda mengambil data absensi

        // Kirim data absensi ke tampilan
        return view('Karyawan.Absensi.Qr_Code1.index', ['absensi' => $absensi]);
    }


    public function datang()
    {
        return view('Karyawan.Absensi.Qr_Code.datang');
    }
    public function datang1()
    {
        return view('Karyawan.Absensi.Qr_Code1.datang');
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
            return response()->json(['error' => 'Qr Code not found'], 400);
        } // Mendapatkan data dari QR code yang discan

        $qrCodeScan = AbsensiQrCode::where('Qr_code_id', $qrCodes->id)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        if ($qrCodeScan) {
            // return redirect('/dashboardkaryawan/Absensi/LiveLocation')->with('error', 'QR Code sudah discan sebelumnya.');
            return response()->json(['message' => 'QR Code sudah discan sebelumnya.'], 400);
        }
        AbsensiQrCode::create([
            'Qr_code_id' => $qrCodes->id,
            'tanggal' => now()->toDateString(),
            'waktu_datang_Qr_code' => now()->toTimeString(),
        ]);

        return response()->json(['success' => 'Absensi berhasil dicatat.'], 200);
    }

    public function pulang(string $id)
    {
        $absensi = AbsensiQrCode::find($id);
        return view('Karyawan.Absensi.Qr_Code.pulang', compact('id', 'absensi'));
    }
    public function scanQrCodePulang(Request $request, $id)
    {
        $absensi = AbsensiQrCode::findOrFail($id);
        if (!$absensi) {
            return response()->json(['message' => 'id not found'], 400);
        }

        $user = auth()->user(); // Mendapatkan informasi pengguna yang melakukan aksi
        $qrCodeData = $request->input('qr_code_pulang'); // Mendapatkan data dari QR code yang discan
        $qrCodes = QrCodeGen::where('code_pulang', $qrCodeData)
            ->where('user_id', $user->id) // Sesuaikan dengan nama kolom yang sesuai dalam tabel QR code
            ->first();
        Log::info('Nilai $qrCodeData: ' . $qrCodeData);
        // $qrCodes = QrCodeGen::where('code_datang', $qrCodeData)->first();
        Log::info('Nilai $qrCodeData 2: ' . $qrCodes->code_pulang);
        if (!$qrCodes) {
            return response()->json(['message' => 'Qr Code not found'], 400);
        } // Mendapatkan data dari QR code yang discan
        // return response()->json(['message' => 'Waktu pulang berhasil diperbarui.']);
        if ($absensi->waktu_pulang_Qr_code === null) {
            // Jika masih null, update waktu_pulang_Qr_code dengan waktu sekarang
            $absensi->update([
                'waktu_pulang_Qr_code' => now()->toTimeString(),
            ]);

            return response()->json(['message' => 'Waktu pulang berhasil diperbarui.']);
        } else {
            // Jika waktu_pulang_Qr_code sudah memiliki nilai, kembalikan pesan kesalahan
            return response()->json(['message' => 'Data waktu pulang sudah ada.'], 400);
        }
    }
}
