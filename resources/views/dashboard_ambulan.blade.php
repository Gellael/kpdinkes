@extends('layouts.admin_bootstrap')

@section('content')

<style>
    /* Kustomisasi Font & Base */
    .fw-900 { font-weight: 900; }
    .fw-800 { font-weight: 800; }
    .tracking-wide { letter-spacing: 1.5px; }
    .backdrop-blur { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }

    /* --- KARTU ID PENGEMUDI (Pengganti "Halo Eldo") --- */
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

    /* --- HERO STANDBY (Mode Menunggu) --- */
    .standby-zone {
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        border-radius: 30px;
        padding: 4rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.4);
    }

    /* Efek Radar Gelombang di Belakang Tombol */
    .radar-container { position: relative; display: flex; justify-content: center; align-items: center; margin: 3rem 0; }
    .radar-wave {
        position: absolute; width: 250px; height: 250px;
        background: rgba(239, 68, 68, 0.2); /* Merah Darurat */
        border-radius: 50%;
        animation: ping 2.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        z-index: 1;
    }
    .radar-wave:nth-child(2) { animation-delay: 0.5s; background: rgba(239, 68, 68, 0.1); }
    .radar-wave:nth-child(3) { animation-delay: 1s; background: rgba(239, 68, 68, 0.05); }

    @keyframes ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }

    /* TOMBOL DARURAT RAKSASA (Sangat gampang diklik) */
    .btn-emergency-start {
        position: relative; z-index: 5;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: 4px solid #fca5a5;
        border-radius: 50%;
        width: 220px; height: 220px; /* Raksasa di tengah layar */
        color: white;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 20px 50px rgba(220, 38, 38, 0.5), inset 0 -10px 20px rgba(0,0,0,0.2);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-emergency-start:active { transform: scale(0.95); box-shadow: 0 10px 30px rgba(220, 38, 38, 0.5), inset 0 10px 20px rgba(0,0,0,0.4); }
    .btn-emergency-start i { font-size: 4rem; margin-bottom: 5px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); }
    .btn-emergency-start span { font-weight: 900; font-size: 1.6rem; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
    .btn-emergency-start small { font-size: 0.8rem; font-weight: 600; opacity: 0.9; }

    /* --- MODE AKTIF (Misi Berjalan) --- */
    .active-zone {
        background: #ffffff; border-radius: 30px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1); border: 2px solid #ef4444;
    }
    /* Header Bergaris Peringatan (Warning Stripes) */
    .warning-header {
        background: repeating-linear-gradient( 45deg, #ef4444, #ef4444 20px, #dc2626 20px, #dc2626 40px );
        color: white; padding: 25px; text-align: center; position: relative;
    }
    .blinking-alert { animation: blinkAlert 1s infinite; font-weight: 900; letter-spacing: 2px; font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
    @keyframes blinkAlert { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

    /* Form Input Darurat (Besar & Jelas) */
    .emergency-input {
        background-color: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px;
        padding: 18px 25px; font-size: 1.1rem; font-weight: 600; color: #0f172a; transition: 0.3s;
    }
    .emergency-input:focus { background-color: #ffffff; border-color: #2563eb; box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.15); outline: none; }
    
    /* Tombol Upload File Besar */
    .upload-zone {
        border: 3px dashed #cbd5e1; border-radius: 20px; padding: 30px; text-align: center;
        background: #f8fafc; cursor: pointer; transition: 0.3s; position: relative;
    }
    .upload-zone:hover { border-color: #3b82f6; background: #eff6ff; }
    .upload-zone input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    
    /* Tombol Selesai Raksasa */
    .btn-finish-mission {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white; border: none; border-radius: 20px; padding: 22px; width: 100%;
        font-size: 1.4rem; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4); display: flex; align-items: center; justify-content: center; gap: 15px; transition: 0.2s;
    }
    .btn-finish-mission:active { transform: scale(0.98); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4); }

    /* --- TABEL RIWAYAT --- */
    .history-card { background: #ffffff; border-radius: 24px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    .table-custom thead th { background: #f8fafc; color: #64748b; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; padding: 15px; border: none; }
    .table-custom tbody td { padding: 18px 15px; vertical-align: middle; color: #334155; font-size: 0.95rem; font-weight: 500; border-bottom: 1px solid #f1f5f9; }

    @media (max-width: 768px) {
        .driver-id-card { flex-direction: column; text-align: center; gap: 15px; padding: 1.5rem; border-left: none; border-top: 8px solid #2563eb; }
        .btn-emergency-start { width: 250px; height: 250px; } /* Justru dibesarkan di HP agar sangat mudah disentuh */
        .radar-wave { width: 280px; height: 280px; }
        .btn-finish-mission { font-size: 1.2rem; padding: 20px; }
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
            <div class="small fw-800 text-muted tracking-wide mb-1">WAKTU SERVER</div>
            <div id="liveDigitalClock" class="fw-900 text-dark fs-4" style="font-variant-numeric: tabular-nums;">--:--:-- WIB</div>
            <a href="{{ route('ambulan.profil') }}" class="btn btn-sm btn-outline-primary rounded-pill fw-bold mt-2 px-3">
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
                            Waktu Keberangkatan: {{ \Carbon\Carbon::parse($activeTrip->waktu_berangkat)->timezone('Asia/Jakarta')->format('H:i WIB') }}
                        </div>
                    </div>

                    <div class="p-4 p-md-5 bg-white">
                        <form action="{{ route('ambulan.finish', $activeTrip->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="alert alert-primary bg-primary bg-opacity-10 border-0 rounded-4 p-4 mb-4">
                                <h5 class="fw-bold text-primary mb-1"><i class="fa-solid fa-clipboard-check me-2"></i> Laporan Akhir Tugas</h5>
                                <p class="text-muted small mb-0">Isi data di bawah ini hanya setelah Anda <strong>selesai</strong> mengantar pasien ke tujuan.</p>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">NAMA PASIEN</label>
                                    <input type="text" name="nama_pasien" class="form-control emergency-input" placeholder="Ketik nama pasien..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">JENIS PELAYANAN</label>
                                    <select name="jenis_pelayanan" class="form-select emergency-input" required style="cursor: pointer;">
                                        <option value="" selected disabled>Pilih Layanan...</option>
                                        <option value="Mengantar/menjemput jenazah">Mengantar / Menjemput Jenazah</option>
                                        <option value="Mengantar pasien (rujukan)">Mengantar Pasien (Rujukan)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">LOKASI JEMPUT (ASAL)</label>
                                    <input type="text" name="lokasi_jemput" class="form-control emergency-input" placeholder="Contoh: Desa Kiwai" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-800 text-dark">TITIK TUJUAN</label>
                                    <input type="text" name="tujuan" class="form-control emergency-input" placeholder="Contoh: RS M. Yunus" required>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label small fw-800 text-dark">FOTO BUKTI / KTP (WAJIB)</label>
                                <div class="upload-zone">
                                    <input type="file" name="foto_ktp" accept="image/*" required id="fileUpload">
                                    <i class="fa-solid fa-camera fa-3x text-primary mb-3"></i>
                                    <h5 class="fw-bold text-dark mb-1">Ketuk untuk Mengambil Foto</h5>
                                    <span class="text-muted small" id="fileName">Format: JPG, PNG dari Kamera/Galeri</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-finish-mission">
                                <i class="fa-solid fa-check-double fa-lg"></i> SELESAIKAN TUGAS
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @else
        
        <div class="standby-zone mb-5">
            <div class="text-center mb-4 relative z-index-2">
                <span class="badge bg-success bg-opacity-20 text-success border border-success px-4 py-2 rounded-pill fw-bold tracking-wide mb-3">
                    <i class="fa-solid fa-satellite-dish me-2"></i> SISTEM SIAGA
                </span>
                <h1 class="display-6 fw-900 text-white mb-2">Tunggu Instruksi / Panggilan</h1>
                <p class="text-white opacity-75 mx-auto" style="max-width: 500px;">
                    Tekan tombol darurat di bawah ini hanya saat Anda mulai bergerak menjemput pasien agar waktu tercatat presisi.
                </p>
            </div>

            <div class="radar-container">
                <div class="radar-wave"></div>
                <div class="radar-wave"></div>
                <div class="radar-wave"></div>
                
                <form action="{{ route('ambulan.start') }}" method="POST" class="position-relative" style="z-index: 10;">
                    @csrf
                    <button type="submit" class="btn-emergency-start border-0">
                        <i class="fa-solid fa-power-off"></i>
                        <span>BERANGKAT</span>
                        <small>TEKAN UNTUK MULAI</small>
                    </button>
                </form>
            </div>
        </div>

    @endif

    <div class="history-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-900 text-dark mb-0"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Log Operasional Selesai</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th width="20%">Keberangkatan</th>
                        <th width="25%">Data Pasien</th>
                        <th width="35%">Rute & Layanan</th>
                        <th width="20%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $log)
                        <tr>
                            <td>
                                <div class="fw-800 text-dark">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->timezone('Asia/Jakarta')->format('d M Y') }}</div>
                                <div class="text-muted fw-bold"><i class="fa-solid fa-clock me-1 text-warning"></i> {{ \Carbon\Carbon::parse($log->waktu_berangkat)->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark text-white rounded-3 d-flex align-items-center justify-content-center fw-900 me-3 text-uppercase shadow-sm" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        {{ substr($log->nama_pasien, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-900 text-dark" style="font-size: 1.05rem;">{{ $log->nama_pasien }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-800 text-primary mb-1">{{ $log->jenis_pelayanan ?? '-' }}</div>
                                <div class="d-flex align-items-center flex-wrap gap-2 text-muted fw-bold" style="font-size: 0.85rem;">
                                    <span class="bg-light px-2 py-1 rounded border">{{ $log->lokasi_jemput }}</span>
                                    <i class="fa-solid fa-angles-right text-primary"></i>
                                    <span class="bg-light px-2 py-1 rounded border">{{ $log->tujuan }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-4 py-2 fw-bold" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-check-circle me-1"></i> SELESAI
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-3"><i class="fa-solid fa-box-open fa-3x opacity-25"></i></div>
                                <h6 class="fw-bold text-muted">Belum ada riwayat misi yang tercatat.</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    // Update Jam Live
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
        const clockEl = document.getElementById('liveDigitalClock');
        if(clockEl) clockEl.innerHTML = timeStr + ' <span class="fs-6 opacity-75">WIB</span>';
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Tampilkan nama file saat foto dipilih
    const fileUpload = document.getElementById('fileUpload');
    const fileNameDisplay = document.getElementById('fileName');
    if(fileUpload) {
        fileUpload.addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                fileNameDisplay.innerHTML = '<span class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i> File Siap: ' + e.target.files[0].name + '</span>';
            }
        });
    }
</script>

@endsection