@extends('layouts.admin_bootstrap')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <h3 class="fw-bold mb-0">{{ $totalAmbulan }}</h3>
                <small class="text-white-50 text-uppercase fw-bold ls-1">Total se-Provinsi</small>
                <i class="fa-solid fa-map position-absolute" style="font-size: 5rem; right: -20px; bottom: -20px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark overflow-hidden">
            <div class="card-body p-4 position-relative">
                <h3 class="fw-bold mb-0">{{ $sedangJalan }}</h3>
                <small class="text-black-50 text-uppercase fw-bold ls-1">Sedang Bergerak</small>
                <div class="spinner-grow text-dark position-absolute" style="width: 1rem; height: 1rem; top: 20px; right: 20px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <h3 class="fw-bold mb-0">{{ $tersedia }}</h3>
                <small class="text-white-50 text-uppercase fw-bold ls-1">Unit Standby</small>
            </div>
        </div>
    </div>
</div>

@if(!$isFiltering)
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-map-location-dot me-2"></i> Pilih Wilayah Pantauan</h5>
    
    <div class="row g-3">
        @forelse($listKabupaten as $kab)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-card position-relative">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">{{ $kab->display_name }}</h6>
                        <small class="text-muted"><i class="fa-solid fa-truck-medical me-1"></i> {{ $kab->jumlah_unit }} Unit Armada</small>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-primary">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
                <a href="{{ route('admin.ambulan', ['kabupaten' => $kab->kabupaten ?? 'Lain-lain']) }}" class="stretched-link"></a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="alert alert-info border-0 shadow-sm d-inline-block px-4">
                <i class="fa-solid fa-circle-info me-2"></i>
                Belum ada data wilayah. Lengkapi kolom "Kabupaten" pada User Driver.
            </div>
        </div>
        @endforelse
    </div>
@else
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <a href="{{ route('admin.ambulan') }}" class="btn btn-light border me-2 shadow-sm rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
            <span class="fw-bold fs-5 text-dark align-middle">
                <i class="fa-solid fa-building-flag me-2 text-primary"></i> {{ $selectedKabupaten }}
            </span>
        </div>
        <div class="position-relative">
            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="searchUnit" class="form-control rounded-pill ps-5 border-0 bg-white shadow-sm" placeholder="Cari Kecamatan / Supir...">
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 h-100 mb-4 overflow-hidden">
        <div class="card-body p-0 bg-light" style="max-height: 600px; overflow-y: auto;">
            <div class="p-4">
                <div class="row g-4" id="unitContainer">
                    @forelse($armadaPerWilayah as $kecamatan => $drivers)
                    <div class="col-12 unit-group" data-search="{{ strtolower($kecamatan) }} @foreach($drivers as $d) {{ strtolower($d->name) }} @endforeach">
                        <h6 class="fw-bold text-secondary text-uppercase border-bottom pb-2 mb-3 mt-2 sticky-top bg-light pt-2" style="top: 0; z-index: 5;">
                            <i class="fa-solid fa-map-pin me-2 text-danger"></i> {{ $kecamatan }}
                        </h6>

                        <div class="row g-3">
                            @foreach($drivers as $drv)
                            <div class="col-md-6 col-lg-4 col-xl-3 unit-item">
                                <a href="{{ route('admin.ambulan.detail', $drv->id) }}" class="text-decoration-none">
                                    <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden hover-card-detail">
                                        <div class="position-absolute start-0 top-0 bottom-0 {{ $drv->status_sekarang == 'sibuk' ? 'bg-warning' : 'bg-success' }}" style="width: 4px;"></div>
                                        <div class="card-body p-3 ps-4 text-dark">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-light p-2 rounded-circle me-2 text-center text-primary border" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-user-nurse"></i>
                                                </div>
                                                <div style="width: 70%;">
                                                    <h6 class="fw-bold mb-0 text-truncate">{{ $drv->name }}</h6>
                                                    <small class="text-muted d-block text-truncate">{{ $drv->unit_kerja ?? 'Desa ?' }}</small>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-2 border mb-2">
                                                <small class="text-muted d-block" style="font-size: 0.65rem;">KONTAK WA:</small>
                                                <span class="fw-bold text-success small">
                                                    <i class="fa-brands fa-whatsapp me-1"></i> {{ $drv->no_hp ?? '-' }}
                                                </span>
                                            </div>
                                            @if($drv->status_sekarang == 'sibuk')
                                                <span class="badge bg-warning text-dark w-100 shadow-sm"><i class="fa-solid fa-truck-fast me-1"></i> SIBUK</span>
                                            @else
                                                <span class="badge bg-light text-success border border-success w-100 opacity-75"><i class="fa-solid fa-check me-1"></i> STANDBY</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Tidak ada armada di wilayah ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endif

