<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Akses Sistem - Program Prioritas Gubernur Bengkulu</title>
    
    <link rel="icon" href="{{ asset('images/logoprov.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8; 
            height: 100vh; /* Dikunci persis 1 layar penuh */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden; /* Mematikan scrollbar browser di desktop */
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
            max-width: 880px; 
            width: 95%; /* Margin aman di kiri kanan otomatis dari sini */
            max-height: 95vh; /* Memastikan kartu tidak akan pernah melebihi layar */
            background: #ffffff;
            border-radius: 20px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); 
            overflow: hidden;
            animation: cardEntrance 0.6s ease-out forwards;
            z-index: 10;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- SISI KIRI (BIRU) --- */
        .brand-side {
            width: 45%;
            background: linear-gradient(150deg, #1e3a8a 0%, #1d4ed8 100%);
            padding: 2rem 1.5rem; 
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-right: 6px solid #f59e0b;
        }

        .brand-content { width: 100%; text-align: center; }
        
        .logo-box {
            width: 45px; height: 45px; 
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin: 0 auto 0.8rem auto;
        }

        .brand-side h2 { font-size: 1.15rem; line-height: 1.3; font-weight: 800; margin-bottom: 0.3rem; }
        .brand-desc { font-size: 0.75rem; opacity: 0.8; margin-bottom: 1.2rem; max-width: 300px; margin-left: auto; margin-right: auto; }

        .leader-photo-container {
            width: 100%;
            max-width: 200px; /* Foto sedikit dikecilkan lagi agar lebih pas di layar 14 inch */
            margin: 0 auto 1.2rem auto;
            background: #ffffff;
            padding: 6px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); 
            border: 3px solid #f59e0b;
        }

        .leader-photo-frame {
            border: 1px solid #e2e8f0; 
            border-radius: 10px;
            overflow: hidden;
            background: #f8fafc;
        }

        .leader-photo-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .leader-caption-box {
            background: #1e3a8a;
            color: #ffffff;
            padding: 5px;
            border-radius: 6px;
            margin-top: 6px;
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        .status-wrapper { display: flex; gap: 10px; width: 100%; max-width: 260px; margin: 0 auto; }
        .status-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px; padding: 6px 10px;
            display: flex; align-items: center; gap: 8px; flex: 1;
        }
        .status-icon {
            width: 24px; height: 24px; border-radius: 6px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; color: white; flex-shrink: 0;
        }
        .icon-ambulan { background: #b45309; }
        .icon-bpjs { background: #047857; }
        .status-text-group { text-align: left; line-height: 1.1; }
        .status-title { font-size: 0.7rem; font-weight: 600; color: #ffffff; display: block;}
        .status-subtitle { font-size: 0.6rem; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 4px; margin-top: 2px;}
        .status-dot { width: 5px; height: 5px; background: #10b981; border-radius: 50%; box-shadow: 0 0 5px #10b981; }

        /* --- SISI KANAN (PUTIH) --- */
        .form-side { 
            width: 55%; 
            padding: 2rem 3.5rem; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        }
        
        .form-title { font-weight: 800; color: #0f172a; font-size: 1.5rem; margin-bottom: 0.2rem; }
        .form-subtitle { color: #64748b; font-size: 0.8rem; margin-bottom: 1.5rem; }

        .input-group-custom { position: relative; margin-bottom: 1rem; }
        .input-group-custom i.icon-left {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 0.9rem; transition: 0.3s; z-index: 10;
        }
        .form-control-custom {
            width: 100%; padding: 0.7rem 1rem 0.7rem 2.5rem;
            background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px; 
            font-size: 0.85rem; color: #1e293b; transition: 0.3s;
        }
        .form-control-custom:focus {
            background-color: #ffffff; border-color: #3b82f6;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1); outline: none;
        }
        .form-control-custom:focus + i.icon-left { color: #3b82f6; }

        .btn-toggle-password {
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; padding: 0; cursor: pointer;
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white; border: none; border-radius: 10px; padding: 0.75rem; width: 100%;
            font-weight: 600; font-size: 0.9rem; transition: 0.3s;
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.2);
            margin-top: 0.5rem;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .form-footer { margin-top: 1.5rem; text-align: center; color: #94a3b8; font-size: 0.65rem; }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 850px) {
            body { 
                height: auto; 
                overflow-y: auto; /* Mengembalikan scroll untuk HP */
                padding: 15px; 
            }
            .login-card { flex-direction: column; max-height: none; }
            .brand-side { width: 100%; padding: 2rem; border-right: none; border-bottom: 6px solid #f59e0b; }
            .form-side { width: 100%; padding: 2rem 1.5rem; }
            .leader-photo-container { max-width: 200px; }
            .form-title { text-align: center; }
            .form-subtitle { text-align: center; }
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
                    Gub & Wagub Bengkulu
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
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 px-3 py-2 small fw-medium mb-3 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-6 me-2"></i> 
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
            <div class="mb-3 form-check">
                <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted fw-medium" for="remember" style="font-size: 0.8rem;">Ingat sesi saya</label>
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