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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('tanggal');
            $table->time('waktu_datang')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->string('qr_code_path')->nullable(); // Path tempat QR code disimpan
            $table->string('longitude')->nullable(); // Lokasi hidup (latitude dan longitude) jika metode absen adalah 'live location'
            $table->string('latitude')->nullable(); // Lokasi hidup (latitude dan longitude) jika metode absen adalah 'live location'
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
