<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('bpjs_data', function (Blueprint $table) {
        $table->string('no_hp', 15)->after('alamat')->nullable();
        $table->string('foto_ktp')->nullable()->after('status_verifikasi');
        $table->string('foto_kk')->nullable()->after('foto_ktp');
    });
}

public function down(): void
{
    Schema::table('bpjs_data', function (Blueprint $table) {
        $table->dropColumn(['no_hp', 'foto_ktp', 'foto_kk']);
    });
}
};
