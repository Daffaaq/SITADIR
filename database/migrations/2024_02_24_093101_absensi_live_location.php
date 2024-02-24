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
        Schema::create('absensi_live_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('tanggal');
            $table->time('waktu_datang')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->time('waktu_datang_LiveLoc')->nullable();
            $table->time('waktu_pulang_LiveLoc')->nullable();
            $table->string('longitude_datang')->nullable();
            $table->string('latitude_datang')->nullable();
            $table->string('longitude_pulang')->nullable();
            $table->string('latitude_pulang')->nullable();
            $table->string('letter_of_assignment')->nullable();
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
