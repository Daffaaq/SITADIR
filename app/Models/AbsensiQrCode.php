<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiQrCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'Qr_code_id',
        'tanggal',
        'waktu_datang_Qr_code',
        'waktu_pulang_Qr_code',
        'qr_code_path_datang',
        'qr_code_path_pulang',
    ];

    /**
     * Get the user that owns the absensi qr code.
     */
    public function qrcode()
    {
        return $this->belongsTo(QrCodeGen::class, 'Qr_code_id'); // Sesuaikan dengan nama foreign key yang digunakan
    }
}
