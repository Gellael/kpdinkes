@extends('layouts.admin_bootstrap')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.ambulan') }}" class="btn btn-light border rounded-pill px-3 shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Wilayah
        </a>
        <a href="{{ route('admin.export', ['type' => 'ambulan_personal', 'driver_id' => $driver->id]) }}" class="btn btn-success rounded-3 shadow-sm">
            <i class="fa-solid fa-file-excel me-2"></i> Export Excel
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white overflow-hidden position-relative">
        <div class="d-flex align-items-center position-relative" style="z-index: 2;">
            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 65px; height: 65px; font-size: 1.8rem;">
                <i class="fa-solid fa-user-nurse"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $driver->name }}</h4>
                <p class="mb-0 opacity-75">{{ $driver->kabupaten }} | {{ $driver->unit_kerja }}</p>
            </div>
            <div class="ms-auto text-end">
                <h2 class="fw-bold mb-0">{{ $driver->total_trip }}</h2>
                <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Total Trip Selesai</small>
            </div>
        </div>
        <i class="fa-solid fa-route position-absolute" style="font-size: 8rem; right: -20px; bottom: -30px; opacity: 0.1;"></i>
    </div>
</div>

<h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Riwayat Perjalanan Individu</h5>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary small text-uppercase">
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Tujuan</th>
                    <th>Lokasi Jemput</th>
                    <th class="text-center">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('d M Y') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($log->waktu_berangkat)->format('H:i') }} WIB</small>
                    </td>
                    <td class="fw-bold text-primary">{{ $log->tujuan ?? 'Tujuan Belum Diisi' }}</td>
                    <td class="small text-muted">{{ $log->lokasi_jemput ?? '-' }}</td>
                    <td class="text-center">
                        @if($log->foto_ktp)
                            <button type="button" class="btn btn-sm btn-light text-primary btn-preview-bukti rounded-pill px-3" 
                                    data-url="{{ route('file.rahasia', ['path' => $log->foto_ktp]) }}" 
                                    data-title="Bukti Pasien: {{ $log->nama_pasien }}">
                                <i class="fa-regular fa-image me-1"></i> Lihat
                            </button>
                        @else
                            <span class="text-muted small italic">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted small">Belum ada riwayat perjalanan untuk supir ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="buktiPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="buktiTitle">Bukti Perjalanan</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" id="buktiImage" class="img-fluid rounded-3 border shadow-sm" alt="Bukti">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewButtons = document.querySelectorAll('.btn-preview-bukti');
    const modalElement = document.getElementById('buktiPreviewModal');
    const previewImage = document.getElementById('buktiImage');
    const modalTitle = document.getElementById('buktiTitle');
    
    // Inisialisasi Modal Bootstrap
    const myModal = new bootstrap.Modal(modalElement);

    previewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Mengambil URL (yang sekarang sudah aman) dan Judul dari data-attributes
            const url = this.getAttribute('data-url');
            const title = this.getAttribute('data-title');

            // Set sumber gambar dan judul modal
            previewImage.src = url;
            modalTitle.innerText = title;

            // Tampilkan modal
            myModal.show();
        });
    });
});
</script>
@endsection