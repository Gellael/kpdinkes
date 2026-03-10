<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsData extends Model
{
    protected $table = 'bpjs_data';
    protected $fillable = [
    'nama_warga',
    'nik',
    'alamat',
    'no_hp',     
    'foto_ktp',  
    'foto_kk',   
    'status_verifikasi',
    'user_id'
];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
