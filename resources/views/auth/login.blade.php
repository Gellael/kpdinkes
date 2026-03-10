<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Akses Sistem - Program Prioritas Gubernur</title>
    
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
            filter: blur(60px);
            z-index: -1;
            animation: floatBackground 15s infinite alternate ease-in-out;
        }
        .shape-1 { width: 500px; height: 500px; background: rgba(59, 130, 246, 0.2); top: -100px; left: -100px; }
        .shape-2 { width: 400px; height: 400px; background: rgba(14, 165, 233, 0.2); bottom: -100px; right: 10%; animation-delay: -5s; }

        @keyframes floatBackground {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 30px) scale(1.1); }
        }

        /* --- KARTU UTAMA --- */
        .login-card {
            display: flex;
            max-width: 1050px;
            width: 95%;
            background: #ffffff;
            border-radius: 24px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); 
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px) scale(0.98);
            animation: cardEntrance 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        @keyframes cardEntrance {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* --- SISI KIRI: BRANDING --- */
        .brand-side {
            width: 45%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 3.5rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .brand-deco-wrapper {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 1; pointer-events: none;
        }
        .brand-deco-wrapper::before {
            content: ''; position: absolute; top: -50px; left: -50px; width: 250px; height: 250px;
            background: rgba(255, 255, 255, 0.1); border-radius: 50%;
        }
        .brand-deco-wrapper::after {
            content: ''; position: absolute; bottom: -100px; right: -50px; width: 350px; height: 350px;
            background: rgba(255, 255, 255, 0.05); border-radius: 50%;
        }

        .brand-content { position: relative; z-index: 2; }
        
        .logo-box {
            width: 65px; height: 65px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* --- STATUS CARDS TERINTEGRASI --- */
        .status-wrapper {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }

        .status-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px; 
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .status-icon {
            width: 42px; height: 42px;
            border-radius: 10px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }
        
        .icon-ambulan { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .icon-bpjs { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

        .status-text-group { display: flex; flex-direction: column; }
        .status-title { font-size: 0.85rem; font-weight: 700; color: #ffffff; letter-spacing: 0.5px; }
        .status-subtitle { font-size: 0.7rem; color: rgba(255,255,255,0.8); display: flex; align-items: center; gap: 6px; margin-top: 2px;}

        .status-dot {
            width: 6px; height: 6px; background: #10b981; border-radius: 50%;
            box-shadow: 0 0 6px #10b981; animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 4px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* --- SISI KANAN: FORM --- */
        .form-side {
            width: 55%;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title { font-weight: 800; color: #0f172a; font-size: 2rem; margin-bottom: 0.5rem; letter-spacing: -0.5px; }
        .form-subtitle { color: #64748b; font-size: 0.95rem; margin-bottom: 2.5rem; line-height: 1.6; }

        /* Styling Input */
        .input-group-custom { position: relative; margin-bottom: 1.5rem; }
        .input-group-custom i.icon-left {
            position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 1.1rem; transition: 0.3s; z-index: 10;
        }
        .form-control-custom {
            width: 100%; padding: 1rem 1rem 1rem 3.2rem;
            background-color: #f8fafc; border: 2px solid transparent; border-radius: 14px; 
            font-size: 0.95rem; color: #334155; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-control-custom:focus {
            background-color: #ffffff; border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1); outline: none;
        }
        .form-control-custom:focus + i.icon-left { color: #3b82f6; }

        .btn-toggle-password {
            position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; padding: 0; cursor: pointer; transition: 0.2s;
        }
        .btn-toggle-password:hover { color: #0f172a; }

        /* Tombol Login */
        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white; border: none; border-radius: 14px; padding: 1rem; width: 100%;
            font-weight: 600; font-size: 1.05rem; transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(37, 99, 235, 0.3); }

        .form-check-input:checked { background-color: #2563eb; border-color: #2563eb; }

        /* ==========================================================
           PERBAIKAN TOTAL RESPONSIVE (MOBILE & TABLET)
           ========================================================== */
        @media (max-width: 991px) {
            body {
                /* KUNCI: flex-start agar konten mulai dari atas (tidak dipotong) */
                align-items: flex-start; 
                padding: 2rem 1rem; /* Memberikan jarak dari ujung layar HP */
            }
            .login-card { 
                flex-direction: column; 
                width: 100%; 
                max-width: 100%; /* Lebar penuh mengikuti padding body */
                border-radius: 20px;
                margin: auto; /* Untuk menyeimbangkan */
            }
            
            .brand-side { 
                width: 100%; 
                padding: 2.5rem 1.5rem; 
                text-align: center; 
                align-items: center; 
            }
            .logo-box { margin: 0 auto 1.5rem auto; }
            .brand-side h2 { font-size: 1.5rem !important; }
            
            /* Status cards menjadi tumpuk di HP agar rapi */
            .status-wrapper { 
                flex-direction: column; 
                width: 100%; 
                gap: 12px;
            }
            .status-card { 
                width: 100%; 
                padding: 12px;
                justify-content: flex-start;
            }
            .status-text-group { text-align: left; }

            .brand-content.mt-5 { margin-top: 2rem !important; padding-top: 1.5rem !important; }
            .brand-content .d-flex { justify-content: center; }

            .form-side { 
                width: 100%; 
                padding: 2.5rem 1.5rem; /* Kurangi padding samping agar form lebih lebar */
            }
            .form-title { font-size: 1.8rem; text-align: center; }
            .form-subtitle { font-size: 0.9rem; text-align: center; margin-bottom: 2rem; }
        }
    </style>
</head>
<body>

<div class="bg-shape shape-1"></div>
<div class="bg-shape shape-2"></div>

<div class="login-card">
    
    <div class="brand-side">
        <div class="brand-deco-wrapper"></div>
        
        <div class="brand-content">
            <div class="logo-box">
                <i class="fa-solid fa-shield-halved text-white"></i>
            </div>
            <h2 class="fw-bold mb-3" style="font-size: 1.8rem; line-height: 1.3; letter-spacing: -0.5px;">
                PROGRAM PRIORITAS<br>GUBERNUR & WAKIL GUBERNUR
            </h2>
            <p class="opacity-75" style="font-size: 0.95rem; line-height: 1.6;">
                Sistem Terpadu Manajemen Kepesertaan BPJS dan Operasional Ambulan Provinsi secara Real-Time.
            </p>

            <div class="status-wrapper">
                <div class="status-card">
                    <div class="status-icon icon-ambulan">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>
                    <div class="status-text-group">
                        <span class="status-title">Armada Ambulan</span>
                        <span class="status-subtitle"><div class="status-dot"></div> Siaga 24 Jam</span>
                    </div>
                </div>

                <div class="status-card">
                    <div class="status-icon icon-bpjs">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="status-text-group">
                        <span class="status-title">Peserta BPJS</span>
                        <span class="status-subtitle"><div class="status-dot"></div> Terintegrasi</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="brand-content mt-5 pt-4 border-top border-white border-opacity-25" style="max-width: 100%;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-lock fs-5 me-3 opacity-75"></i>
                <small class="opacity-75" style="font-size: 0.75rem; letter-spacing: 0.5px;">AKSES TERENKRIPSI & TERLINDUNGI</small>
            </div>
        </div>
    </div>

    <div class="form-side">
        <h1 class="form-title">Akses Portal</h1>
        <p class="form-subtitle">Masukkan akun dinas Anda untuk melanjutkan ke dalam dashboard sistem.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 px-4 py-3 small fw-medium mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-5 me-3"></i> 
                <div>Akun tidak valid. Silakan periksa kembali email dan password Anda.</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="input-group-custom">
                <input type="email" name="email" class="form-control-custom" placeholder="Alamat Email Dinas" value="{{ old('email') }}" required autofocus autocomplete="username">
                <i class="fa-regular fa-envelope icon-left"></i>
            </div>

            <div class="input-group-custom">
                <input type="password" id="passwordInput" name="password" class="form-control-custom" placeholder="Kata Sandi Akses" required autocomplete="current-password">
                <i class="fa-solid fa-key icon-left"></i>
                <button type="button" class="btn-toggle-password" id="togglePassword" tabindex="-1">
                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <div class="mb-4 px-1">
                <div class="form-check">
                    <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember" style="cursor: pointer;">
                    <label class="form-check-label small text-muted fw-medium" for="remember" style="cursor: pointer;">
                        Ingat sesi saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Masuk ke Sistem <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="text-center mt-5">
            <small class="text-muted fw-medium" style="font-size: 0.75rem;">
                &copy; {{ date('Y') }} Dashboard Operasional Terpadu.<br>Hanya untuk petugas yang memiliki otorisasi.
            </small>
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