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
        Schema::create('bpjs_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_warga');
            $table->string('nik', 16)->unique(); // NIK wajib 16 digit & unik
            $table->text('alamat');
            $table->string('status_verifikasi')->default('pending'); // pending, acc, tolak
            $table->foreignId('user_id')->constrained('users'); // Siapa petugas yg input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_data');
    }
};