<div class="mt-5 border-top pt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-dark mb-0" style="font-family: 'Poppins', sans-serif;"><i class="fa-solid fa-clock-rotate-left me-2"></i> Log Operasional Terbaru</h5>
            <small class="text-muted">Riwayat penggunaan armada secara real-time.</small>
        </div>
        <a href="{{ route('admin.export', 'ambulan') }}" class="btn btn-success btn-sm shadow-sm rounded-3">
            <i class="fa-solid fa-file-excel me-2"></i> Export Excel
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-family: 'Poppins', sans-serif;">
                <thead class="bg-light text-secondary small text-uppercase fw-semibold">
                    <tr>
                        <th class="ps-4 py-3">Waktu</th>
                        <th class="py-3">Driver & Armada</th>
                        <th class="py-3">Pasien & Pelayanan</th>
                        <th class="py-3">Lokasi & Tujuan</th>
                        <th class="text-center py-3">Bukti</th>
                        <th class="text-center py-3">Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $log->driver->name ?? 'User Dihapus' }}</div>
                            <small class="text-muted">Kades: {{ $log->driver->nama_kepala_desa ?? '-' }}</small>
                        </td>
                        
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $log->nama_pasien ?? 'Sedang Berjalan' }}</div>
                            @if($log->jenis_pelayanan)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle mt-1" style="font-size: 0.65rem;">
                                    {{ $log->jenis_pelayanan }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <div style="font-size: 0.8rem;"><b>Dari:</b> <span class="text-muted">{{ $log->lokasi_jemput ?? '-' }}</span></div>
                            <div style="font-size: 0.8rem;" class="text-primary"><b>Ke:</b> {{ $log->tujuan ?? '-' }}</div>
                        </td>
                        <td class="text-center">
                            @if($log->foto_ktp)
                                <button type="button" class="btn btn-sm btn-light text-primary btn-preview-bukti rounded-pill px-3 hover-scale fw-medium" 
                                        data-url="{{ $log->foto_ktp }}" 
                                        data-title="Bukti Pasien: {{ $log->nama_pasien }}">
                                    <i class="fa-regular fa-image me-1"></i> Lihat
                                </button>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            <form action="{{ route('admin.ambulan.destroy', $log->id) }}" method="POST" class="form-hapus-log d-inline-block" data-nama="{{ $log->driver->name ?? 'Driver' }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center hover-scale mx-auto" style="width: 32px; height: 32px; border-width: 1.5px;" title="Hapus Log">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada riwayat perjalanan terbaru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="buktiPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="buktiTitle" style="font-family: 'Poppins', sans-serif;">Bukti Perjalanan</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" id="buktiImage" class="img-fluid rounded-3 border shadow-sm" alt="Bukti" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* UTILITIES KARTU & TABEL */
    .hover-card { transition: all 0.3s; cursor: pointer; border: 1px solid transparent; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; border-color: #0d6efd; }
    .hover-card-detail { transition: all 0.2s; border: 1px solid transparent; }
    .hover-card-detail:hover { transform: scale(1.02); border-color: #0d6efd; background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.08); }

    /* =========================================================
       CUSTOM SWEETALERT DESAIN BARU (TIDAK KAKU)
       ========================================================= */
    .swal-custom-popup {
        border-radius: 20px !important;
        padding: 2rem !important;
        border: 1px solid #f8fafc !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        font-family: 'Poppins', sans-serif !important;
    }
    .swal-custom-title {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-top: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    .icon-trash-custom {
        background: #fee2e2;
        color: #dc2626;
        width: 75px;
        height: 75px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 0.5rem auto;
    }
    .swal-action-btn {
        border-radius: 12px !important;
        padding: 10px 28px !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        letter-spacing: 0.3px !important;
        transition: all 0.2s ease !important;
    }
    .swal-btn-danger {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none !important;
        box-shadow: none !important;
    }
    .swal-btn-danger:hover {
        background-color: #fca5a5 !important;
        color: #b91c1c !important;
    }
    .swal-btn-cancel {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: none !important;
        box-shadow: none !important;
    }
    .swal-btn-cancel:hover {
        background-color: #e2e8f0 !important;
        color: #334155 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika Pencarian Unit
        const searchInput = document.getElementById('searchUnit');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let groups = document.querySelectorAll('.unit-group');
                groups.forEach(group => {
                    let text = group.getAttribute('data-search');
                    group.style.display = text.includes(filter) ? "" : "none";
                });
            });
        }

        // Logika Preview Bukti
        const previewButtons = document.querySelectorAll('.btn-preview-bukti');
        const modalElement = document.getElementById('buktiPreviewModal');
        const previewImage = document.getElementById('buktiImage');
        const modalTitle = document.getElementById('buktiTitle');
        const myModal = new bootstrap.Modal(modalElement);

        previewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const title = this.getAttribute('data-title');
                
                previewImage.src = 'https://i.gifer.com/ZKZg.gif'; 
                
                const img = new Image();
                img.onload = function() {
                    previewImage.src = this.src; 
                };
                img.src = url;

                modalTitle.innerText = title;
                myModal.show();
            });
        });

        // LOGIKA KONFIRMASI HAPUS DENGAN DESAIN BARU
        const formsHapus = document.querySelectorAll('.form-hapus-log');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    showConfirmButton: true,
                    showCancelButton: true,
                    buttonsStyling: false,
                    html: `
                        <div class="icon-trash-custom">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <h4 class="swal-custom-title">Hapus Log Ambulan?</h4>
                        <p class="text-muted small mt-2 mb-4" style="line-height: 1.5;">
                            Riwayat perjalanan atas nama <b class="text-dark">${nama}</b> akan dihapus dari sistem.
                        </p>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 small border border-danger border-opacity-25 text-start d-flex align-items-center">
                            <i class="fa-solid fa-triangle-exclamation me-3 fs-5"></i>
                            <span style="font-size: 0.8rem; font-weight: 500;">Tindakan ini tidak dapat dibatalkan.</span>
                        </div>
                    `,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal-custom-popup',
                        confirmButton: 'swal-action-btn swal-btn-danger ms-2',
                        cancelButton: 'swal-action-btn swal-btn-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ 
                            title: 'Menghapus...', 
                            allowOutsideClick: false, 
                            customClass: { popup: 'swal-custom-popup', title: 'swal-custom-title' },
                            didOpen: () => { Swal.showLoading() } 
                        });
                        this.submit(); 
                    }
                });
            });
        });

    });
</script>

@endsection