<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BpjsData;
use App\Models\AmbulanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// MENGGUNAKAN CLASS ASLI (Penting untuk bypass error Facade)
use Cloudinary\Cloudinary;

class DashboardController extends Controller
{
    /**
     * FUNGSI PEMBANTU (HELPER)
     * Untuk melakukan upload secara manual tanpa bergantung pada config Laravel
     */
    private function uploadKeCloudinary($file, $folder)
    {
        // Masukkan kredensial langsung di sini
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dubmmly9e',
                'api_key'    => '517729831355594',
                'api_secret' => 'WknqfVum-8Mx2wgq0z0d9OZGrys'
            ]
        ]);

        return $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder
        ])['secure_url'];
    }

    // ==========================================
    // 1. BAGIAN ADMIN (PENGELOLA UTAMA)
    // ==========================================

    public function adminIndex() {
        return view('admin.hub');
    }

    public function adminUsers() {
        $admins = User::where('role', 'admin')->latest()->get();
        $puskesmas = User::where('role', 'puskesmas')
                         ->orderBy('kabupaten', 'asc')
                         ->orderBy('name', 'asc')
                         ->get();
        $drivers = User::where('role', 'ambulan')->latest()->get();

        return view('admin.users', compact('admins', 'puskesmas', 'drivers'));
    }

    public function createUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'nopol' => 'nullable|string|max:20', // <-- Validasi NOPOL ditambahkan
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'unit_kerja' => $request->unit_kerja, 
            'kecamatan' => $request->kecamatan,   
            'kabupaten' => $request->kabupaten,   
            'no_hp' => $request->no_hp,
            'nopol' => $request->nopol, // <-- Menyimpan NOPOL ke database
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function editUser($id) {
        $user = User::findOrFail($id);
        return view('admin.users_edit', compact('user'));
    }

    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
            'nopol' => 'nullable|string|max:20', // <-- Validasi NOPOL ditambahkan
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'unit_kerja' => $request->unit_kerja,
            'kecamatan' => $request->kecamatan, 
            'kabupaten' => $request->kabupaten, 
            'no_hp' => $request->no_hp,
            'nopol' => $request->nopol, // <-- Mengupdate NOPOL di database
        ];

        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User diperbarui!');
    }

    public function deleteUser($id) {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User dihapus!');
    }

    public function adminBpjs(Request $request) {
        $query = BpjsData::with('petugas');
        if ($request->filled('kabupaten')) {
            $query->whereHas('petugas', function($q) use ($request) {
                $q->where('kabupaten', $request->kabupaten);
            });
        }
        if ($request->filled('puskesmas_id')) {
            $query->where('user_id', $request->puskesmas_id);
        }
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . " 00:00:00", 
                $request->tanggal_selesai . " 23:59:59"
            ]);
        }
        $data = $query->latest()->get();
        $listPuskesmas = User::where('role', 'puskesmas')->orderBy('name')->get();
        $listKabupaten = User::where('role', 'puskesmas')->distinct()->pluck('kabupaten')->filter();

        return view('admin.bpjs', compact('data', 'listPuskesmas', 'listKabupaten'));
    }

    public function approveBpjs($id) {
        BpjsData::findOrFail($id)->update([
            'status_verifikasi' => 'acc',
            'alasan_ditolak' => null
        ]);
        return back()->with('success', 'Data Warga BERHASIL Disetujui (ACC)!');
    }

    public function rejectBpjs(Request $request, $id) {
        $request->validate(['alasan_ditolak' => 'required|string|max:500']);
        BpjsData::findOrFail($id)->update([
            'status_verifikasi' => 'ditolak',
            'alasan_ditolak' => $request->alasan_ditolak
        ]);
        return back()->with('success', 'Data Warga DITOLAK beserta alasannya.');
    }

    public function adminAmbulan(Request $request) {
        $isFiltering = $request->has('kabupaten'); 
        $selectedKabupaten = $request->query('kabupaten');
        $totalAmbulan = User::where('role', 'ambulan')->count();
        $sedangJalan = AmbulanceLog::where('status', 'jalan')->count();
        $tersedia = $totalAmbulan - $sedangJalan;

        $driverStats = User::where('role', 'ambulan')
            ->withCount(['ambulanceLogs as total_trip' => function($q) {
                $q->where('status', 'selesai');
            }])->orderBy('total_trip', 'desc')->get();

        $listKabupaten = User::where('role', 'ambulan')->select('kabupaten')->distinct()->orderBy('kabupaten')->get();
        $logQuery = AmbulanceLog::with('driver');

        // FIX 1: Deklarasi variabel $armadaPerWilayah di awal agar tidak error 'undefined variable'
        $armadaPerWilayah = []; 

        if (!$isFiltering) {
            foreach($listKabupaten as $kab) {
                $kab->display_name = $kab->kabupaten ?? 'Lain-lain';
                $kab->jumlah_unit = User::where('role', 'ambulan')->where('kabupaten', $kab->kabupaten)->count();
            }
            $logs = $logQuery->latest()->take(50)->get();
        } else {
            $logQuery->whereHas('driver', function($q) use ($selectedKabupaten) {
                if ($selectedKabupaten == 'Lain-lain') { $q->whereNull('kabupaten'); } 
                else { $q->where('kabupaten', $selectedKabupaten); }
            });
            $drivers = User::where('role', 'ambulan');
            if ($selectedKabupaten == 'Lain-lain') { $drivers->whereNull('kabupaten'); } 
            else { $drivers->where('kabupaten', $selectedKabupaten); }
            $drivers = $drivers->orderBy('kecamatan')->orderBy('unit_kerja')->get();

            foreach($drivers as $driver) {
                $activeLog = AmbulanceLog::where('driver_id', $driver->id)->where('status', 'jalan')->first();
                $driver->status_sekarang = $activeLog ? 'sibuk' : 'standby';
                $driver->tujuan_sekarang = $activeLog ? $activeLog->tujuan : null;
            }
            $armadaPerWilayah = $drivers->groupBy(fn($item) => $item->kecamatan ?? 'Kecamatan Belum Diisi');
            $logs = $logQuery->latest()->get();
        }

        return view('admin.ambulan', compact('listKabupaten', 'armadaPerWilayah', 'totalAmbulan', 'sedangJalan', 'tersedia', 'selectedKabupaten', 'isFiltering', 'logs', 'driverStats'));
    }

    public function adminAmbulanDetail($id) {
        $driver = User::where('role', 'ambulan')->withCount(['ambulanceLogs as total_trip' => function($q) {
            $q->where('status', 'selesai');
        }])->findOrFail($id);
        $logs = AmbulanceLog::where('driver_id', $id)->latest()->get();
        return view('admin.ambulan_detail', compact('driver', 'logs'));
    }

    public function exportData(Request $request, $type) {
        $fileName = $type . '_data_' . date('Y-m-d_H-i') . '.csv';
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate", "Expires" => "0"];
        $callback = function() use($type, $request) {
            $file = fopen('php://output', 'w');
            if($type == 'bpjs') {
                fputcsv($file, ['No', 'Nama Warga', 'NIK', 'No HP', 'Alamat', 'Petugas Input', 'Kabupaten', 'Waktu Input', 'Status Verifikasi']);
                $query = BpjsData::with('petugas');
                if ($request->filled('kabupaten')) { $query->whereHas('petugas', function($q) use ($request) { $q->where('kabupaten', $request->kabupaten); }); }
                if ($request->filled('puskesmas_id')) { $query->where('user_id', $request->puskesmas_id); }
                foreach ($query->latest()->get() as $index => $d) {
                    fputcsv($file, [$index + 1, $d->nama_warga, "'".$d->nik, $d->no_hp, $d->alamat, $d->petugas->name ?? '-', $d->petugas->kabupaten ?? '-', $d->created_at->format('d/m/Y H:i'), $d->status_verifikasi]);
                }
            } else {
                fputcsv($file, ['No', 'Nama Pasien', 'Jenis Pelayanan', 'Lokasi Jemput', 'Tujuan Antar', 'Supir', 'Wilayah', 'Waktu Berangkat', 'Status Perjalanan']);
                foreach (AmbulanceLog::with('driver')->latest()->get() as $index => $d) {
                    fputcsv($file, [$index + 1, $d->nama_pasien, $d->jenis_pelayanan ?? '-', $d->lokasi_jemput, $d->tujuan, $d->driver->name ?? 'Supir Dihapus', ($d->driver->kabupaten ?? '-') . ' - ' . ($d->driver->unit_kerja ?? '-'), " " . \Carbon\Carbon::parse($d->waktu_berangkat)->format('d/m/Y H:i'), $d->status]);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
    // 2. BAGIAN PUSKESMAS (INPUT DATA)
    // ==========================================

    public function puskesmasIndex() {
        $myData = BpjsData::where('user_id', Auth::id())->latest()->get();
        return view('dashboard_puskesmas', compact('myData'));
    }

    public function storeBpjs(Request $request) {
        $request->validate([
            'nama_warga' => 'required',
            'nik' => 'required|numeric|digits:16|unique:bpjs_data,nik',
            'no_hp' => 'required',
            'alamat' => 'required',
            'foto_ktp' => 'required|image|max:2048',
            'foto_kk' => 'required|image|max:2048',
            'foto_sktm' => 'nullable|image|max:2048',
            'foto_rawat' => 'nullable|image|max:2048', 
        ]);
        
        $pathKtp = $this->uploadKeCloudinary($request->file('foto_ktp'), 'berkas_bpjs/ktp');
        $pathKk = $this->uploadKeCloudinary($request->file('foto_kk'), 'berkas_bpjs/kk');
        
        $pathSktm = null;
        if ($request->hasFile('foto_sktm')) {
            $pathSktm = $this->uploadKeCloudinary($request->file('foto_sktm'), 'berkas_bpjs/sktm');
        }

        $pathRawat = null;
        if ($request->hasFile('foto_rawat')) {
            $pathRawat = $this->uploadKeCloudinary($request->file('foto_rawat'), 'berkas_bpjs/rawat');
        }

        BpjsData::create([
            'user_id' => Auth::id(),
            'nama_warga' => $request->nama_warga,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_ktp' => $pathKtp,
            'foto_kk' => $pathKk,
            'foto_sktm' => $pathSktm,
            'foto_rawat' => $pathRawat,
            'status_verifikasi' => 'pending'
        ]);
        
        return back()->with('success', 'Berhasil! Data warga tersimpan via Cloudinary.');
    }

    // ==========================================
    // 3. BAGIAN SUPIR AMBULAN (DRIVER)
    // ==========================================

    public function ambulanIndex() {
        $activeTrip = AmbulanceLog::where('driver_id', Auth::id())->where('status', 'jalan')->first();
        $riwayat = AmbulanceLog::where('driver_id', Auth::id())->where('status', 'selesai')->latest()->get();
        return view('dashboard_ambulan', compact('activeTrip', 'riwayat'));
    }

    public function startAmbulan() {
        if(AmbulanceLog::where('driver_id', Auth::id())->where('status', 'jalan')->first()) 
            return back()->withErrors('Anda masih memiliki tugas aktif!');

        AmbulanceLog::create(['driver_id' => Auth::id(), 'waktu_berangkat' => now(), 'status' => 'jalan']);
        return back()->with('success', 'Sirine Dinyalakan! Hati-hati di jalan.');
    }

    public function finishAmbulan(Request $request, $id) {
        $log = AmbulanceLog::where('driver_id', Auth::id())->findOrFail($id);
        $request->validate([
            'nama_pasien' => 'required',
            'jenis_pelayanan' => 'required', 
            'lokasi_jemput' => 'required',
            'tujuan' => 'required',
            'foto_ktp' => 'required|image|max:3048', 
        ]);

        $path = $this->uploadKeCloudinary($request->file('foto_ktp'), 'ktp_pasien');
        
        $log->update([
            'nama_pasien' => $request->nama_pasien,
            'jenis_pelayanan' => $request->jenis_pelayanan,
            'lokasi_jemput' => $request->lokasi_jemput,
            'tujuan' => $request->tujuan,
            'foto_ktp' => $path,
            'status' => 'selesai'
        ]);

        // FIX 2: Kembalikan menggunakan back() agar tidak mencari rute 'ambulan.index' yang tidak ada
        return back()->with('success', 'Tugas Selesai. Data tersimpan aman!');
    }

    public function ambulanProfil() {
        $user = Auth::user();
        return view('ambulan_profil', compact('user'));
    }

    public function updateProfilAmbulan(Request $request) {
        $user = User::findOrFail(Auth::id());
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|numeric',
        ]);
        $user->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'nama_kepala_desa' => $request->nama_kepala_desa,
        ]);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}