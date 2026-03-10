@extends('layouts.admin_bootstrap')

@section('content')
{{-- TAMBAHAN 1: LINK GOOGLE FONT POPPINS & ANIMATE.CSS UNTUK TAMPILAN MODERN --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

{{-- TAMBAHAN 2: CUSTOM CSS UNTUK MEMBUAT SWEETALERT MENJADI ROUNDED & CANTIK --}}
<style>
    /* Mengubah font default SweetAlert menjadi Poppins */
    .swal2-popup {
        font-family: 'Poppins', sans-serif !important;
        border-radius: 25px !important; /* Membuat sudut pop-up sangat melengkung */
        padding: 2rem !important;
    }
    /* Membuat judul lebih besar dan tegas */
    .swal2-title {
        font-weight: 700 !important;
        font-size: 1.6rem !important;
    }
    /* Membuat teks isi lebih enak dibaca */
    .swal2-html-container {
        font-size: 1.1rem !important;
        color: #555 !important;
    }
    /* Membuat tombol menjadi rounded (pil) dan tebal */
    .swal2-confirm, .swal2-cancel {
        border-radius: 50px !important; /* Bentuk pil */
        font-weight: 600 !important;
        font-size: 1rem !important;
        padding: 12px 30px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; /* Bayangan halus */
        transition: all 0.3s ease;
    }
    .swal2-confirm:hover, .swal2-cancel:hover {
        transform: translateY(-2px); /* Efek naik sedikit saat di-hover */
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    }
    /* Mengubah ikon agar tidak terlalu kaku */
    .swal2-icon {
        border: none !important; /* Menghilangkan border lingkaran default */
        background-color: rgba(var(--bs-primary-rgb), 0.1); /* Background transparan halus */
        width: 5em !important;
        height: 5em !important;
        margin-bottom: 1.5rem !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0" style="font-family: 'Poppins', sans-serif;">Verifikasi Data BPJS</h3>
        <p class="text-muted small">Tinjau pendaftaran warga berdasarkan wilayah atau waktu input tertentu.</p>
    </div>
    <a href="{{ route('admin.export', array_merge(['type' => 'bpjs'], request()->all())) }}" class="btn btn-success shadow-sm rounded-pill px-4 fw-bold" style="font-family: 'Poppins', sans-serif;">
        <i class="fa-solid fa-file-excel me-2"></i> Export Excel
    </a>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
    <div class="card-body p-4 bg-light bg-gradient">
        <form action="{{ route('admin.bpjs') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary ls-1">KABUPATEN</label>
                <select name="kabupaten" id="filterKabupaten" class="form-select border-0 shadow-sm" style="border-radius: 15px;">
                    <option value="">Semua Kabupaten</option>
                    @foreach($listKabupaten as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary ls-1">PUSKESMAS PENGINPUT</label>
                <select name="puskesmas_id" id="filterPuskesmas" class="form-select border-0 shadow-sm" style="border-radius: 15px;">
                    <option value="">Semua Puskesmas</option>
                    @foreach($listPuskesmas as $p)
                        <option value="{{ $p->id }}" 
                                data-kabupaten="{{ $p->kabupaten }}" 
                                {{ request('puskesmas_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary ls-1">DARI TANGGAL</label>
                <input type="date" name="tanggal_mulai" class="form-control border-0 shadow-sm" style="border-radius: 15px;" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary ls-1">SAMPAI TANGGAL</label>
                <input type="date" name="tanggal_selesai" class="form-control border-0 shadow-sm" style="border-radius: 15px;" value="{{ request('tanggal_selesai') }}">
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill shadow fw-bold py-2">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                <a href="{{ route('admin.bpjs') }}" class="btn btn-light border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;" title="Reset Filter">
                    <i class="fa-solid fa-arrows-rotate text-secondary"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-primary bg-opacity-10 text-primary small text-uppercase fw-bold">
                <tr>
                    <th class="ps-4 py-3">Data Warga</th>
                    <th class="py-3">Puskesmas Pengirim</th>
                    <th class="py-3">Wilayah</th>
                    <th class="py-3">Dokumen</th>
                    <th class="py-3">Status</th>
                    <th class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark fs-6 mb-1">{{ $d->nama_warga }}</div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fa-regular fa-id-card me-1 text-primary opacity-50"></i> {{ $d->nik }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($d->petugas->name ?? 'P') }}&background=random&color=fff&size=40" class="rounded-circle shadow-sm me-2" alt="Petugas">
                            <div>
                                <span class="fw-bold d-block small text-dark">{{ $d->petugas->name ?? 'Puskesmas Terhapus' }}</span>
                                <small class="text-muted" style="font-size: 0.7rem;">
                                    <i class="fa-regular fa-clock me-1"></i> {{ $d->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border fw-normal rounded-pill px-3 mb-1 d-inline-block">
                            <i class="fa-solid fa-map-pin me-1 text-danger opacity-75"></i> {{ $d->petugas->kabupaten ?? '-' }}
                        </span>
                        <div class="small text-muted ms-2">{{ $d->petugas->kecamatan ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-bold text-primary btn-preview hover-scale" 
                                    data-url="{{ route('file.rahasia', ['path' => $d->foto_ktp]) }}" 
                                    data-title="KTP - {{ $d->nama_warga }}">
                                <i class="fa-regular fa-image me-1"></i> KTP
                            </button>
                            <button type="button" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-bold text-primary btn-preview hover-scale" 
                                    data-url="{{ route('file.rahasia', ['path' => $d->foto_kk]) }}" 
                                    data-title="KK - {{ $d->nama_warga }}">
                                <i class="fa-regular fa-image me-1"></i> KK
                            </button>
                        </div>
                    </td>
                    <td>
                        @if($d->status_verifikasi == 'acc')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold">
                                <i class="fa-solid fa-circle-check me-1"></i> Disetujui
                            </span>
                        @elseif($d->status_verifikasi == 'ditolak')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Ditolak
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-bold">
                                <i class="fa-regular fa-clock me-1"></i> Menunggu
                            </span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2 bg-light p-2 rounded-pill border mx-auto" style="width: fit-content;">
                            <form action="{{ route('admin.bpjs.approve', $d->id) }}" method="POST" class="form-konfirmasi" data-aksi="Menyetujui" data-nama="{{ $d->nama_warga }}" data-icon="success">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $d->status_verifikasi == 'acc' ? 'btn-light text-muted disabled border-0' : 'btn-success shadow-sm' }} rounded-circle d-flex align-items-center justify-content-center hover-scale" style="width: 35px; height: 35px;" title="Setujui">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <div class="vr opacity-25"></div>
                            <form action="{{ route('admin.bpjs.reject', $d->id) }}" method="POST" class="form-konfirmasi" data-aksi="Menolak" data-nama="{{ $d->nama_warga }}" data-icon="error">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $d->status_verifikasi == 'ditolak' ? 'btn-light text-muted disabled border-0' : 'btn-danger shadow-sm' }} rounded-circle d-flex align-items-center justify-content-center hover-scale" style="width: 35px; height: 35px;" title="Tolak">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Kosong" style="width: 100px; opacity: 0.5;">
                        <p class="text-muted fw-bold mt-3">Belum ada data pengajuan BPJS.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
            <div class="modal-header border-0 pb-0 bg-light pt-3">
                <h6 class="modal-title fw-bold text-dark" id="modalTitle" style="font-family: 'Poppins', sans-serif;">Preview Dokumen</h6>
                <button type="button" class="btn-close shadow-none bg-white rounded-circle p-2 m-2 position-absolute top-0 end-0 shadow-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4 bg-light">
                <img src="" id="previewImage" class="img-fluid rounded-4 shadow-sm border" style="max-height: 75vh; object-fit: contain;" alt="Preview">
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.08); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LOGIKA DROP DOWN DINAMIS ---
        const filterKab = document.getElementById('filterKabupaten');
        const filterPus = document.getElementById('filterPuskesmas');
        const puskesmasOptions = Array.from(filterPus.options);
        function updatePuskesmasDropdown() {
            const selectedKab = filterKab.value;
            filterPus.innerHTML = '';
            const filteredOptions = puskesmasOptions.filter(opt => {
                if (opt.value === "") return true; 
                return opt.getAttribute('data-kabupaten') === selectedKab || selectedKab === "";
            });
            filteredOptions.forEach(opt => filterPus.add(opt));
        }
        filterKab.addEventListener('change', updatePuskesmasDropdown);
        if(filterKab.value !== "") { updatePuskesmasDropdown(); }

        // --- 2. LOGIKA PREVIEW GAMBAR ---
        const previewButtons = document.querySelectorAll('.btn-preview');
        const modalElement = document.getElementById('imagePreviewModal');
        const previewImage = document.getElementById('previewImage');
        const modalTitle = document.getElementById('modalTitle');
        const myModal = new bootstrap.Modal(modalElement);
        previewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Gambar kini memuat URL yang aman, bukan folder public!
                previewImage.src = this.getAttribute('data-url');
                modalTitle.innerText = this.getAttribute('data-title');
                myModal.show();
            });
        });

        // --- 3. LOGIKA POP-UP SWEETALERT2 ---
        const formsKonfirmasi = document.querySelectorAll('.form-konfirmasi');
        formsKonfirmasi.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const aksi = this.getAttribute('data-aksi');
                const nama = this.getAttribute('data-nama');
                const iconType = this.getAttribute('data-icon'); // success atau error
                
                const warnaTombol = iconType === 'success' ? '#198754' : '#dc3545';
                const judul = iconType === 'success' ? 'Setujui Data?' : 'Tolak Data?';

                Swal.fire({
                    title: judul,
                    html: `Apakah Anda yakin ingin <b>${aksi.toLowerCase()}</b> pengajuan atas nama <br><span class="text-primary fw-bold" style="font-size: 1.2rem;">${nama}</span>?`,
                    icon: iconType,
                    showCancelButton: true,
                    confirmButtonColor: warnaTombol,
                    cancelButtonColor: '#e9ecef',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: '<span class="text-dark fw-bold">Batal</span>',
                    reverseButtons: true,
                    showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
                    hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' },
                    customClass: {
                        icon: 'border-0 shadow-sm bg-light bg-opacity-50 mb-4',
                        cancelButton: 'border-0 shadow-sm text-dark'
                    },
                    buttonsStyling: false, 
                    didOpen: () => {
                        Swal.getConfirmButton().classList.add('btn', iconType === 'success' ? 'btn-success' : 'btn-danger', 'rounded-pill', 'px-4', 'py-2', 'fw-bold', 'shadow', 'ms-2');
                        Swal.getCancelButton().classList.add('btn', 'btn-light', 'text-dark', 'rounded-pill', 'px-4', 'py-2', 'fw-bold', 'border', 'shadow-sm');
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Mohon tunggu sebentar.',
                            timer: 1000,
                            timerProgressBar: true,
                            didOpen: () => { Swal.showLoading() }
                        }).then(() => {
                             this.submit();
                        });
                    }
                });
            });
        });
    });
</script>
@endsection