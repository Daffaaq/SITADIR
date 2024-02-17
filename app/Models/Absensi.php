<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu_datang',
        'waktu_pulang',
        'qr_code_path',
        'longitude',
        'latitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
