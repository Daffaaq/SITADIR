<?php

namespace App\Http\Controllers;

use App\Models\QrCodeGen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class SendQrCodeController extends Controller
{
    public function json()
    {
        $users = User::select(['id', 'name', 'email', 'status'])
            ->where('role', 'karyawan')
            ->get();
        $index = 1;
        return DataTables::of($users)
            ->addColumn('DT_RowIndex', function ($data) use (&$index) {
                return $index++; // Menambahkan nomor urutan baris
            })
            ->addColumn('action', function ($row) {
                $sendQrCodeUrlDatang = url('/dashboardSuperadmin/send-qr-code-to-karyawan/send/datang/' . $row->id);
                $sendQrCodeUrlPulang = url('/dashboardSuperadmin/send-qr-code-to-karyawan/send/pulang/' . $row->id);

                return '<a href="#" class="send-qr-code_datang" data-url="' . $sendQrCodeUrlDatang . '">Send Qr Code Datang</a>' .
                    ' | ' .
                    '<a href="#" class="send-qr-code_pulang" data-url="' . $sendQrCodeUrlPulang . '">Send Qr Code Pulang</a>';
            })
            ->toJson();
    }
    public function index()
    {
        return view('Superadmin.Qr_Code.index');
    }
    public function sendQrCodeToUserDatang(string $id)
    {
        // Temukan pengguna dengan ID yang diberikan
        $user = User::findOrFail($id);

        // Pastikan pengguna ditemukan dan memiliki peran 'karyawan'
        if ($user && $user->role === 'karyawan') {
            // Hapus QR code datang yang lama jika ada
            // $existingQrCode = QrCode::where('user_id', $user->id)->where('code', 'datang')->first();
            // if ($existingQrCode) {
            //     Storage::delete($existingQrCode->qr_code);
            //     $existingQrCode->delete();
            // }

            // Generate kode unik untuk QR code
            // $code = 'ID' . $user->id . '_' . Str::slug($user->name) . '_' . Str::slug($user->email) . '_' . Str::random(3);
            $code = 'ATTDN' . Str::random(6);

            // Generate QR Code datang dengan informasi yang sesuai (misalnya, kode unik)
            $qrCodeData = $code;
            $qrCode = QrCode::format('png')->size(200)->generate($qrCodeData);

            // Simpan QR Code datang ke dalam penyimpanan yang dapat diakses oleh pengguna
            $qrCodePathDatang = 'qrcodes/' . $qrCodeData . '.png';
            Storage::disk('public')->put($qrCodePathDatang, $qrCode);

            // Simpan informasi QR code ke dalam database
            QrCodeGen::create([
                'user_id' => $user->id,
                'tanggal_kirim_datang' => now()->toDateString(),
                'jam_kirim_datang' => now()->toTimeString(),
                'code_datang' => $qrCodeData,
                'qr_code_datang' => $qrCodePathDatang,
            ]);

            // Jika QR Code berhasil dikirim, kembalikan respons sukses dalam format JSON
            return response()->json(['message' => 'QR Code Datang berhasil dikirim ke ' . $user->name]);
        }

        // Jika pengguna tidak ditemukan atau bukan karyawan, kembalikan respons gagal dalam format JSON
        return response()->json(['error' => 'Gagal mengirim QR Code Datang. Pengguna tidak ditemukan atau bukan karyawan.'], 404);
    }


    public function sendQrCodeToUserPulang(string $id)
    {
        // Temukan pengguna dengan ID yang diberikan
        $qrCodeGen = QrCodeGen::findOrFail($id);

        // Pastikan pengguna ditemukan dan memiliki peran 'karyawan'
        if ($qrCodeGen && $qrCodeGen->user->role === 'karyawan') {
            // Generate kode unik untuk QR code
            $code = 'ATTDNP' . Str::random(6);

            // Generate QR Code pulang dengan informasi yang sesuai (misalnya, kode unik)
            $qrCodeData = $code;
            $qrCode = QrCode::format('png')->size(200)->generate($qrCodeData);

            // Simpan QR Code pulang ke dalam penyimpanan yang dapat diakses oleh pengguna
            $qrCodePathPulang = 'qrcodes/' . $qrCodeData . '.png';
            Storage::disk('public')->put($qrCodePathPulang, $qrCode);

            // Perbarui informasi QR code di database jika sudah ada, jika tidak, buat entri baru
            if ($qrCodeGen) {
                $qrCodeGen->update([
                    'tanggal_kirim_pulang' => now()->toDateString(),
                    'jam_kirim_pulang' => now()->toTimeString(),
                    'code_pulang' => $qrCodeData,
                    'qr_code_pulang' => $qrCodePathPulang,
                ]);
            } else {
                QrCodeGen::create([
                    'user_id' => $qrCodeGen->user_id,
                    'tanggal_kirim_datang' => now()->toDateString(),
                    'jam_kirim_datang' => now()->toTimeString(),
                    'code_pulang' => $qrCodeData,
                    'qr_code_pulang' => $qrCodePathPulang,
                ]);
            }

            // Jika QR Code berhasil dikirim, kembalikan respons sukses dalam format JSON
            return response()->json(['message' => 'QR Code Pulang berhasil dikirim ke ' . $qrCodeGen->user->name]);
        }

        // Jika pengguna tidak ditemukan atau bukan karyawan, kembalikan respons gagal dalam format JSON
        return response()->json(['error' => 'Gagal mengirim QR Code Pulang. Pengguna tidak ditemukan atau bukan karyawan.'], 404);
    }




    // public function indexKaryawan()
    // {
    //     $user = auth()->user();
    //     $today = now()->format('Y-m-d');
    //     // Tentukan jenis absensi berdasarkan request pengguna
    //     $jenisAbsensi = request()->segment(3); // Mendapatkan segment URL ke-3

    //     // Tentukan path QR Code berdasarkan jenis absensi
    //     if ($jenisAbsensi == 'datang') {
    //         $qrCodePath = 'qrcodes/' . $user->id . '_qr_code_datang_' . $today . '.png';
    //     } elseif ($jenisAbsensi == 'pulang') {
    //         $qrCodePath = 'qrcodes/' . $user->id . '_qr_code_pulang_' . $today . '.png';
    //     } else {
    //         // Default: absensi datang
    //         $qrCodePath = 'qrcodes/' . $user->id . '_qr_code_datang.png';
    //     }

    //     return view('Karyawan.Qr_Code.index', ['qrCodePath' => $qrCodePath]);
    // }
    public function indexKaryawan()
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');
        $qrCode = QrCodeGen::where('tanggal_kirim_datang', $today)
            ->where('tanggal_kirim_pulang', $today)
            ->where('user_id', $user->id)
            ->first();
        // dd($qrcode);
        // $qrCodes = QrCodeGen::where('user_id', $user->id)->first();
        // dd($qrCodes);
        // Kembalikan kedua path QR Code ke tampilan
        // return view('Karyawan.Qr_Code.index', ['prima' => $qrCodes]); //manipulasi variabel ke view
        return view('Karyawan.Qr_Code.index', compact('qrCode'));
    }

    // public function indexKaryawan()
    // {
    //     $user = auth()->user();

    //     // Path untuk QR Code kedatangan
    //     $qrCodePathDatang = 'qrcodes/' . $user->id . '_qr_code_datang_' . now()->format('Y-m-d') . '.png';

    //     // Path untuk QR Code pulang
    //     $qrCodePathPulang = 'qrcodes/' . $user->id . '_qr_code_pulang_' . now()->format('Y-m-d') . '.png';

    //     // Periksa apakah kedua QR Code tersedia
    //     $qrCodeDatangExists = Storage::exists($qrCodePathDatang);
    //     $qrCodePulangExists = Storage::exists($qrCodePathPulang);

    //     // Kembalikan kedua path QR Code ke tampilan, serta informasi apakah QR Code ada atau tidak
    //     return view('Karyawan.Qr_Code.index', [
    //         'qrCodePathDatang' => $qrCodePathDatang,
    //         'qrCodePathPulang' => $qrCodePathPulang,
    //         'qrCodeDatangExists' => $qrCodeDatangExists,
    //         'qrCodePulangExists' => $qrCodePulangExists
    //     ]);
    // }
}
