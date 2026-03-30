<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// HAPUS baris Sanctum di sini karena bikin error dan tidak kita pakai

class User extends Authenticatable
{
    // HAPUS 'HasApiTokens' dari dalam sini
    use HasFactory, Notifiable;
    public function ambulanceLogs()
{
    // Relasi One-to-Many: Satu supir memiliki banyak log perjalanan
    return $this->hasMany(AmbulanceLog::class, 'driver_id');
}
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nopol',
        'unit_kerja',
        'kecamatan',
        'kabupaten',
        'nama_kepala_desa',
        'no_hp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}