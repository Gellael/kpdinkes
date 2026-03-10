<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Akses Sistem - Program Prioritas Gubernur Bengkulu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* --- BACKGROUND ANIMATION --- */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: floatBackground 20s infinite alternate ease-in-out;
            opacity: 0.6;
        }
        .shape-1 { width: 600px; height: 600px; background: rgba(37, 99, 235, 0.25); top: -200px; left: -150px; }
        .shape-2 { width: 500px; height: 500px; background: rgba(14, 165, 233, 0.2); bottom: -150px; right: 5%; }

        @keyframes floatBackground {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 30px) scale(1.05); }
        }

        /* --- KARTU UTAMA --- */
        .login-card {
            display: flex;
            max-width: 1100px;
            width: 95%;
            background: #ffffff;
            border-radius: 28px; 
            box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.2); 
            overflow: hidden;
            opacity: 0;
            transform: translateY(40px);
            animation: cardEntrance 0.8s ease-out forwards;
            position: relative;
            z-index: 10;
        }

        @keyframes cardEntrance {
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- SISI KIRI (BIRU): TOTAL REVAMP UNTUK FOTO MENCOLOK --- */
        .brand-side {
            width: 50%; /* Diperlebar sedikit */
            background: linear-gradient(150deg, #1e3a8a 0%, #1d4ed8 100%);
            padding: 3rem; /* Sedikit dikurangi padding agar foto lebih luas */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center semua konten */
            position: relative;
            border-right: 8px solid #f59e0b; /* Aksen Emas di pemisah */
        }

        .brand-content { position: relative; z-index: 2; width: 100%; text-align: center; }
        
        .logo-box {
            width: 60px; height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto 1.5rem auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .brand-side h2 { font-size: 1.7rem; line-height: 1.2; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 1rem; }
        .brand-desc { font-size: 0.9rem; opacity: 0.8; margin-bottom: 2rem; font-weight: 300; max-width: 400px; margin-left: auto; margin-right: auto; }

        /* ==========================================================
           MODIFIKASI FOTO MENCOLOK ALA PEMERINTAHAN (POPPING OUT)
           ========================================================== */
        .leader-photo-container {
            position: relative;
            width: 100%;
            max-width: 420px; /* Ukuran wadah diperbesar */
            margin-bottom: 2.5rem;
            /* Efek Pop-Out: Background putih solid dengan border tebal emas & putih */
            background: #ffffff;
            padding: 15px;
            border-radius: 24px;
            /* Bayangan sangat dalam agar foto benar-benar menonjol keluar */
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 0 0 8px rgba(255,255,255,0.1); 
            border: 4px solid #f59e0b; /* Border Emas Utama */
            transition: transform 0.3s ease;
        }
        .leader-photo-container:hover {
            transform: translateY(-5px) scale(1.02); /* Efek interaktif saat dihover */
        }

        /* Bingkai dalam untuk foto agar terlihat seperti potret resmi */
        .leader-photo-frame {
            border: 2px solid #e2e8f0; 
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
        }

        .leader-photo-container img {
            max-width: 100%;
            height: auto;
            /* Ukuran foto diperbesar signifikan */
            max-height: 260px; 
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Caption tegas di bawah foto di dalam kotak putih */
        .leader-caption-box {
            background: #1e3a8a; /* Warna biru tua dinas */
            color: #ffffff;
            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        /* ========================================================== */

        /* Status Cards (Dibuat subtil agar foto tetap dominan) */
        .status-wrapper {
            display: flex; gap: 15px; width: 100%; max-width: 400px;
        }
        .status-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; padding: 12px;
            display: flex; align-items: center; gap: 10px; flex: 1;
        }
        .status-icon {
            width: 35px; height: 35px; border-radius: 8px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: white; flex-shrink: 0;
        }
        .icon-ambulan { background: #b45309; }
        .icon-bpjs { background: #047857; }
        .status-title { font-size: 0.85rem; font-weight: 600; color: #ffffff; }
        .status-subtitle { font-size: 0.7rem; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 5px;}
        .status-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 6px #10b981; }

        /* --- SISI KANAN (PUTIH): FORM --- */
        .form-side { width: 50%; padding: 5rem; display: flex; flex-direction: column; justify-content: center; }
        .form-title { font-weight: 800; color: #0f172a; font-size: 2.2rem; margin-bottom: 0.5rem; letter-spacing: -1px; }
        .form-subtitle { color: #64748b; font-size: 1rem; margin-bottom: 3rem; }

        /* Styling Input */
        .input-group-custom { position: relative; margin-bottom: 1.5rem; }
        .input-group-custom i.icon-left {
            position: absolute; left: 1.3rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 1.1rem; transition: 0.3s; z-index: 10;
        }
        .form-control-custom {
            width: 100%; padding: 1rem 1rem 1rem 3.5rem;
            background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; 
            font-size: 0.95rem; color: #1e293b; transition: 0.3s;
        }
        .form-control-custom:focus {
            background-color: #ffffff; border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1); outline: none;
        }
        .form-control-custom:focus + i.icon-left { color: #3b82f6; }

        .btn-toggle-password {
            position: absolute; right: 1.3rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; padding: 0; cursor: pointer;
        }

        /* Tombol Login */
        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white; border: none; border-radius: 14px; padding: 1rem; width: 100%;
            font-weight: 600; font-size: 1.05rem; transition: 0.3s;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(37, 99, 235, 0.3); }

        .form-check-input:checked { background-color: #2563eb; border-color: #2563eb; }

        /* Footer */
        .form-footer { margin-top: 3rem; text-align: center; color: #94a3b8; font-size: 0.75rem; }

        /* ==========================================================
           RESPONSIVE (MOBILE)
           ========================================================== */
        @media (max-width: 991px) {
            body { padding: 1rem; }
            .login-card { flex-direction: column-reverse; }
            .form-side, .brand-side { width: 100%; padding: 2.5rem 1.5rem; }
            .form-side { padding-top: 3rem; }
            .brand-side { border-right: none; border-top: 8px solid #f59e0b; }
            .leader-photo-container { max-width: 100%; }
            .leader-photo-container img { max-height: 180px; }
            .form-title { font-size: 1.8rem; text-align: center; }
            .form-subtitle { text-align: center; font-size: 0.9rem; }
        }
    </style>
</head>
<body>

<div class="bg-shape shape-1"></div>
<div class="bg-shape shape-2"></div>

<div class="login-card">
    
    <div class="brand-side">
        <div class="brand-content">
            <div class="logo-box">
                <i class="fa-solid fa-shield-halved text-white"></i>
            </div>
            <h2>PROGRAM PRIORITAS<br>GUBERNUR BENGKULU</h2>
            <p class="brand-desc">Sistem Manajemen Terpadu BPJS & Ambulan Provinsi Bengkulu.</p>

            <div class="leader-photo-container">
                <div class="leader-photo-frame">
                    <img src="{{ asset('images/gubernur-wakil-gubernur-bengkulu.png') }}" alt="Gubernur dan Wakil Gubernur Bengkulu">
                </div>
                <div class="leader-caption-box">
                    Gubernur & Wakil Gubernur Bengkulu
                </div>
            </div>
            <div class="status-wrapper">
                <div class="status-card">
                    <div class="status-icon icon-ambulan">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>
                    <div class="status-text-group">
                        <span class="status-title">Ambulan</span>
                        <span class="status-subtitle"><div class="status-dot"></div> Siaga</span>
                    </div>
                </div>
                <div class="status-card">
                    <div class="status-icon icon-bpjs">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="status-text-group">
                        <span class="status-title">BPJS</span>
                        <span class="status-subtitle"><div class="status-dot"></div> Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-side">
        <h1 class="form-title">Akses Portal</h1>
        <p class="form-subtitle">Masukkan akun dinas Anda untuk melanjutkan.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 px-4 py-3 small fw-medium mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-5 me-3"></i> 
                <div>Email atau password tidak valid.</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="input-group-custom">
                <input type="email" name="email" class="form-control-custom" placeholder="Alamat Email Dinas" value="{{ old('email') }}" required autofocus>
                <i class="fa-regular fa-envelope icon-left"></i>
            </div>
            <div class="input-group-custom">
                <input type="password" id="passwordInput" name="password" class="form-control-custom" placeholder="Kata Sandi Akses" required>
                <i class="fa-solid fa-key icon-left"></i>
                <button type="button" class="btn-toggle-password" id="togglePassword" tabindex="-1">
                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="mb-4 form-check">
                <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted fw-medium" for="remember">Ingat sesi saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk ke Dashboard</button>
        </form>

        <div class="form-footer">
            &copy; {{ date('Y') }} Dinkes Provinsi Bengkulu.<br>Akses Terbatas Petugas Otorisasi.
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.className = type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
        });
    });
</script>

</body>
</html>