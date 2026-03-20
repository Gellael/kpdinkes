<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Prioritas Gubernur dan Wakil Gubernur</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            --sidebar-bg: #0f172a; 
            --accent-blue: #60a5fa;
        }

        body { 
            background-color: #f1f5f9; 
            font-family: 'Poppins', sans-serif; 
            overflow-x: hidden;
        }

        /* --- 1. SIDEBAR RESPONSIVE LOGIC --- */
        .sidebar { 
            width: var(--sidebar-width);
            height: 100vh; /* FIX LOGOUT KEPOTONG: Gunakan height fix agar overflow menu berfungsi */
            background: #0f172a;
            background-image: radial-gradient(circle at 0% 0%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            color: white;
            position: fixed;
            left: 0; top: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0,0,0,0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand { 
            padding: 35px 25px; 
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 10px;
        }
        
        .brand-icon {
            width: 45px; height: 45px;
            background: var(--primary-gradient);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); 
            margin-right: 15px;
            flex-shrink: 0;
        }

        .brand-text h5 { margin: 0; font-weight: 800; line-height: 1.2; font-size: 0.85rem; text-transform: uppercase; color: #f8fafc; }
        .brand-text small { color: var(--accent-blue); font-size: 0.6rem; letter-spacing: 1.5px; font-weight: 700; }

        .sidebar-menu { 
            padding: 0 18px; 
            list-style: none; 
            overflow-y: auto; /* Menu akan scroll jika layar pendek */
            flex-grow: 1; 
        }
        
        /* Menyembunyikan visual scrollbar di menu agar tetap elegan */
        .sidebar-menu::-webkit-scrollbar { display: none; }
        .sidebar-menu { -ms-overflow-style: none; scrollbar-width: none; }

        .menu-header { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; color: #475569; margin: 25px 0 12px 12px; font-weight: 800; }

        .nav-link { 
            color: #94a3b8; text-decoration: none; padding: 12px 18px; display: flex; align-items: center;
            border-radius: 14px; margin-bottom: 5px; transition: all 0.3s; font-size: 0.9rem;
        }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: #fff; padding-left: 25px; }
        .nav-link.active { background: var(--primary-gradient); color: white; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }

        .user-profile { 
            margin: 20px; padding: 20px; 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(10px); 
            border-radius: 20px; 
            border: 1px solid rgba(255,255,255,0.05); 
            flex-shrink: 0; /* Mencegah profil terpotong flexbox */
        }
        .btn-logout { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); width: 100%; padding: 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 700; margin-top: 15px; transition: 0.3s; }
        .btn-logout:hover { background: #ef4444; color: white; }

        /* --- 2. MAIN CONTENT AREA --- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            height: 70px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* --- 3. MOBILE RESPONSIVE FIXES --- */
        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
            backdrop-filter: blur(3px);
        }

        @media (max-width: 991.98px) {
            .sidebar { left: -100%; } 
            .sidebar.active { left: 0; } 
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay.show { display: block; }
        }

        .table-responsive { border-radius: 15px; overflow-x: auto; background: white; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></div>
        <div class="brand-text">
            <h5>PROGRAM PRIORITAS <br> GUBERNUR</h5>
            <small>EST. 2026</small>
        </div>
        <button class="btn btn-link text-white d-lg-none ms-auto p-0" id="closeSidebar">
            <i class="fa-solid fa-xmark fs-4"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        @if(Auth::user()->role == 'admin')
            <li class="menu-header">Administrator</li>
            <li><a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie me-2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fa-solid fa-user-gear me-2"></i> Manajemen User</a></li>
            <li class="menu-header">Monitoring</li>
            <li><a href="{{ route('admin.bpjs') }}" class="nav-link {{ request()->routeIs('admin.bpjs') ? 'active' : '' }}"><i class="fa-solid fa-shield-heart me-2"></i> Data BPJS</a></li>
            <li><a href="{{ route('admin.ambulan') }}" class="nav-link {{ request()->routeIs('admin.ambulan') ? 'active' : '' }}"><i class="fa-solid fa-truck-fast me-2"></i> Log Ambulan</a></li>
        @endif

        @if(Auth::user()->role == 'puskesmas')
            <li class="menu-header">Layanan</li>
            <li><a href="{{ route('puskesmas.home') }}" class="nav-link {{ request()->routeIs('puskesmas.home') ? 'active' : '' }}"><i class="fa-solid fa-file-waveform me-2"></i> Pendaftaran BPJS</a></li>
        @endif

        @if(Auth::user()->role == 'ambulan')
            <li class="menu-header">Operasional</li>
            <li><a href="{{ route('ambulan.dashboard') }}" class="nav-link {{ request()->routeIs('ambulan.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge-high me-2"></i> Dashboard Driver</a></li>
            <li><a href="{{ route('ambulan.profil') }}" class="nav-link {{ request()->routeIs('ambulan.profil') ? 'active' : '' }}"><i class="fa-solid fa-user-circle me-2"></i> Profil Saya</a></li>
        @endif
    </ul>

    <div class="user-profile">
        <div class="d-flex align-items-center">
            <div class="avatar-box bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="ms-3 overflow-hidden">
                <div class="fw-bold text-white text-truncate small">{{ Auth::user()->name }}</div>
                <div class="text-info fw-bold" style="font-size: 0.6rem; text-transform: uppercase;">{{ Auth::user()->role }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa-solid fa-power-off me-2"></i> Logout</button>
        </form>
    </div>
</div>

<div class="main-content">
    
    <nav class="top-navbar d-lg-none">
        <div class="d-flex align-items-center w-100">
            <button class="btn btn-white border shadow-sm rounded-3 me-3" id="hamburger">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <span class="fw-bold text-dark fs-6">Menu Navigasi</span>
        </div>
    </nav>

    <div class="p-3 p-md-4 pt-md-5"> 
        @yield('content')
    </div>

</div>

<div id="laravel-notifications" 
     data-success="{{ session('success') }}" 
     data-error="{{ $errors->any() ? implode('<br>', $errors->all()) : '' }}" 
     style="display: none;">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // LOGIKA SIDEBAR RESPONSIVE
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const hamburger = document.getElementById('hamburger');
    const closeBtn = document.getElementById('closeSidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('show');
    }

    if(hamburger) hamburger.addEventListener('click', toggleSidebar);
    if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', toggleSidebar);

    document.addEventListener('DOMContentLoaded', function() {
        const notifyContainer = document.getElementById('laravel-notifications');
        const successMsg = notifyContainer.getAttribute('data-success');
        const errorMsg = notifyContainer.getAttribute('data-error');

        if (successMsg && successMsg.trim() !== "") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMsg,
                showConfirmButton: false,
                timer: 2500,
                borderRadius: '20px'
            });
        }

        if (errorMsg && errorMsg.trim() !== "") {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: errorMsg,
                confirmButtonColor: '#1e40af',
                borderRadius: '20px'
            });
        }
    });
</script>

</body>
</html>