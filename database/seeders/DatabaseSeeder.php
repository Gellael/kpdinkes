<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEEDER ADMIN
        User::create([
            'name' => 'Admin Dinkes Provinsi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. SEEDER PUSKESMAS (Nama User = Nama Puskesmas)
        User::create([
            'name' => 'Puskesmas Lingkar Barat', // <--- Nama Instansi
            'email' => 'puskesmaslingkarbarat@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'puskesmas',
            'unit_kerja' => 'Puskesmas Lingkar Barat',
            'kabupaten' => 'Kota Bengkulu',
            'no_hp' => '081122334455',
        ]);

        User::create([
            'name' => 'Puskesmas Perawatan Betungan', // <--- Nama Instansi
            'email' => 'puskesmasbetungan@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'puskesmas',
            'unit_kerja' => 'Puskesmas Perawatan Betungan',
            'kabupaten' => 'Kota Bengkulu',
            'no_hp' => '081155667788',
        ]);

        // 3. SEEDER AMBULAN
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'driver.budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'ambulan',
            'kabupaten' => 'Kota Bengkulu',
            'kecamatan' => 'Ratu Samban',
            'unit_kerja' => 'Kelurahan Anggut',
            'nama_kepala_desa' => 'H. Ahmad Muzaki',
            'no_hp' => '085267112233',
        ]);
    }
}