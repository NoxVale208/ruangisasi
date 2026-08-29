@extends('layouts.app')

@section('title', 'Ruangisasi - Pemesanan Jasa')

@section('content')
    <div class="hero p-4 p-md-5 shadow-sm mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary mb-3">Sistem Pemesanan Jasa</span>
                <h1 class="display-5 fw-bold">Pesan jasa dengan alur yang jelas dan terkontrol.</h1>
                <p class="lead mb-4">Ruangisasi mengelola pemesanan dari pengajuan, keputusan Super Admin, sampai proses
                    pengerjaan oleh Admin.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Mulai Memesan</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
                @else
                    @php $dashboard = auth()->user()->role === 'super_admin' ? route('superadmin.dashboard') : (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')); @endphp
                    <a href="{{ $dashboard }}" class="btn btn-light btn-lg">Masuk Dashboard</a>
                @endguest
            </div>
            <div class="col-lg-4">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-4">
                    <h5 class="fw-bold">Alur Ruangisasi</h5>
                    <div class="small">1. User mengajukan jasa</div>
                    <div class="small">2. Super Admin memberi keputusan</div>
                    <div class="small">3. Admin mengerjakan</div>
                    <div class="small">4. Status: Pengerjaan → Perbaikan → Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <h5>Alamat Otomatis</h5>
                <p class="text-muted mb-0">Alamat dapat diambil dari lokasi perangkat melalui browser.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <h5>Tanggal Mulai Otomatis</h5>
                <p class="text-muted mb-0">Tanggal mulai diisi otomatis saat pemesanan dibuat.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <h5>Kontrol Super Admin</h5>
                <p class="text-muted mb-0">Keputusan Setuju/Tidak Setuju hanya tersedia untuk Super Admin.</p>
            </div>
        </div>
    </div>
@endsection