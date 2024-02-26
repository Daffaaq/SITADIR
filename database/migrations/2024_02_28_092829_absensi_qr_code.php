<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Qr_code_id');
            $table->foreign('Qr_code_id')->references('id')->on('qr_code_gens');
            $table->date('tanggal');
            $table->time('waktu_datang_Qr_code')->nullable();
            $table->time('waktu_pulang_Qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
