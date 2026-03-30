@extends('layouts.admin_bootstrap')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body { font-family: 'Poppins', sans-serif; }
    .swal2-popup { font-family: 'Poppins', sans-serif !important; border-radius: 25px !important; padding: 2rem !important; }
    .swal2-title { font-weight: 700 !important; font-size: 1.6rem !important; }
    .swal2-confirm, .swal2-cancel { border-radius: 50px !important; font-weight: 600 !important; padding: 12px 30px !important; transition: all 0.3s ease; }
    .swal2-icon { border: none !important; background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .doc-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 50px;
        font-size: 0.75rem; font-weight: 700; text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid transparent; cursor: pointer;
    }
    .doc-ktp { background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .doc-ktp:hover { background-color: #2563eb; color: #ffffff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
    .doc-kk { background-color: #f5f3ff; color: #4f46e5; border-color: #ddd6fe; }
    .doc-kk:hover { background-color: #4f46e5; color: #ffffff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
    .doc-sktm { background-color: #f0fdf4; color: #0f766e; border-color: #bbf7d0; }
    .doc-sktm:hover { background-color: #0f766e; color: #ffffff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2); }
    .doc-rawat { background-color: #fffbeb; color: #b45309; border-color: #fde68a; }
    .doc-rawat:hover { background-color: #b45309; color: #ffffff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(180, 83, 9, 0.2); }
    .filter-card {
        background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); padding: 1.5rem;
    }
    .form-label-custom { font-size: 0.7rem; font-weight: 700; color: #64748b; letter-spacing: 1px; margin-bottom: 0.4rem; text-transform: uppercase; }
    .input-modern {
        background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
        padding: 0.6rem 1rem; font-size: 0.9rem; color: #334155; transition: all 0.2s; width: 100%; height: 42px;
    }
    .input-modern:focus { background-color: #ffffff; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); outline: none; }
    
    .table-container { background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
    .table-modern { margin-bottom: 0; }
    .table-modern thead th {
        background: #f8fafc; color: #64748b; font-size: 0.75rem; font-weight: 700; 
        text-transform: uppercase; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; border-top: none;
    }
    .table-modern tbody td { padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .table-modern tbody tr:last-child td { border-bottom: none; }
    .table-modern tbody tr:hover td { background-color: #f8fafc; }


    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.08); }
    .empty-state-icon {
        width: 80px; height: 80px; background: #f1f5f9; color: #94a3b8;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%; font-size: 2rem; margin-bottom: 1rem;
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-0">Verifikasi Data BPJS</h3>
        <p class="text-muted small mb-0 mt-1">Tinjau pendaftaran warga berdasarkan wilayah atau waktu input tertentu.</p>
    </div>
    <a href="{{ route('admin.export', array_merge(['type' => 'bpjs'], request()->all())) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center" style="height: 42px; transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fa-solid fa-file-excel me-2"></i> Export Excel
    </a>
</div>

<div class="filter-card mb-4 animate__animated animate__fadeIn">
    <form action="{{ route('admin.bpjs') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label-custom d-block">Kabupaten</label>
            <select name="kabupaten" id="filterKabupaten" class="input-modern">
                <option value="">-- Semua Kabupaten --</option>
                @foreach($listKabupaten as $kab)
                    <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label-custom d-block">Petugas Penginput</label>
            <select name="puskesmas_id" id="filterPuskesmas" class="input-modern">
                <option value="">-- Semua Penginput --</option>
                @foreach($listPuskesmas as $p)
                    <option value="{{ $p->id }}" data-kabupaten="{{ $p->kabupaten }}" {{ request('puskesmas_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label-custom d-block">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" class="input-modern" value="{{ request('tanggal_mulai') }}">
        </div>
        
        <div class="col-md-2">
            <label class="form-label-custom d-block">Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" class="input-modern" value="{{ request('tanggal_selesai') }}">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm fw-bold" style="height: 42px;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            <a href="{{ route('admin.bpjs') }}" class="btn btn-light border shadow-sm rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;" title="Reset Filter">
                <i class="fa-solid fa-arrows-rotate text-secondary"></i>
            </a>
        </div>
    </form>
</div>

<div class="table-container animate__animated animate__fadeIn animate__delay-1s">
    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Data Warga</th>
                    <th>Petugas Penginput</th>
                    <th>Wilayah Domisili</th>
                    <th style="width: 25%;">Kelengkapan Dokumen</th>
                    <th style="width: 15%;">Status</th>
                    <th class="text-center">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark fs-6 mb-1">{{ $d->nama_warga }}</div>
                        <div class="d-flex align-items-center text-secondary" style="font-size: 0.8rem;">
                            <i class="fa-regular fa-id-card me-2 text-primary opacity-50"></i> {{ $d->nik }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px; flex-shrink:0;">
                                {{ substr($d->petugas->name ?? 'P', 0, 1) }}
                            </div>
                            <div>
                                <span class="fw-bold d-block text-dark" style="font-size: 0.9rem;">{{ $d->petugas->name ?? 'Penginput Terhapus' }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-clock me-1"></i> {{ $d->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1 text-secondary fw-semibold mb-1" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-map-location-dot me-2 text-danger opacity-75"></i> {{ $d->petugas->kabupaten ?? '-' }}
                        </div>
                        <div class="text-muted ms-2" style="font-size: 0.8rem;">Kec. {{ $d->petugas->kecamatan ?? '-' }}</div>
                    </td>
                    
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="doc-badge doc-ktp btn-preview" data-url="{{ $d->foto_ktp }}" data-title="KTP - {{ $d->nama_warga }}">
                                <i class="fa-solid fa-address-card"></i> KTP
                            </button>
                            
                            <button type="button" class="doc-badge doc-kk btn-preview" data-url="{{ $d->foto_kk }}" data-title="KK - {{ $d->nama_warga }}">
                                <i class="fa-solid fa-users-rectangle"></i> KK
                            </button>
                            
                            @if(!empty($d->foto_sktm))
                            <button type="button" class="doc-badge doc-sktm btn-preview" data-url="{{ $d->foto_sktm }}" data-title="SKTM - {{ $d->nama_warga }}">
                                <i class="fa-solid fa-file-signature"></i> SKTM
                            </button>
                            @endif

                            @if(!empty($d->foto_rawat))
                            <button type="button" class="doc-badge doc-rawat btn-preview" data-url="{{ $d->foto_rawat }}" data-title="Foto Rawat - {{ $d->nama_warga }}">
                                <i class="fa-solid fa-bed-pulse"></i> Rawat
                            </button>
                            @endif
                        </div>
                    </td>

                    <td>
                        @if($d->status_verifikasi == 'acc')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold">
                                <i class="fa-solid fa-circle-check me-1"></i> Disetujui
                            </span>
                        @elseif($d->status_verifikasi == 'ditolak')
                            <div>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold mb-2">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Ditolak
                                </span>
                                <div class="p-2 bg-danger bg-opacity-10 text-danger rounded-3 border border-danger border-opacity-25 mt-1" style="font-size: 0.7rem; line-height: 1.4;">
                                    <strong>Ket:</strong> {{ $d->alasan_ditolak ?? 'Tidak ada alasan.' }}
                                </div>
                            </div>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-bold">
                                <i class="fa-regular fa-clock me-1"></i> Menunggu
                            </span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2 bg-light p-1 rounded-pill border mx-auto" style="width: fit-content;">
                            <form action="{{ route('admin.bpjs.approve', $d->id) }}" method="POST" class="form-konfirmasi" data-aksi="Menyetujui" data-nama="{{ $d->nama_warga }}" data-icon="success">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $d->status_verifikasi == 'acc' ? 'btn-light text-muted disabled border-0' : 'btn-success shadow-sm' }} rounded-circle d-flex align-items-center justify-content-center hover-scale" style="width: 36px; height: 36px;" title="Setujui Pengajuan">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <div class="vr opacity-25 my-1"></div>
                            <form action="{{ route('admin.bpjs.reject', $d->id) }}" method="POST" class="form-konfirmasi" data-aksi="Menolak" data-nama="{{ $d->nama_warga }}" data-icon="error">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $d->status_verifikasi == 'ditolak' ? 'btn-light text-muted disabled border-0' : 'btn-danger shadow-sm' }} rounded-circle d-flex align-items-center justify-content-center hover-scale" style="width: 36px; height: 36px;" title="Tolak Pengajuan">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state-icon">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan</h6>
                        <p class="text-muted small mb-0">Data warga yang diinput oleh petugas akan muncul di sini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-light p-4 pb-3">
                <h6 class="modal-title fw-bold text-dark" id="modalTitle">Preview Dokumen</h6>
                <button type="button" class="btn-close shadow-none bg-white rounded-circle p-2 position-absolute top-0 end-0 mt-3 me-3 shadow-sm border" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4 bg-light pt-0">
                <img src="" id="previewImage" class="img-fluid rounded-3 shadow-sm border bg-white" style="max-height: 70vh; object-fit: contain; padding: 5px;" alt="Preview">
            </div>
        </div>
    </div>
</div>

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
                const iconType = this.getAttribute('data-icon'); 
                
                // JIKA AKSI MENOLAK (Munculkan Textarea)
                if (iconType === 'error') {
                    Swal.fire({
                        title: 'Tolak Pengajuan?',
                        html: `Ketik alasan penolakan untuk warga <br><span class="text-danger fw-bold fs-5">${nama}</span>:`,
                        input: 'textarea',
                        inputPlaceholder: 'Contoh: Foto KTP buram, NIK tidak sesuai...',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#e9ecef',
                        confirmButtonText: '<i class="fa-solid fa-paper-plane me-1"></i> Tolak & Kirim',
                        cancelButtonText: '<span class="text-dark fw-bold">Batal</span>',
                        reverseButtons: true,
                        customClass: {
                            icon: 'border-0 shadow-sm bg-light bg-opacity-50 mb-3',
                            cancelButton: 'border-0 shadow-sm text-dark'
                        },
                        inputValidator: (value) => {
                            if (!value) { return 'Alasan penolakan wajib diisi!' }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const inputAlasan = document.createElement('input');
                            inputAlasan.type = 'hidden';
                            inputAlasan.name = 'alasan_ditolak';
                            inputAlasan.value = result.value;
                            this.appendChild(inputAlasan);
                            
                            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                            this.submit();
                        }
                    });
                } 
                // JIKA AKSI MENYETUJUI
                else {
                    Swal.fire({
                        title: 'Setujui Data?',
                        html: `Yakin ingin menyetujui pengajuan <br><span class="text-success fw-bold fs-5">${nama}</span>?`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#e9ecef',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: '<span class="text-dark fw-bold">Batal</span>',
                        reverseButtons: true,
                        customClass: {
                            icon: 'border-0 shadow-sm bg-light bg-opacity-50 mb-3',
                            cancelButton: 'border-0 shadow-sm text-dark'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                            this.submit();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection