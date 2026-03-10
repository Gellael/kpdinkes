@extends('layouts.admin_bootstrap')

@section('content')

<style>
    /* Kustomisasi Font & Base */
    .fw-900 { font-weight: 900; }
    .fw-800 { font-weight: 800; }
    .tracking-wide { letter-spacing: 1.5px; }
    .backdrop-blur { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }

    /* --- KARTU ID PENGEMUDI --- */
    .driver-id-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-left: 8px solid #2563eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .driver-avatar {
        width: 65px; height: 65px;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white; font-size: 1.8rem; font-weight: 800;
        border-radius: 18px; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
    }
    .driver-info h2 { font-size: 1.6rem; margin: 0; color: #0f172a; text-transform: uppercase; letter-spacing: -0.5px; }
    .driver-info .badge-unit { background: #eff6ff; color: #1e40af; padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; margin-top: 5px; display: inline-block; }

    /* --- HERO STANDBY --- */
    .standby-zone {
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        border-radius: 30px;
        padding: 4rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.4);
    }

    .radar-container { position: relative; display: flex; justify-content: center; align-items: center; margin: 3rem 0; }
    .radar-wave {
        position: absolute; width: 250px; height: 250px;
        background: rgba(239, 68, 68, 0.2);
        border-radius: 50%;
        animation: ping 2.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        z-index: 1;
    }
    .radar-wave:nth-child(2) { animation-delay: 0.5s; background: rgba(239, 68, 68, 0.1); }
    .radar-wave:nth-child(3) { animation-delay: 1s; background: rgba(239, 68, 68, 0.05); }

    @keyframes ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }

    .btn-emergency-start {
        position: relative; z-index: 5;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: 4px solid #fca5a5;
        border-radius: 50%;
        width: 220px; height: 220px;
        color: white;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 20px 50px rgba(220, 38, 38, 0.5), inset 0 -10px 20px rgba(0,0,0,0.2);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-emergency-start:active { transform: scale(0.95); }

    /* --- MODE AKTIF --- */
    .active-zone {
        background: #ffffff; border-radius: 30px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1); border: 2px solid #ef4444;
    }
    .warning-header {
        background: repeating-linear-gradient( 45deg, #ef4444, #ef4444 20px, #dc2626 20px, #dc2626 40px );
        color: white; padding: 25px; text-align: center; position: relative;
    }
    .blinking-alert { animation: blinkAlert 1s infinite; font-weight: 900; letter-spacing: 2px; font-size: 1.5rem; }
    @keyframes blinkAlert { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

    .emergency-input {
        background-color: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px;
        padding: 18px 25px; font-size: 1.1rem; font-weight: 600;
    }
    
    .upload-zone {
        border: 3px dashed #cbd5e1; border-radius: 20px; padding: 30px; text-align: center;
        background: #f8fafc; cursor: pointer; position: relative;
    }
    .upload-zone input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    
    .btn-finish-mission {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white; border: none; border-radius: 20px; padding: 22px; width: 100%;
        font-size: 1.4rem; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4); display: flex; align-items: center; justify-content: center; gap: 15px;
    }
    .btn-finish-mission:disabled { background: #9ca3af; box-shadow: none; cursor: not-allowed; }

    /* --- TABEL RIWAYAT --- */
    .history-card { background: #ffffff; border-radius: 24px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    .table-custom thead th { background: #f8fafc; color: #64748b; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; padding: 15px; border: none; }
    .table-custom tbody td { padding: 18px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    @media (max-width: 768px) {
        .btn-emergency-start { width: 220px; height: 220px; }
    }
</style>

<div class="container-fluid p-0">

    <div class="driver-id-card">
        <div class="d-flex align-items-center flex-column flex-md-row gap-3 gap-md-4">
            <div class="driver-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="driver-info text-center text-md-start">
                <div class="small fw-800 text-muted tracking-wide">ID PENGEMUDI</div>
                <h2 class="fw-900">{{ Auth::user()->name }}</h2>
                <div class="badge-unit"><i class="fa-solid fa-truck-medical me-1"></i> {{ Auth::user()->unit_kerja ?? 'Unit Darurat' }}</div>
            </div>
        </div>
        <div class="text-center text-md-end mt-3 mt-md-0">
            <div id="liveDigitalClock" class="fw-900 text-dark fs-4">--:--:-- WIB</div>
            <a href="{{ route('ambulan.profil') }}" class="btn btn-sm btn-outline-primary rounded-pill fw-bold mt-2">
                <i class="fa-solid fa-gear me-1"></i> Pengaturan
            </a>
        </div>
    </div>

    @if($activeTrip)
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-lg-10">
                <div class="active-zone">
                    <div class="warning-header">
                        <i class="fa-solid fa-triangle-exclamation fa-3x mb-2 text-white"></i>
                        <div class="blinking-alert">STATUS: DALAM PERJALANAN</div>
                        <div class="fw-bold mt-2 text-white opacity-75 bg-black bg-opacity-25 d-inline-block px-4 py-2 rounded-pill">
                            Berangkat: {{ \Carbon\Carbon::parse($activeTrip->waktu_berangkat)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                        </div>
                    </div>

                    <div class="p-4 p-md-5 bg-white">
                        <form id="finishForm" action="{{ route('ambulan.finish', $activeTrip->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="alert alert-primary bg-primary bg-opacity-10 border-0 rounded-4 p-4 mb-4">
                                <h5 class="fw-bold text-primary mb-1"><i class="fa-solid fa-clipboard-check me-2"></i> Laporan Akhir Tugas</h5>
                                <p class="text-muted small mb-0">Pastikan data benar sebelum menekan tombol SELESAI.</p>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">NAMA PASIEN</label>
                                    <input type="text" name="nama_pasien" class="form-control emergency-input" placeholder="Nama pasien..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">JENIS PELAYANAN</label>
                                    <select name="jenis_pelayanan" class="form-select emergency-input" required>
                                        <option value="" selected disabled>Pilih Layanan...</option>
                                        <option value="Mengantar/menjemput jenazah">Mengantar / Menjemput Jenazah</option>
                                        <option value="Mengantar pasien (rujukan)">Mengantar Pasien (Rujukan)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">LOKASI JEMPUT</label>
                                    <input type="text" name="lokasi_jemput" class="form-control emergency-input" placeholder="Asal penjemputan..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">TITIK TUJUAN</label>
                                    <input type="text" name="tujuan" class="form-control emergency-input" placeholder="Tujuan pengantaran..." required>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label small fw-800 text-dark">FOTO BUKTI / KTP (WAJIB)</label>
                                <div class="upload-zone" onclick="document.getElementById('fileUpload').click()">
                                    <input type="file" name="foto_ktp" accept="image/*" required id="fileUpload" style="display: none;">
                                    <i class="fa-solid fa-camera fa-3x text-primary mb-3"></i>
                                    <h5 class="fw-bold text-dark mb-1">Ketuk untuk Mengambil Foto</h5>
                                    <span class="text-muted small" id="fileName">Format: JPG, PNG</span>
                                </div>
                            </div>

                            <button type="submit" id="btnSubmitFinish" class="btn-finish-mission">
                                <i class="fa-solid fa-check-double fa-lg"></i> 
                                <span id="btnText">SELESAIKAN TUGAS</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="standby-zone mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-success bg-opacity-20 text-success border border-success px-4 py-2 rounded-pill fw-bold mb-3">
                    <i class="fa-solid fa-satellite-dish me-2"></i> SISTEM SIAGA
                </span>
                <h1 class="display-6 fw-900 text-white mb-2">Tunggu Instruksi</h1>
                <p class="text-white opacity-75">Tekan tombol BERANGKAT saat Anda mulai bergerak.</p>
            </div>

            <div class="radar-container">
                <div class="radar-wave"></div>
                <div class="radar-wave"></div>
                <div class="radar-wave"></div>
                
                <form action="{{ route('ambulan.start') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-emergency-start border-0">
                        <i class="fa-solid fa-power-off"></i>
                        <span>BERANGKAT</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="history-card mb-4">
        <h5 class="fw-900 text-dark mb-4"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Log Operasional Selesai</h5>
        <div class="table-responsive">
            <table class="table table-custom table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pasien</th>
                        <th>Rute</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $log)
                        <tr>
                            <td>
                                <div class="fw-800 text-dark">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('d M Y') }}</div>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('H:i') }} WIB</div>
                            </td>
                            <td><div class="fw-900 text-dark">{{ $log->nama_pasien }}</div></td>
                            <td>
                                <div class="small fw-bold text-primary">{{ $log->jenis_pelayanan }}</div>
                                <div class="text-muted small">{{ $log->lokasi_jemput }} ➔ {{ $log->tujuan }}</div>
                            </td>
                            <td class="text-center"><span class="badge bg-success">SELESAI</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('liveDigitalClock').innerText = now.toLocaleTimeString('id-ID') + ' WIB';
    }
    setInterval(updateClock, 1000);
    updateClock();

    // File Preview Name
    document.getElementById('fileUpload').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.getElementById('fileName').innerHTML = '<b class="text-success">Siap: ' + e.target.files[0].name + '</b>';
        }
    });

    // ANTI-DOUBLE CLICK & LOADING UI (Penting untuk upload besar)
    document.getElementById('finishForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitFinish');
        const btnText = document.getElementById('btnText');
        
        btn.disabled = true; // Matikan tombol agar tidak diklik 2x
        btnText.innerHTML = "SEDANG MENGUPLOAD..."; // Beri tanda sedang proses
        btn.style.opacity = "0.7";
    });
</script>

@endsection