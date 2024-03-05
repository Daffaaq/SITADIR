@extends('Karyawan.layouts.index')
@section('container')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">QR Code Kedatangan</h6>
        </div>
        <div class="card-body">
                @php
                    $qrCodePathDatang = 'storage/qrcode/' . $qrCode->code . '_qr_code_datang.png';
                @endphp
                <img src="{{ asset('storage/' . $qrCode->qr_code_datang) }}" alt="QR Code Kedatangan">
        </div>
        <div class="card-footer">
                <a href="{{ asset('storage/' . $qrCode->qr_code_datang) }}" download>Download QR Code Kedatangan</a>
        </div>
        <div class="card-body">
                @php
                    $qrCodePathDatang = 'storage/qrcode/' . $qrCode->code . '_qr_code_datang.png';
                @endphp
                <img src="{{ asset('storage/' . $qrCode->qr_code_pulang) }}" alt="QR Code Kedatangan">
        </div>
        <div class="card-footer">
                <a href="{{ asset('storage/' . $qrCode->qr_code_pulang) }}" download>Download QR Code Kedatangan</a>
        </div>
    </div>
@endsection

{{-- {{ $qrcode->tanggal_kirim_datang }} --}}
