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
        // Nama tabel sudah disesuaikan menjadi 'bpjs_data'
        Schema::table('bpjs_data', function (Blueprint $table) {
            $table->string('foto_sktm')->nullable()->after('foto_kk');
            $table->string('foto_rawat')->nullable()->after('foto_sktm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bpjs_data', function (Blueprint $table) {
            $table->dropColumn(['foto_sktm', 'foto_rawat']);
        });
    }
};