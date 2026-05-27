<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Warung Sobak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* TEMA WARNA ALA GACOAN */
        :root {
            --primary-red: #D32F2F;
            --dark-black: #1A1A1A;
            --accent-yellow: #FFC107;
            --light-gray: #F4F6F9;
        }

        body {
            background-color: var(--light-gray);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--dark-black);
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--accent-yellow);
            text-align: center;
            padding: 25px 0;
            border-bottom: 1px solid #333;
            letter-spacing: 1px;
        }

        .sidebar-menu { margin-top: 20px; }
        .sidebar-menu a {
            color: #d1d1d1;
            text-decoration: none;
            padding: 15px 25px;
            display: block;
            font-size: 1.05rem;
            transition: 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: var(--primary-red);
            color: white;
            border-left: 5px solid var(--accent-yellow);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px 25px;
            border-top: 1px solid #333;
        }
        .sidebar-footer a {
            color: #ff5e5e;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .sidebar-footer a:hover { color: #ff8e8e; }

        /* KONTEN UTAMA */
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        /* NAVBAR ATAS */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }
        .search-box { width: 450px; }
        .search-box .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
        }

        .nav-icons .circle-icon {
            width: 45px;
            height: 45px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-left: 15px;
            color: var(--dark-black);
            text-decoration: none;
            font-size: 1.2rem;
            transition: 0.3s;
        }
        .nav-icons .circle-icon:hover {
            background-color: var(--primary-red);
            color: white;
        }

        /* TIGA KOTAK PERSEGI PANJANG DI TENGAH */
        .placeholder-box {
            background-color: #E2E8F0;
            height: 160px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #64748B;
            font-weight: bold;
            font-size: 1.2rem;
            border: 2px dashed #CBD5E1;
            transition: 0.3s;
        }
        
        .placeholder-box:hover {
            background-color: #F1F5F9;
            border-color: var(--primary-red);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            🍜 WARUNG SOBAK
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-home me-3"></i> Dashboard</a>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="fas fa-users me-3"></i> Data Pengguna</a>
            <a href="{{ route('admin.menus') }}" class="{{ request()->routeIs('admin.menus') ? 'active' : '' }}"><i class="fas fa-utensils me-3"></i> Kelola Menu</a>
            <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}"><i class="fas fa-receipt me-3"></i> Kelola Order</a>
            
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-cog me-3"></i> Pengaturan</a>
        </div>
        
        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar (Logout)
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="search-box">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary border-start-0 d-flex align-items-center">Clear</a>
                    @endif
                </div>
            </form>
            
            <div class="nav-icons">
                <a href="#" class="circle-icon" title="Notifikasi"><i class="fas fa-bell"></i></a>
                
                <a href="{{ route('admin.settings') }}" class="circle-icon" title="Profil Admin"><i class="fas fa-user-circle"></i></a>
            </div>
        </div>

        @yield('content')

    </div>

</body>
</html>