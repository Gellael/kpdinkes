<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('ambulance_logs', function (Blueprint $table) {
        // Ubah kolom jadi nullable (boleh kosong saat awal jalan)
        $table->string('nama_pasien')->nullable()->change();
        $table->string('lokasi_jemput')->nullable()->change();
        $table->string('tujuan')->nullable()->change();
        $table->string('foto_ktp')->nullable()->change();
    });
}

public function down(): void
{
    // Kembalikan ke tidak boleh kosong (rollback)
    Schema::table('ambulance_logs', function (Blueprint $table) {
        $table->string('nama_pasien')->nullable(false)->change();
        // ... dan seterusnya
    });
}
};
