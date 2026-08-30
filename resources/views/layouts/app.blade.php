<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ruangisasi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .brand { font-weight: 800; letter-spacing: .3px; }
        .hero { background: linear-gradient(135deg, #0d6efd, #6f42c1); color: white; border-radius: 24px; }
        .card { border: 0; border-radius: 18px; }
        .stat-card { min-height: 125px; }
        .table thead th { white-space: nowrap; }
        .badge-soft { background: #eef2ff; color: #4338ca; }
        .navbar { backdrop-filter: blur(10px); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        @php $role = auth()->user()->role ?? null; @endphp
        <a class="navbar-brand brand text-primary" href="{{ route('home') }}">RUANGISASI</a>
        <div class="d-flex align-items-center gap-3">
            @auth
                @if($role === 'user')
                    <a class="text-decoration-none" href="{{ route('user.dashboard') }}">Pemesanan Jasa</a>
                @elseif($role === 'admin')
                    <a class="text-decoration-none" href="{{ route('admin.dashboard') }}">Pemesanan</a>
                    <a class="text-decoration-none" href="{{ route('admin.jasa.index') }}">Jasa</a>
                    <a class="text-decoration-none" href="{{ route('admin.tim.index') }}">Tim</a>
                @elseif($role === 'super_admin')
                    <a class="text-decoration-none" href="{{ route('superadmin.dashboard') }}">Pemesanan</a>
                    <a class="text-decoration-none" href="{{ route('superadmin.jasa.index') }}">Jasa</a>
                    <a class="text-decoration-none" href="{{ route('superadmin.tim.index') }}">Tim</a>
                @endif
                <span class="badge text-bg-dark">{{ str_replace('_', ' ', ucfirst($role)) }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Keluar</button>
                </form>
            @else
                <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a>
                <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Periksa data:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
