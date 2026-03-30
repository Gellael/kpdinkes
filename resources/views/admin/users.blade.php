@extends('layouts.admin_bootstrap')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* --- FONT & RESET --- */
    body, .card, button, input, select, .accordion-button {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; appearance: textfield; }

    /* --- TYPOGRAPHY & HEADER --- */
    .page-title { font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
    
    /* --- MODERN TABS (SAAS STYLE) --- */
    .nav-pills-wrapper {
        overflow-x: auto; -ms-overflow-style: none; scrollbar-width: none; padding-bottom: 10px;
    }
    .nav-pills-wrapper::-webkit-scrollbar { display: none; }
    
    .nav-pills-custom {
        background-color: #f1f5f9; border-radius: 100px; padding: 6px; display: inline-flex; gap: 8px; border: none; width: max-content;
    }
    .nav-pills-custom .nav-link {
        color: #64748b; font-weight: 600; border-radius: 100px; padding: 10px 24px; transition: all 0.3s ease; border: none; background: transparent; display: flex; align-items: center; gap: 8px;
    }
    .nav-pills-custom .nav-link:hover { color: #0f172a; }
    .nav-pills-custom .nav-link.active {
        background-color: #ffffff; color: #2563eb; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    .badge-tab {
        background: #e2e8f0; color: #475569; border-radius: 50px; padding: 4px 10px; font-size: 0.75rem; transition: 0.3s; font-weight: 700;
    }
    .nav-link.active .badge-tab { background: #dbeafe; color: #2563eb; }

    /* --- AVATAR MODERN --- */
    .avatar-modern {
        width: 45px; height: 45px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.2rem; flex-shrink: 0;
    }

    /* --- ACCORDION CARDS --- */
    .accordion-custom { display: flex; flex-direction: column; gap: 16px; }
    .accordion-custom .accordion-item {
        border: 1px solid #e2e8f0; border-radius: 20px !important; background: #ffffff; overflow: hidden; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .accordion-custom .accordion-item:hover { border-color: #cbd5e1; box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); }
    .accordion-custom .accordion-button {
        padding: 20px 24px; background: #ffffff; border-radius: 20px !important; box-shadow: none !important;
    }
    .accordion-custom .accordion-button:not(.collapsed) {
        background: #f8fafc; border-bottom-left-radius: 0 !important; border-bottom-right-radius: 0 !important; border-bottom: 1px solid #e2e8f0;
    }
    .icon-box {
        width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;
    }

    /* --- TABLE MODERNIZATION --- */
    .table-card { border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .table-modern { margin-bottom: 0; }
    .table-modern thead th {
        background: #f8fafc; color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; border-top: none;
    }
    .table-modern tbody td {
        padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; background: #ffffff; color: #334155;
    }
    .table-modern tbody tr:last-child td { border-bottom: none; }
    .table-modern tbody tr:hover td { background-color: #f8fafc; }

    /* --- ACTION BUTTONS --- */
    .btn-action {
        width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; transition: all 0.2s; border: none; background: #f1f5f9; cursor: pointer; text-decoration: none;
    }
    .btn-action.edit { color: #3b82f6; }
    .btn-action.edit:hover { background: #dbeafe; color: #1d4ed8; transform: translateY(-2px); }
    .btn-action.delete { color: #ef4444; }
    .btn-action.delete:hover { background: #fee2e2; color: #b91c1c; transform: translateY(-2px); }

    /* --- MOBILE RESPONSIVE (HYBRID CARDS) --- */
    @media screen and (max-width: 768px) {
        .table-responsive-mobile thead { display: none; }
        .table-responsive-mobile tbody { padding: 16px; display: block; background: #f8fafc;}
        .table-responsive-mobile tbody tr {
            display: flex; flex-direction: column; background-color: #fff; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 16px; padding: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        }
        .table-responsive-mobile tbody td {
            display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border: none; text-align: right; background: transparent;
        }
        .table-responsive-mobile tbody tr:hover td { background-color: transparent; }
        .table-responsive-mobile tbody td::before {
            content: attr(data-label); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8; text-align: left; margin-right: 15px; letter-spacing: 0.5px;
        }
        .table-responsive-mobile tbody td.col-nama {
            justify-content: flex-start; gap: 15px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 16px; margin-bottom: 8px;
        }
        .table-responsive-mobile tbody td.col-nama::before { display: none; }
        .table-responsive-mobile tbody td.col-aksi {
            justify-content: center; margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 16px;
        }
        .table-responsive-mobile tbody td.col-aksi::before { display: none; }
        
        .accordion-custom .accordion-button { padding: 16px; }
        .icon-box { width: 40px; height: 40px; font-size: 1rem; }
    }
</style>

<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="page-title mb-1">Manajemen Pengguna</h2>
            <p class="text-muted mb-0">Kelola akses sistem untuk Administrator, Puskesmas, dan Driver.</p>
        </div>
        <button class="btn btn-primary fw-bold shadow rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#addUserModal" style="box-shadow: 0 8px 15px -3px rgba(37, 99, 235, 0.3) !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fa-solid fa-plus me-2"></i> Tambah Pengguna
        </button>
    </div>

    <div class="nav-pills-wrapper mb-4">
        <ul class="nav nav-pills-custom" id="userTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-panel" type="button">
                    <i class="fa-solid fa-user-shield"></i> Admin Sistem <span class="badge-tab">{{ $admins->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="puskesmas-tab" data-bs-toggle="tab" data-bs-target="#puskesmas-panel" type="button">
                    <i class="fa-solid fa-hospital-user"></i> Puskesmas <span class="badge-tab">{{ $puskesmas->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="driver-tab" data-bs-toggle="tab" data-bs-target="#driver-panel" type="button">
                    <i class="fa-solid fa-truck-medical"></i> Ambulan <span class="badge-tab">{{ $drivers->count() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="userTabsContent">
        
        <div class="tab-pane fade show active" id="admin-panel">
            <div class="table-card">
                <table class="table table-modern table-responsive-mobile">
                    <thead>
                        <tr>
                            <th class="ps-md-4">Nama Admin</th>
                            <th>Email Login</th>
                            <th>Hak Akses</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $u)
                        <tr>
                            <td class="col-nama ps-md-4" data-label="Nama Admin">
                                <div class="d-flex align-items-center w-100">
                                    <div class="avatar-modern text-white me-3 shadow-sm" style="background-color: #1e293b;">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark text-start" style="font-size: 0.95rem;">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td data-label="Email"><span class="text-muted">{{ $u->email }}</span></td>
                            <td data-label="Hak Akses">
                                <span class="badge bg-dark bg-opacity-10 text-dark border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-bold">Administrator</span>
                            </td>
                            <td class="col-aksi text-center">
                                @if(Auth::id() != $u->id) 
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.users.edit', $u->id) }}" class="btn-action edit"><i class="fa-solid fa-pen"></i></a>
                                        <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-action delete btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Sedang Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="puskesmas-panel">
            <div class="accordion accordion-custom" id="accordionPuskesmas">
                @php $groupedPuskesmas = $puskesmas->groupBy('kabupaten'); @endphp
                @forelse($groupedPuskesmas as $kabupaten => $items)
                    @php $targetIdP = str_replace([' ', '.'], '_', $kabupaten ?? 'Lain_lain'); @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseP_{{ $targetIdP }}">
                                <div class="d-flex align-items-center justify-content-between w-100 me-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 lh-1">{{ strtoupper($kabupaten ?? 'Wilayah Belum Terdata') }}</h6>
                                            <small class="text-muted mt-1 d-block fw-semibold">{{ $items->count() }} Faskes Terdaftar</small>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseP_{{ $targetIdP }}" class="accordion-collapse collapse" data-bs-parent="#accordionPuskesmas">
                            <div class="accordion-body p-0 border-top">
                                <table class="table table-modern table-responsive-mobile">
                                    <thead>
                                        <tr>
                                            <th class="ps-md-4">Nama Puskesmas</th>
                                            <th>Kecamatan</th>
                                            <th>Email Login</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $u)
                                        <tr>
                                            <td class="col-nama ps-md-4" data-label="Puskesmas">
                                                <div class="d-flex align-items-center w-100">
                                                    <div class="avatar-modern bg-primary bg-opacity-10 text-primary me-3">
                                                        {{ substr($u->name, 0, 1) }}
                                                    </div>
                                                    <span class="fw-bold text-dark text-start" style="font-size: 0.95rem;">{{ $u->name }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Kecamatan">
                                                <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1 text-secondary fw-semibold" style="font-size: 0.85rem;">
                                                    <i class="fa-solid fa-location-dot me-2 text-muted"></i> {{ $u->kecamatan ?? '-' }}
                                                </div>
                                            </td>
                                            <td data-label="Email"><span class="text-muted">{{ $u->email }}</span></td>
                                            <td class="col-aksi text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn-action edit"><i class="fa-solid fa-pen"></i></a>
                                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="d-inline form-delete">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-action delete btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; color: #cbd5e1;">
                            <i class="fa-solid fa-hospital-user"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">Belum ada data Petugas Puskesmas.</h6>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="driver-panel">
            <div class="accordion accordion-custom" id="accordionDriver">
                @php $groupedDrivers = $drivers->groupBy('kabupaten'); @endphp
                @forelse($groupedDrivers as $kabupaten => $items)
                    @php $targetIdD = str_replace([' ', '.'], '_', $kabupaten ?? 'Lain_lain'); @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseD_{{ $targetIdD }}">
                                <div class="d-flex align-items-center justify-content-between w-100 me-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                                            <i class="fa-solid fa-truck-medical"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 lh-1">{{ strtoupper($kabupaten ?? 'Wilayah Belum Terdata') }}</h6>
                                            <small class="text-muted mt-1 d-block fw-semibold">{{ $items->count() }} Pengemudi Siaga</small>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseD_{{ $targetIdD }}" class="accordion-collapse collapse" data-bs-parent="#accordionDriver">
                            <div class="accordion-body p-0 border-top">
                                <table class="table table-modern table-responsive-mobile">
                                    <thead>
                                        <tr>
                                            <th class="ps-md-4">Nama Pengemudi</th>
                                            <th>Area Penugasan</th>
                                            <th>Kontak Cepat</th>
                                            <th>Kepala Desa</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $u)
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $u->no_hp);
                                            $waFormat = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : (str_starts_with($cleanPhone, '8') ? '62' . $cleanPhone : $cleanPhone);
                                        @endphp
                                        <tr>
                                            <td class="col-nama ps-md-4" data-label="Pengemudi">
                                                <div class="d-flex align-items-center w-100">
                                                    <div class="avatar-modern bg-danger bg-opacity-10 text-danger me-3">
                                                        {{ substr($u->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark text-start d-block" style="font-size: 0.95rem;">{{ $u->name }}</span>
                                                        @if($u->nopol)
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 mt-1">
                                                            <i class="fa-solid fa-car-side me-1"></i> {{ $u->nopol }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Wilayah Tugas">
                                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $u->kecamatan ?? '-' }}</div>
                                                <div class="text-muted" style="font-size: 0.8rem;"><i class="fa-solid fa-map-pin me-1 opacity-50"></i> {{ $u->unit_kerja ?? 'Desa belum diatur' }}</div>
                                            </td>
                                            <td data-label="WhatsApp">
                                                @if($u->no_hp)
                                                <a href="https://wa.me/{{ $waFormat }}" target="_blank" class="btn btn-sm btn-light text-success fw-bold rounded-pill px-3 shadow-sm border border-success-subtle hover-scale" style="transition: 0.2s;">
                                                    <i class="fa-brands fa-whatsapp me-1" style="font-size: 1.1em;"></i> Chat WA
                                                </a>
                                                @else
                                                <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td data-label="Kepala Desa">
                                                <div class="d-inline-flex align-items-center bg-light rounded-3 px-3 py-2 text-secondary" style="font-size: 0.85rem;">
                                                    <i class="fa-solid fa-user-tie me-2 text-muted"></i> {{ $u->nama_kepala_desa ?? 'Belum Diisi' }}
                                                </div>
                                            </td>
                                            <td class="col-aksi text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn-action edit"><i class="fa-solid fa-pen"></i></a>
                                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="d-inline form-delete">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-action delete btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; color: #cbd5e1;">
                            <i class="fa-solid fa-truck-medical"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">Belum ada data Driver Ambulan.</h6>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4" style="overflow: hidden;">
            <div class="modal-header border-bottom-0 bg-light pb-2 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-user-plus text-primary me-2"></i> Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.create_user') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">1. Pilih Peran (Role)</label>
                        <select name="role" id="roleSelect" class="form-select form-select-lg bg-light border-0 shadow-sm" style="border-radius: 12px; font-size: 0.95rem;" required onchange="updateFormLabel()">
                            <option value="" selected disabled>-- Pilih Jabatan Pengguna --</option>
                            <option value="puskesmas">Petugas Puskesmas (BPJS)</option>
                            <option value="ambulan">Driver Ambulan</option>
                            <option value="admin">Administrator Sistem</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase" id="labelNama">Nama Lengkap</label>
                        <input type="text" name="name" id="inputNama" class="form-control" style="border-radius: 10px; padding: 12px 15px;" placeholder="Masukkan nama..." required>
                    </div>

                    <div id="locationBox" class="p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 mb-4" style="display: none;">
                        <h6 class="fw-bold small text-primary mb-3"><i class="fa-solid fa-map-location-dot me-1"></i> AREA PENUGASAN</h6>
                        <div class="mb-3">
                            <label class="small fw-bold text-dark mb-1">Kabupaten / Kota</label>
                            <select name="kabupaten" id="kabupatenSelectModal" class="form-select shadow-sm" style="border-radius: 10px;" onchange="populateKecamatanModal()">
                                <option value="">-- Pilih Kabupaten --</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold text-dark mb-1">Kecamatan</label>
                            <select name="kecamatan" id="kecamatanSelectModal" class="form-select shadow-sm" style="border-radius: 10px;">
                                <option value="">-- Pilih Kabupaten Dulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3" id="unitKerjaBox" style="display: none;">
                        <label class="form-label fw-bold text-secondary small text-uppercase" id="unitLabel">Unit Kerja</label>
                        <input type="text" name="unit_kerja" id="inputUnit" class="form-control" style="border-radius: 10px; padding: 12px 15px;" placeholder="..." required>
                    </div>

                    <div class="mb-3" id="nopolBox" style="display: none;">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Nomor Polisi (NOPOL) Ambulan</label>
                        <input type="text" name="nopol" class="form-control text-uppercase" style="border-radius: 10px; padding: 12px 15px;" placeholder="Contoh: BD 1234 XY">
                        <div class="form-text mt-1 text-muted" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> Input plat nomor khusus untuk pendataan armada ambulan.
                        </div>
                    </div>

                    <div class="mb-3" id="noHpBox" style="display: none;">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Nomor HP / WhatsApp Aktif</label>
                        <input type="number" name="no_hp" class="form-control" style="border-radius: 10px; padding: 12px 15px;" placeholder="08xxx...">
                    </div>

                    <div class="row g-3 mb-4 mt-1">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Email (Login)</label>
                            <input type="email" name="email" class="form-control" style="border-radius: 10px; padding: 12px 15px;" placeholder="user@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control" style="border-radius: 10px; padding: 12px 15px;" placeholder="Min. 6 Karakter" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-white text-muted fw-bold rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold"><i class="fa-solid fa-save me-2"></i> Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // DATA WILAYAH PROVINSI BENGKULU
    const wilayahBengkulu = {
        "Kota Bengkulu": ["Gading Cempaka", "Kampung Melayu", "Muara Bangka Hulu", "Ratu Agung", "Ratu Samban", "Selebar", "Singaran Pati", "Sungai Serut", "Teluk Segara"],
        "Bengkulu Selatan": ["Kedurang", "Seginim", "Pino", "Manna", "Kota Manna", "Pasar Manna", "Pino Raya", "Bunga Mas", "Air Nipis", "Ulu Manna", "Kedurang Ilir"],
        "Bengkulu Tengah": ["Karang Tinggi", "Talang Empat", "Pondok Kelapa", "Pematang Tiga", "Pagar Jati", "Taba Penanjung", "Merigi Kelindang", "Merigi Sakti", "Bang Haji", "Pondok Kubang"],
        "Bengkulu Utara": ["Arga Makmur", "Arma Jaya", "Batik Nau", "Giri Mulya", "Air Padang", "Air Besi", "Air Napal", "Enggano", "Hulu Palik", "Kerkap", "Ketahun", "Lais", "Marga Sakti Sebelat", "Napal Putih", "Padang Jaya", "Pinang Raya", "Putri Hijau", "Tanjung Agung Palik", "Ulok Kupai"],
        "Kaur": ["Kaur Selatan", "Kaur Tengah", "Kaur Utara", "Kelam Tengah", "Kinal", "Luas", "Lungkang Kule", "Maje", "Muara Sahung", "Nasal", "Padang Guci Hilir", "Padang Guci Hulu", "Semidang Gumay", "Tetap", "Tanjung Kemuning"],
        "Kepahiang": ["Kepahiang", "Awang Sari", "Bermani Ilir", "Kabawetan", "Merigi", "Muara Kemumu", "Seberang Musi", "Tebat Karai", "Ujan Mas"],
        "Lebong": ["Lebong Atas", "Lebong Tengah", "Lebong Selatan", "Lebong Sakti", "Lebong Utara", "Amen", "Bingin Kuning", "Pinang Belapis", "Rimbo Pengadang", "Topos", "Uram Jaya", "Pelebai"],
        "Mukomuko": ["Kota Mukomuko", "Teras Terunjam", "Pondok Suguh", "Ipuh", "Malin Deman", "Air Rami", "Teramang Jaya", "Selagan Raya", "Penarik", "V Koto", "XIV Koto", "Air Majunto", "Air Dikit", "Sungai Rumbai"],
        "Rejang Lebong": ["Curup", "Curup Selatan", "Curup Tengah", "Curup Timur", "Curup Utara", "Bermani Ulu", "Bermani Ulu Raya", "Binduriang", "Kota Padang", "Padang Ulak Tanding", "Sindang Beliti Ilir", "Sindang Beliti Ulu", "Sindang Dataran", "Sindang Kelingi", "Selupu Rejang"],
        "Seluma": ["Seluma", "Seluma Barat", "Seluma Selatan", "Seluma Timur", "Seluma Utara", "Semidang Alas", "Semidang Alas Maras", "Talo", "Talo Kecil", "Ulu Talo", "Air Periukan", "Lubuk Sandi", "Sukaraja", "Ilir Talo"]
    };

    document.addEventListener("DOMContentLoaded", function() {
        const kabSelect = document.getElementById('kabupatenSelectModal');
        for (let kab in wilayahBengkulu) {
            let opt = document.createElement('option');
            opt.value = kab; opt.text = kab;
            kabSelect.add(opt);
        }

        // LOGIKA KONFIRMASI HAPUS MODERN (SWEETALERT2)
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.form-delete');
                
                Swal.fire({
                    title: 'Hapus Pengguna?',
                    text: "Data yang dihapus tidak dapat dipulihkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#94a3b8',  
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    borderRadius: '20px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    function populateKecamatanModal() {
        const kabSelect = document.getElementById('kabupatenSelectModal');
        const kecSelect = document.getElementById('kecamatanSelectModal');
        const selectedKab = kabSelect.value;
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        if (selectedKab && wilayahBengkulu[selectedKab]) {
            wilayahBengkulu[selectedKab].forEach(kec => {
                let opt = document.createElement('option');
                opt.value = kec; opt.text = kec;
                kecSelect.add(opt);
            });
        }
    }

    // LOGIKA PENYESUAIAN FORM BERDASARKAN ROLE
    function updateFormLabel() {
        let role = document.getElementById('roleSelect').value;
        let locationBox = document.getElementById('locationBox');
        let unitBox = document.getElementById('unitKerjaBox');
        let noHpBox = document.getElementById('noHpBox');
        let nopolBox = document.getElementById('nopolBox'); // Kotak Nopol
        let unitLabel = document.getElementById('unitLabel');
        let unitInput = document.getElementById('inputUnit');
        let labelNama = document.getElementById('labelNama');
        let inputNama = document.getElementById('inputNama');

        inputNama.oninput = null;

        if (role === 'ambulan') {
            locationBox.style.display = 'block';
            unitBox.style.display = 'block';
            noHpBox.style.display = 'block';
            nopolBox.style.display = 'block'; // Tampilkan NOPOL
            unitLabel.innerText = 'Nama Desa / Kelurahan';
            labelNama.innerText = 'Nama Lengkap Supir';
            inputNama.placeholder = 'Contoh: Budi Santoso';
        } else if(role === 'puskesmas') {
            locationBox.style.display = 'block';
            unitBox.style.display = 'none';
            noHpBox.style.display = 'none';
            nopolBox.style.display = 'none'; // Sembunyikan NOPOL
            labelNama.innerText = 'Nama Puskesmas';
            inputNama.placeholder = 'Contoh: Puskesmas Bentiring';
            inputNama.oninput = function() { unitInput.value = this.value; };
        } else {
            locationBox.style.display = 'none';
            unitBox.style.display = 'none';
            noHpBox.style.display = 'none';
            nopolBox.style.display = 'none';
            unitInput.value = "Pusat";
            labelNama.innerText = 'Nama Lengkap';
            inputNama.placeholder = 'Masukkan nama Admin...';
        }
    }
</script>
@endsection