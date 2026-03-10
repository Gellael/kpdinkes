<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // PERHATIKAN: Nama tabelnya sekarang adalah ambulance_logs
        Schema::table('ambulance_logs', function (Blueprint $table) {
            $table->string('jenis_pelayanan')->nullable()->after('nama_pasien');
        });
    }

    public function down()
    {
        Schema::table('ambulance_logs', function (Blueprint $table) {
            $table->dropColumn('jenis_pelayanan');
        });
    }
};