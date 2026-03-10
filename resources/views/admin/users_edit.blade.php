@extends('layouts.admin_bootstrap')

@section('content')
<div class="container" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Edit Data Pengguna</h4>
            <p class="text-muted small">Perbarui informasi akun, password, atau lokasi tugas.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-light border shadow-sm rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white p-4">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                    <i class="fa-solid fa-user-pen fa-xl"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                    <small class="text-white-50">{{ $user->email }}</small>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-secondary small">JABATAN / PERAN</label>
                        <select name="role" id="roleSelect" class="form-select form-select-lg bg-light" required onchange="updateEditForm()">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="puskesmas" {{ $user->role == 'puskesmas' ? 'selected' : '' }}>Petugas Puskesmas</option>
                            <option value="ambulan" {{ $user->role == 'ambulan' ? 'selected' : '' }}>Driver Ambulan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary small" id="labelNama">NAMA LENGKAP</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary small">EMAIL LOGIN</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="col-12" id="locationSelectionBox" style="display: none;">
                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold small text-primary mb-3"><i class="fa-solid fa-map-location-dot me-2"></i> WILAYAH TUGAS</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Kabupaten / Kota</label>
                                    <select name="kabupaten" id="wilayahSelect" class="form-select" onchange="populateKecamatan()">
                                        <option value="">-- Pilih Wilayah --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Kecamatan</label>
                                    <select name="kecamatan" id="kecamatanSelect" class="form-select">
                                        <option value="">-- Pilih Kabupaten/Kota Dulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="unitBox">
                        <label class="form-label fw-bold text-secondary small" id="unitLabel">UNIT KERJA / LOKASI</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-muted"></i></span>
                            <input type="text" name="unit_kerja" class="form-control border-start-0" value="{{ $user->unit_kerja }}" placeholder="...">
                        </div>
                        
                        <div id="kadesBox" style="display: none;">
                            <label class="form-label fw-bold text-secondary small">NAMA KEPALA DESA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-tie text-muted"></i></span>
                                <input type="text" name="nama_kepala_desa" class="form-control border-start-0" value="{{ $user->nama_kepala_desa }}" placeholder="Masukkan Nama Kades...">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold text-secondary small">NOMOR HP (Opsional)</label>
                        <input type="number" name="no_hp" class="form-control" value="{{ $user->no_hp }}" placeholder="08...">
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="col-md-12">
                        <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-lock me-3 fs-4"></i>
                            <div>
                                <strong class="small">Ganti Password?</strong>
                                <div class="extra-small text-muted" style="font-size: 0.75rem;">Kosongkan jika tidak ingin mengubah password user ini.</div>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password Baru (Min. 6 Karakter)">
                    </div>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const dataWilayah = {
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

    function populateKecamatan() {
        let wilayahSelect = document.getElementById('wilayahSelect');
        let kecSelect = document.getElementById('kecamatanSelect');
        let selectedWilayah = wilayahSelect.value;
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        if (selectedWilayah && dataWilayah[selectedWilayah]) {
            dataWilayah[selectedWilayah].forEach(function(kec) {
                let option = document.createElement('option');
                option.value = kec;
                option.text = kec;
                kecSelect.add(option);
            });
        }
    }

    function updateEditForm() {
        let role = document.getElementById('roleSelect').value;
        let unitBox = document.getElementById('unitBox');
        let kadesBox = document.getElementById('kadesBox');
        let locBox = document.getElementById('locationSelectionBox'); 
        let labelNama = document.getElementById('labelNama');
        let inputUnit = document.getElementsByName('unit_kerja')[0];

        // Sembunyikan semua box tambahan terlebih dahulu
        unitBox.style.display = 'none';
        locBox.style.display = 'none';
        kadesBox.style.display = 'none';

        if(role === 'admin') {
            labelNama.innerText = 'NAMA LENGKAP';
        } 
        else if(role === 'puskesmas') {
            locBox.style.display = 'block'; 
            labelNama.innerText = 'NAMA PUSKESMAS'; // Input 'name' sekarang untuk Nama Puskesmas
            unitBox.style.display = 'none'; // Sembunyikan input unit_kerja agar tidak double
        } 
        else if(role === 'ambulan') {
            locBox.style.display = 'block';
            unitBox.style.display = 'block';
            kadesBox.style.display = 'block';
            labelNama.innerText = 'NAMA LENGKAP SUPIR';
            document.getElementById('unitLabel').innerText = 'NAMA DESA / KELURAHAN';
            inputUnit.placeholder = 'Contoh: Kelurahan Anggut';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Wilayah
        let wilayahSelect = document.getElementById('wilayahSelect');
        let userWilayah = "{{ $user->kabupaten }}";
        let userKec = "{{ $user->kecamatan }}";

        for (let wilayah in dataWilayah) {
            let option = document.createElement('option');
            option.value = wilayah;
            option.text = wilayah;
            if(wilayah === userWilayah) option.selected = true; 
            wilayahSelect.add(option);
        }

        if(userWilayah) {
            populateKecamatan();
            let kecSelect = document.getElementById('kecamatanSelect');
            for (let i = 0; i < kecSelect.options.length; i++) {
                if (kecSelect.options[i].value === userKec) {
                    kecSelect.options[i].selected = true;
                    break;
                }
            }
        }
        
        // Jalankan update form untuk sinkronisasi UI berdasarkan role user saat ini
        updateEditForm();
    });
</script>
@endsection