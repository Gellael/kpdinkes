@extends('layouts.admin_bootstrap')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Menghilangkan panah pada input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield; 
    }

    /* Styling Header Biru */
    .header-gradient {
        background: linear-gradient(120deg, #0d6efd 0%, #0099ff 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
    }
    
    .glass-stat {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        padding: 15px;
        transition: transform 0.3s;
    }
    .glass-stat:hover { transform: translateY(-5px); }

    /* Custom Nav Tabs */
    .nav-modern .nav-link {
        color: #6c757d; font-weight: 600; border: none; background: transparent; border-bottom: 3px solid transparent; padding: 15px 25px;
    }
    .nav-modern .nav-link.active {
        color: #0d6efd; background: transparent; border-bottom: 3px solid #0d6efd;
    }

    /* Form Styling */
    .form-floating-custom > .form-control {
        border: 2px solid #f1f5f9; background-color: #f8fafc; border-radius: 12px; height: 55px;
    }
    .form-floating-custom > .form-control:focus {
        border-color: #0d6efd; background-color: #fff; box-shadow: none;
    }
    
    /* Area Upload */
    .upload-area {
        border: 2px dashed #cbd5e1; border-radius: 15px; background: #f8fafc; padding: 20px 15px; 
        text-align: center; transition: 0.3s; cursor: pointer; position: relative; overflow: hidden;
        height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;
    }
    .upload-area:hover { background: #eff6ff; border-color: #0d6efd; }
    .upload-area input[type="file"] {
        position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;
        z-index: 10;
    }

    /* Styling Kolom Pencarian Baru */
    .search-box {
        background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; padding: 8px 20px;
        display: flex; align-items: center; width: 100%; max-width: 350px; transition: 0.3s;
    }
    .search-box:focus-within { border-color: #0d6efd; background-color: #fff; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }
    .search-box input { border: none; background: transparent; outline: none; width: 100%; padding-left: 10px; }
</style>

<div class="container-fluid px-0">

    <div class="header-gradient mb-4">
        <div class="d-flex justify-content-between align-items-end position-relative" style="z-index: 2;">
            <div>
                <h3 class="fw-bold mb-1">Portal Pendaftaran BPJS</h3>
                <p class="mb-0 opacity-75">Halo, {{ Auth::user()->name }}. Mari bantu warga mendapatkan hak kesehatannya.</p>
            </div>
            <div class="d-flex gap-3 d-none d-md-flex">
                <div class="glass-stat text-center px-4">
                    <h2 class="fw-bold mb-0">{{ $myData->count() }}</h2>
                    <small class="text-white-50">Total Input</small>
                </div>
                <div class="glass-stat text-center px-4">
                    <h2 class="fw-bold mb-0 text-warning">{{ $myData->where('status_verifikasi', 'pending')->count() }}</h2>
                    <small class="text-white-50">Pending</small>
                </div>
            </div>
        </div>
        <i class="fa-solid fa-file-medical position-absolute" style="font-size: 150px; right: -20px; bottom: -40px; opacity: 0.1;"></i>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white mb-5">
        <div class="card-header bg-white border-bottom-0 pt-3 px-4">
            <ul class="nav nav-tabs nav-modern" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="form-tab" data-bs-toggle="tab" data-bs-target="#form" type="button">
                        <i class="fa-solid fa-plus-circle me-2"></i> Input Data Baru
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pengajuan
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="myTabContent">
                
                <div class="tab-pane fade show active" id="form">
                    <form action="{{ route('puskesmas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-lg-5">
                                <h6 class="text-primary fw-bold mb-3"><i class="fa-regular fa-id-card me-2"></i> DATA PRIBADI WARGA</h6>
                                
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" name="nama_warga" class="form-control" id="inputNama" placeholder="Nama" required>
                                    <label for="inputNama">Nama Lengkap (Sesuai KTP)</label>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-custom">
                                            <input type="text" inputmode="numeric" name="nik" class="form-control" id="inputNik" placeholder="NIK" maxlength="16" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <label for="inputNik">NIK (16 Digit)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-custom">
                                            <input type="text" inputmode="numeric" name="no_hp" class="form-control" id="inputHp" placeholder="HP" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <label for="inputHp">No. WhatsApp</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating form-floating-custom">
                                    <textarea name="alamat" class="form-control h-100" id="inputAlamat" rows="3" placeholder="Alamat" style="min-height: 120px" required></textarea>
                                    <label for="inputAlamat">Alamat Domisili Lengkap</label>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-cloud-arrow-up me-2"></i> DOKUMEN PENDUKUNG</h6>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="upload-area" id="area-ktp">
                                            <i class="fa-regular fa-id-card fa-2x text-secondary mb-2" id="icon-ktp"></i>
                                            <h6 class="fw-bold mb-0 text-dark small" id="text-ktp">1. Upload KTP</h6>
                                            <small class="text-muted" style="font-size: 0.7rem;">Wajib (Max 2MB)</small>
                                            <input type="file" name="foto_ktp" id="input-ktp" accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="upload-area" id="area-kk">
                                            <i class="fa-solid fa-users-rectangle fa-2x text-secondary mb-2" id="icon-kk"></i>
                                            <h6 class="fw-bold mb-0 text-dark small" id="text-kk">2. Upload KK</h6>
                                            <small class="text-muted" style="font-size: 0.7rem;">Wajib (Max 2MB)</small>
                                            <input type="file" name="foto_kk" id="input-kk" accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="upload-area" id="area-sktm">
                                            <i class="fa-solid fa-file-signature fa-2x text-secondary mb-2" id="icon-sktm"></i>
                                            <h6 class="fw-bold mb-0 text-dark small" id="text-sktm">3. Upload SKTM</h6>
                                            <small class="text-muted" style="font-size: 0.7rem;">Wajib (Max 2MB)</small>
                                            <input type="file" name="foto_sktm" id="input-sktm" accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="upload-area" id="area-rawat">
                                            <i class="fa-solid fa-bed-pulse fa-2x text-secondary mb-2" id="icon-rawat"></i>
                                            <h6 class="fw-bold mb-0 text-dark small" id="text-rawat">4. Foto Rawat</h6>
                                            <small class="text-muted" style="font-size: 0.7rem;">*Opsional (Max 2MB)</small>
                                            <input type="file" name="foto_rawat" id="input-rawat" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                    <i class="fa-solid fa-paper-plane me-2"></i> KIRIM PENDAFTARAN BPJS
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="history">
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <h6 class="fw-bold text-dark mb-0 d-none d-md-block">Daftar Warga Terdaftar</h6>
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            <input type="text" id="searchInput" placeholder="Cari nama atau status...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="py-3 ps-3">Tanggal Input</th>
                                    <th>Identitas Warga</th>
                                    <th>Status Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($myData as $data)
                                <tr class="data-row">
                                    <td class="ps-3 text-muted" style="width: 180px;">
                                        <i class="fa-regular fa-calendar me-2"></i> {{ $data->created_at->format('d/m/Y') }}
                                        <br>
                                        <small class="ms-4">{{ $data->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="d-block fw-bold text-dark fs-6">{{ $data->nama_warga }}</span>
                                        <small class="d-block text-muted mt-1"><i class="fa-brands fa-whatsapp me-1 text-success"></i> {{ $data->no_hp }}</small>
                                    </td>
                                    
                                    <td>
                                        @if($data->status_verifikasi == 'pending')
                                            <span class="badge bg-warning text-dark bg-opacity-25 border border-warning px-3 py-2 rounded-pill status-badge">
                                                <i class="fa-regular fa-clock me-1"></i> Menunggu
                                            </span>
                                        @elseif($data->status_verifikasi == 'acc')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill status-badge">
                                                <i class="fa-solid fa-check-circle me-1"></i> Diterima
                                            </span>
                                        @else
                                            <div>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill status-badge mb-2">
                                                    <i class="fa-solid fa-xmark-circle me-1"></i> Ditolak
                                                </span>
                                                <div class="small p-2 bg-danger bg-opacity-10 text-danger rounded-3 border border-danger border-opacity-25 mt-1" style="max-width: 300px; font-size: 0.75rem;">
                                                    <strong>Alasan:</strong> {{ $data->alasan_ditolak ?? 'Tidak ada alasan.' }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyStateRow">
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                                            <p>Belum ada riwayat inputan data.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. SCRIPT UPLOAD GAMBAR (AGAR NAMA FILE MUNCUL & BISA DIKLIK)
        function handleFileInput(inputId, textId, iconId, areaId, defaultText) {
            const input = document.getElementById(inputId);
            const textElement = document.getElementById(textId);
            const iconElement = document.getElementById(iconId);
            const areaElement = document.getElementById(areaId);

            if(!input) return; 

            input.addEventListener('change', function(e) {
                if (this.files && this.files.length > 0) {
                    let fileName = this.files[0].name;
                    if (fileName.length > 20) { fileName = fileName.substring(0, 17) + '...'; }

                    // Ubah jadi Hijau
                    textElement.innerHTML = '<span class="text-success"><i class="fa-solid fa-check me-1"></i> ' + fileName + '</span>';
                    iconElement.className = 'fa-solid fa-file-circle-check fa-2x text-success mb-2';
                    areaElement.style.borderColor = '#198754';
                    areaElement.style.backgroundColor = '#f8fff9';
                } else {
                    // Reset
                    textElement.innerHTML = defaultText;
                    
                    // Kembalikan icon sesuai jenis dokumen masing-masing
                    if(inputId === 'input-ktp') iconElement.className = 'fa-regular fa-id-card fa-2x text-secondary mb-2';
                    else if(inputId === 'input-kk') iconElement.className = 'fa-solid fa-users-rectangle fa-2x text-secondary mb-2';
                    else if(inputId === 'input-sktm') iconElement.className = 'fa-solid fa-file-signature fa-2x text-secondary mb-2';
                    else iconElement.className = 'fa-solid fa-bed-pulse fa-2x text-secondary mb-2';

                    areaElement.style.borderColor = '#cbd5e1';
                    areaElement.style.backgroundColor = '#f8fafc';
                }
            });
        }
        
        // Terapkan fungsi ke ke-4 kotak upload
        handleFileInput('input-ktp', 'text-ktp', 'icon-ktp', 'area-ktp', '1. Upload KTP');
        handleFileInput('input-kk', 'text-kk', 'icon-kk', 'area-kk', '2. Upload KK');
        handleFileInput('input-sktm', 'text-sktm', 'icon-sktm', 'area-sktm', '3. Upload SKTM');
        handleFileInput('input-rawat', 'text-rawat', 'icon-rawat', 'area-rawat', '4. Foto Rawat');


        // 2. SCRIPT PENCARIAN REAL-TIME
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.data-row');

        if(searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                
                rows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    if(textContent.includes(term)) {
                        row.style.display = ''; 
                    } else {
                        row.style.display = 'none'; 
                    }
                });
            });
        }
    });
</script>
@endsection