<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\kirimEmail;


class QRCodeController extends Controller
{

    public function generateAndSendQrCode(Request $request)
    {
        $qrCodeText = "Your QR code content here"; // Gantilah dengan konten QR code yang diinginkan

        // Generate QR code image
        $path = public_path('qrcodes/');
        $filename = 'test.png'; // Sesuaikan nama file jika diperlukan
        $qrImagePath = $path . $filename;

        // Menggunakan Simple QrCode untuk membuat QR code
        QrCode::format('png')->size(200)->generate($qrCodeText, $qrImagePath);

        // Example email address, ganti dengan alamat email penerima
        $recipientEmail = 'example@gmail.com';

        // Kirim email dengan QR code sebagai lampiran
        Mail::to($recipientEmail)->send(new kirimEmail($qrImagePath));

        return response()->json(['message' => 'QR code sent to the specified email']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
