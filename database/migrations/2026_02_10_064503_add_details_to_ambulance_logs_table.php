<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('ambulance_logs', function (Blueprint $table) {
        // Kita tambah kolom baru
        $table->string('lokasi_jemput')->after('nama_pasien')->nullable();
        $table->string('foto_ktp')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('ambulance_logs', function (Blueprint $table) {
        $table->dropColumn(['lokasi_jemput', 'foto_ktp']);
    });
}
};
