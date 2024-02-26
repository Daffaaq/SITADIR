<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiLiveLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu_datang_LiveLoc',
        'waktu_pulang_LiveLoc',
        'longitude_datang_real',
        'latitude_datang_real',
        'longitude_pulang_real',
        'latitude_pulang_real',
        'longitude_datang',
        'latitude_datang',
        'longitude_pulang',
        'latitude_pulang',
        'letter_of_assignment',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
