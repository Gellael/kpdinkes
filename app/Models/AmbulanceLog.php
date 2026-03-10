<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbulanceLog extends Model
{
    protected $fillable = [
    'driver_id',
    'nama_pasien',
    'jenis_pelayanan',
    'lokasi_jemput',
    'tujuan',
    'waktu_berangkat',
    'status',
    'foto_ktp',
];
    public function driver()
{
    return $this->belongsTo(User::class, 'driver_id');
}
}
