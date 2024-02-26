<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeGen extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'code_datang', 'qr_code_datang', 'code_pulang', 'qr_code_pulang'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
