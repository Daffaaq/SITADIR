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
        Schema::create('qr_code_gens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('tanggal_kirim_datang')->nullable();
            $table->time('jam_kirim_datang')->nullable();
            $table->date('tanggal_kirim_pulang')->nullable();
            $table->time('jam_kirim_pulang')->nullable();
            $table->string('code_datang')->unique();
            $table->string('qr_code_datang')->nullable();
            $table->string('code_pulang')->unique()->nullable();
            $table->string('qr_code_pulang')->nullable();
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
