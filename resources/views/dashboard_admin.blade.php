@extends('layouts.admin_bootstrap')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            
            <div class="mb-4 text-center text-md-start">
                <h3 class="fw-bold text-dark mb-1">Halo, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-muted small">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i> Posisi: <span class="fw-semibold">{{ Auth::user()->unit_kerja }}</span>
                </p>
            </div>

            @if(!$tugasAktif)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center p-4 p-md-5 bg-white">
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 90px; height: 90px;">
                            <i class="fa-solid fa-truck-medical fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Siap Bertugas?</h4>
                        <p class="text-muted small px-3">Klik tombol di bawah saat Anda mulai berangkat menjemput pasien.</p>
                    </div>

                    <form action="{{ route('ambulan.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow-sm fw-bold transition-all" style="letter-spacing: 1px;">
                            <i class="fa-solid fa-play me-2"></i> MULAI PERJALANAN
                        </button>
                    </form>
                    
                    <div class="mt-4 pt-4 border-top">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Waktu Server Akurat</small>
                        <h2 class="fw-black text-dark mb-0 font-monospace" id="realtimeClock" style="letter-spacing: -1px;">00:00:00 WIB</h2>
                    </div>
                </div>

            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="bg-warning p-3 p-md-4 text-center">
                        <div class="spinner-grow text-dark spinner-grow-sm mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="fw-bold mb-1 text-dark text-uppercase" style="letter-spacing: 1px;">Ambulan Beroperasi</h5>
                        <small class="text-dark opacity-75">Anda tercatat sedang dalam perjalanan dinas.</small>
                    </div>

                    <div class="card-body p-4">
                        <div class="bg-light rounded-4 p-3 text-center mb-4 border border-light-subtle">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Waktu Berangkat</small>
                            <h3 class="fw-bold text-primary mb-0 font-monospace">
                                {{ \Carbon\Carbon::parse($tugasAktif->waktu_berangkat)->format('H:i') }} WIB
                            </h3>
                            <small class="text-muted" style="font-size: 0.8rem;">{{ \Carbon\Carbon::parse($tugasAktif->waktu_berangkat)->format('d F Y') }}</small>
                        </div>

                        <form action="{{ route('ambulan.finish', $tugasAktif->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <h6 class="fw-bold mb-3 border-bottom pb-2">
                                <i class="fa-solid fa-file-signature me-2 text-primary"></i>Laporan Selesai Tugas
                            </h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="Masukkan nama pasien..." required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Lokasi Jemput</label>
                                    <input type="text" name="lokasi_jemput" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="Desa/Kecamatan..." required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Tujuan Antar</label>
                                    <input type="text" name="tujuan" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="RS/Puskesmas..." required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-danger">
                                    <i class="fa-solid fa-camera me-1"></i> Foto Bukti / KTP Pasien
                                </label>
                                <input type="file" name="foto_ktp" class="form-control bg-light border-0 rounded-3" accept="image/*" required capture="environment">
                                <div class="form-text mt-2 text-muted" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-circle-info me-1"></i> Wajib upload foto sebagai bukti perjalanan dinas selesai.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-pill shadow-sm fw-bold">
                                <i class="fa-solid fa-check-circle me-2"></i> SELESAIKAN TUGAS
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElement = document.getElementById('realtimeClock');
        if(clockElement) {
            clockElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }

    setInterval(updateClock, 1000);
    updateClock(); // Jalankan langsung saat load
</script>
@endsection