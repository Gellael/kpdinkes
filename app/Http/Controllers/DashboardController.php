<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BpjsData;
use App\Models\AmbulanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // DITAMBAHKAN UNTUK KEAMANAN FILE

class DashboardController extends Controller
{
    // ==========================================
    // 1. BAGIAN ADMIN (PENGELOLA UTAMA)
    // ==========================================

    public function adminIndex() {
        return view('admin.hub');
    }

    // --- MANAJEMEN USER ---
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
            'role' => 'required'
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'unit_kerja' => $request->unit_kerja,
            'kecamatan' => $request->kecamatan, 
            'kabupaten' => $request->kabupaten, 
            'no_hp' => $request->no_hp,
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

    // --- MONITORING BPJS DENGAN FILTER ---
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
            'alasan_ditolak' => null // Bersihkan alasan jika sebelumnya pernah ditolak lalu disetujui ulang
        ]);
        return back()->with('success', 'Data Warga BERHASIL Disetujui (ACC)!');
    }

    // PERUBAHAN: Menambahkan Request untuk menangkap alasan penolakan
    public function rejectBpjs(Request $request, $id) {
        // Validasi agar alasan wajib diisi saat menolak
        $request->validate([
            'alasan_ditolak' => 'required|string|max:500'
        ]);

        BpjsData::findOrFail($id)->update([
            'status_verifikasi' => 'ditolak',
            'alasan_ditolak' => $request->alasan_ditolak
        ]);
        
        return back()->with('success', 'Data Warga DITOLAK beserta alasannya.');
    }

    // ==========================================
    // MONITORING AMBULAN
    // ==========================================
    public function adminAmbulan(Request $request) {
        $isFiltering = $request->has('kabupaten'); 
        $selectedKabupaten = $request->query('kabupaten');

        $totalAmbulan = User::where('role', 'ambulan')->count();
        $sedangJalan = AmbulanceLog::where('status', 'jalan')->count();
        $tersedia = $totalAmbulan - $sedangJalan;

        $driverStats = User::where('role', 'ambulan')
            ->withCount(['ambulanceLogs as total_trip' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->orderBy('total_trip', 'desc')
            ->get();

        $listKabupaten = User::where('role', 'ambulan')
                            ->select('kabupaten')
                            ->distinct()
                            ->orderBy('kabupaten')
                            ->get();

        $armadaPerWilayah = [];
        $logQuery = AmbulanceLog::with('driver');

        if (!$isFiltering) {
            foreach($listKabupaten as $kab) {
                $kab->display_name = $kab->kabupaten ?? 'Lain-lain';
                $kab->jumlah_unit = User::where('role', 'ambulan')->where('kabupaten', $kab->kabupaten)->count();
            }
            $logs = $logQuery->latest()->take(50)->get();
        } 
        else {
            $logQuery->whereHas('driver', function($q) use ($selectedKabupaten) {
                if ($selectedKabupaten == 'Lain-lain') {
                    $q->whereNull('kabupaten');
                } else {
                    $q->where('kabupaten', $selectedKabupaten);
                }
            });

            $drivers = User::where('role', 'ambulan');
            if ($selectedKabupaten == 'Lain-lain') {
                $drivers->whereNull('kabupaten');
            } else {
                $drivers->where('kabupaten', $selectedKabupaten);
            }

            $drivers = $drivers->orderBy('kecamatan')->orderBy('unit_kerja')->get();

            foreach($drivers as $driver) {
                $activeLog = AmbulanceLog::where('driver_id', $driver->id)->where('status', 'jalan')->first();
                $driver->status_sekarang = $activeLog ? 'sibuk' : 'standby';
                $driver->tujuan_sekarang = $activeLog ? $activeLog->tujuan : null;
            }

            $armadaPerWilayah = $drivers->groupBy(function($item) {
                return $item->kecamatan ?? 'Kecamatan Belum Diisi';
            });
            
            $logs = $logQuery->latest()->get();
        }

        return view('admin.ambulan', compact(
            'listKabupaten', 'armadaPerWilayah', 'totalAmbulan', 
            'sedangJalan', 'tersedia', 'selectedKabupaten', 
            'isFiltering', 'logs', 'driverStats'
        ));
    }

    public function adminAmbulanDetail($id) {
        $driver = User::where('role', 'ambulan')
            ->withCount(['ambulanceLogs as total_trip' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->findOrFail($id);

        $logs = AmbulanceLog::where('driver_id', $id)->latest()->get();

        return view('admin.ambulan_detail', compact('driver', 'logs'));
    }

    // --- EXPORT DATA CERDAS ---
    public function exportData(Request $request, $type) {
        $fileName = $type . '_data_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($type, $request) {
            $file = fopen('php://output', 'w');
            
            if($type == 'bpjs') {
                fputcsv($file, ['No', 'Nama Warga', 'NIK', 'No HP', 'Alamat', 'Petugas Input', 'Kabupaten', 'Waktu Input', 'Status Verifikasi']);
                $query = BpjsData::with('petugas');
                if ($request->filled('kabupaten')) {
                    $query->whereHas('petugas', function($q) use ($request) { $q->where('kabupaten', $request->kabupaten); });
                }
                if ($request->filled('puskesmas_id')) { $query->where('user_id', $request->puskesmas_id); }
                
                $data = $query->latest()->get();
                foreach ($data as $index => $d) {
                    fputcsv($file, [
                        $index + 1, $d->nama_warga, "'".$d->nik, $d->no_hp, $d->alamat, 
                        $d->petugas->name ?? '-', $d->petugas->kabupaten ?? '-',
                        $d->created_at->format('d/m/Y H:i'), $d->status_verifikasi
                    ]);
                }
            } 
            elseif($type == 'ambulan_personal') {
                $driverId = $request->query('driver_id');
                $driver = User::findOrFail($driverId);
                
                fputcsv($file, ['No', 'Waktu Berangkat', 'Nama Pasien', 'Jenis Pelayanan', 'Lokasi Jemput', 'Tujuan Antar', 'Status']);
                
                $logs = AmbulanceLog::where('driver_id', $driverId)->latest()->get();
                foreach ($logs as $index => $log) {
                    fputcsv($file, [
                        $index + 1, 
                        $log->waktu_berangkat, 
                        $log->nama_pasien, 
                        $log->jenis_pelayanan ?? '-', 
                        $log->lokasi_jemput, 
                        $log->tujuan, 
                        $log->status
                    ]);
                }
            }
            else {
                fputcsv($file, ['No', 'Nama Pasien', 'Jenis Pelayanan', 'Lokasi Jemput', 'Tujuan Antar', 'Supir', 'Wilayah', 'Waktu Berangkat', 'Status Perjalanan']);
                $data = AmbulanceLog::with('driver')->latest()->get();
                foreach ($data as $index => $d) {
                    fputcsv($file, [
                        $index + 1, 
                        $d->nama_pasien, 
                        $d->jenis_pelayanan ?? '-', 
                        $d->lokasi_jemput, 
                        $d->tujuan, 
                        $d->driver->name ?? 'Supir Dihapus', 
                        ($d->driver->kabupaten ?? '-') . ' - ' . ($d->driver->unit_kerja ?? '-'), 
                        " " . \Carbon\Carbon::parse($d->waktu_berangkat)->format('d/m/Y H:i'), 
                        $d->status
                    ]);
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
            'foto_sktm' => 'required|image|max:2048',
            'foto_rawat' => 'nullable|image|max:2048', 
        ]);
        
        $pathKtp = $request->file('foto_ktp')->store('private/berkas_bpjs/ktp');
        $pathKk = $request->file('foto_kk')->store('private/berkas_bpjs/kk');
        $pathSktm = $request->file('foto_sktm')->store('private/berkas_bpjs/sktm');
        
        $pathRawat = null;
        if ($request->hasFile('foto_rawat')) {
            $pathRawat = $request->file('foto_rawat')->store('private/berkas_bpjs/rawat');
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
        
        return back()->with('success', 'Data warga berhasil didaftarkan!');
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
        $cek = AmbulanceLog::where('driver_id', Auth::id())->where('status', 'jalan')->first();
        if($cek) return back()->withErrors('Anda masih memiliki tugas aktif!');

        AmbulanceLog::create([
            'driver_id' => Auth::id(),
            'waktu_berangkat' => now(),
            'status' => 'jalan',
        ]);

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

        $path = $request->file('foto_ktp')->store('private/ktp_pasien');
        
        $log->update([
            'nama_pasien' => $request->nama_pasien,
            'jenis_pelayanan' => $request->jenis_pelayanan,
            'lokasi_jemput' => $request->lokasi_jemput,
            'tujuan' => $request->tujuan,
            'foto_ktp' => $path,
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Tugas Selesai. Data berhasil disimpan!');
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
            'nama_kepala_desa' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'nama_kepala_desa' => $request->nama_kepala_desa,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // ==========================================
    // 4. KEAMANAN FILE (ANTI KEBOCORAN DATA)
    // ==========================================
    
    public function tampilkanBerkasRahasia(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            abort(404, 'Path dokumen tidak diberikan.');
        }

        $cleanPath = str_replace(['public/', 'private/', 'storage/'], '', $path);
        $cleanPath = ltrim($cleanPath, '/'); 

        $possibleLocations = [
            'private/' . $cleanPath,  
            'public/' . $cleanPath,   
            $cleanPath                
        ];

        $foundPath = null;
        foreach ($possibleLocations as $loc) {
            if (\Illuminate\Support\Facades\Storage::exists($loc)) {
                $foundPath = $loc;
                break; 
            }
        }

        if (!$foundPath) {
            abort(404, 'File gambar fisik sudah tidak ada di server. File yang dicari: ' . $cleanPath);
        }

        return response()->file(\Illuminate\Support\Facades\Storage::path($foundPath));
    }
}