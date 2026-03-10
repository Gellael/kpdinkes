@extends('layouts.admin_bootstrap')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="mb-4">
                <h3 class="fw-bold text-dark">Halo, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i> Posisi: {{ Auth::user()->unit_kerja }}</p>
            </div>

            @if(!$tugasAktif)
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden text-center p-5 bg-white">
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                            <i class="fa-solid fa-truck-medical fa-3x"></i>
                        </div>
                        <h4 class="fw-bold">Siap Bertugas?</h4>
                        <p class="text-muted">Klik tombol di bawah saat Anda mulai berangkat menjemput pasien.</p>
                    </div>

                    <form action="{{ route('ambulan.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold fs-5">
                            <i class="fa-solid fa-play me-2"></i> MULAI PERJALANAN
                        </button>
                    </form>
                    
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted d-block mb-1 text-uppercase ls-1 fw-bold">Waktu Sekarang (Akurat)</small>
                        <h3 class="fw-bold text-dark" id="realtimeClock">00:00:00 WIB</h3>
                    </div>
                </div>

            @else
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="bg-warning p-4 text-center">
                        <h5 class="fw-bold mb-1 text-dark text-uppercase">Ambulan Sedang Beroperasi</h5>
                        <small class="text-dark opacity-75">Anda tercatat sedang dalam perjalanan dinas.</small>
                    </div>

                    <div class="card-body p-4">
                        <div class="bg-light rounded-4 p-3 text-center mb-4 border">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Waktu Berangkat</small>
                            <h4 class="fw-bold text-primary mb-0">
                                {{ \Carbon\Carbon::parse($tugasAktif->waktu_berangkat)->format('H:i') }} WIB
                            </h4>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($tugasAktif->waktu_berangkat)->format('d F Y') }}</small>
                        </div>

                        <form action="{{ route('ambulan.finish', $tugasAktif->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <h6 class="fw-bold mb-3"><i class="fa-solid fa-file-signature me-2 text-primary"></i>Laporan Selesai Tugas</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="form-control bg-light border-0 py-2" placeholder="Masukkan nama pasien..." required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Lokasi Jemput</label>
                                    <input type="text" name="lokasi_jemput" class="form-control bg-light border-0 py-2" placeholder="Desa/Kecamatan..." required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Tujuan Antar</label>
                                    <input type="text" name="tujuan" class="form-control bg-light border-0 py-2" placeholder="RS/Puskesmas..." required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-danger">
                                    <i class="fa-solid fa-camera me-1"></i> Foto Bukti / KTP Pasien
                                </label>
                                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                                <div class="form-text mt-1 text-muted" style="font-size: 0.75rem;">
                                    Wajib upload foto sebagai bukti perjalanan dinas selesai.
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