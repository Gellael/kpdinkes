@extends('layouts.admin_bootstrap')

@section('content')

<style>
    /* Tipografi Poppins Premium */
    .fw-800 { font-weight: 800; }
    .ls-1 { letter-spacing: 1px; }
    
    /* Animasi Kartu Menu Utama */
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent !important;
    }
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: rgba(13, 110, 253, 0.2) !important;
    }
    
    .hover-card:hover .icon-circle {
        transform: scale(1.1) rotate(5deg);
    }
    .icon-circle { transition: all 0.3s ease; }

    /* Dekorasi Lingkaran Latar Belakang */
    .circle-deco {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }
</style>

<div class="container-fluid p-0">
    
    <div class="card border-0 rounded-5 shadow-lg mb-4 position-relative" 
         style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); min-height: 200px; overflow: hidden;">
        
        <div class="circle-deco" style="width: 250px; height: 250px; top: -120px; right: -30px;"></div>
        <div class="circle-deco" style="width: 120px; height: 120px; bottom: -40px; left: 5%;"></div>
        
        <div class="card-body p-4 p-md-5 position-relative" style="z-index: 5;">
            <div class="row align-items-center">
                <div class="col-lg-8 text-white">
                    <div class="mb-2">
                        <h1 class="fw-800 mb-1 display-5 ls-1">Selamat Datang,</h1>
                        <h2 class="fw-light opacity-90 fs-3 mb-3">{{ Auth::user()->name }}</h2>
                    </div>
                    <p class="lead opacity-75 mb-0 d-none d-md-block" style="font-size: 1rem; max-width: 650px; line-height: 1.6;">
                        Pusat kendali utama Program Prioritas Gubernur dan Wakil Gubernur. Kelola data kesehatan masyarakat dan pantau operasional ambulan secara real-time melalui panel navigasi di bawah ini.
                    </p>
                </div>
                
                <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                    <div class="text-center bg-white bg-opacity-10 p-4 rounded-5 border border-white border-opacity-20 shadow-sm" 
                         style="backdrop-filter: blur(10px); min-width: 180px;">
                        <div class="display-6 fw-bold mb-0 text-white" id="liveClockMain" style="letter-spacing: 2px;">00:00</div>
                        <div class="small fw-bold text-uppercase opacity-75 text-white ls-1" style="font-size: 0.65rem;">Waktu Sistem</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-800 small mb-2">Total Pengguna</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\User::count() }}</h2>
                        <small class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Akun Aktif</small>
                    </div>
                    <div class="rounded-4 bg-primary bg-opacity-10 text-primary p-3 fs-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-800 small mb-2">Data BPJS</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\BpjsData::count() }}</h2>
                        <small class="text-primary fw-bold">Warga Terdaftar</small>
                    </div>
                    <div class="rounded-4 bg-success bg-opacity-10 text-success p-3 fs-4">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-800 small mb-2">Ambulan Jalan</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\AmbulanceLog::where('status', 'jalan')->count() }}</h2>
                        <small class="text-warning fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Beroperasi</small>
                    </div>
                    <div class="rounded-4 bg-warning bg-opacity-10 text-warning p-3 fs-4">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-800 small mb-2">Unit Standby</h6>
                        @php
                            $total = \App\Models\User::where('role', 'ambulan')->count();
                            $jalan = \App\Models\AmbulanceLog::where('status', 'jalan')->count();
                        @endphp
                        <h2 class="fw-bold mb-0 text-dark">{{ $total - $jalan }}</h2>
                        <small class="text-info fw-bold">Siap Tugas</small>
                    </div>
                    <div class="rounded-4 bg-info bg-opacity-10 text-info p-3 fs-4">
                        <i class="fa-solid fa-square-parking"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-4 ls-1"><i class="fa-solid fa-layer-group me-2 text-primary"></i> Navigasi Kontrol</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('admin.users') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card rounded-5 overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="icon-circle bg-primary text-white rounded-circle mx-auto mb-4 shadow-lg d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-user-gear fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Manajemen User</h5>
                        <p class="text-muted small mb-0 px-2">Kelola akun Admin, Puskesmas, dan Driver secara terpadu.</p>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center text-primary fw-bold small">
                        Buka Menu <i class="fa-solid fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.bpjs') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card rounded-5 overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="icon-circle bg-success text-white rounded-circle mx-auto mb-4 shadow-lg d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-file-shield fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Verifikasi BPJS</h5>
                        <p class="text-muted small mb-0 px-2">Validasi dokumen pendaftaran BPJS gratis bagi warga.</p>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center text-success fw-bold small">
                        Buka Menu <i class="fa-solid fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.ambulan') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card rounded-5 overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="icon-circle bg-warning text-dark rounded-circle mx-auto mb-4 shadow-lg d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-map-location-dot fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Monitoring Ambulan</h5>
                        <p class="text-muted small mb-0 px-2">Pantau sebaran unit dan riwayat perjalanan ambulan.</p>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center text-warning fw-bold small text-dark">
                        Buka Menu <i class="fa-solid fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>

<script>
    function updateLiveClockMain() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false }).replace('.', ':');
        const clockEl = document.getElementById('liveClockMain');
        if(clockEl) clockEl.textContent = timeStr;
    }
    setInterval(updateLiveClockMain, 1000);
    updateLiveClockMain();
</script>

@endsection