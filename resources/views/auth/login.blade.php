@extends('layouts.app')
@section('title', 'Login - Ruangisasi')
@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">RUANGISASI</h2>
                    <p class="text-muted">Masuk ke akun Anda</p>
                </div>
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('login.perform') }}">@csrf
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email"
                            class="form-control" value="{{ old('email') }}" required autofocus></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password"
                            class="form-control" required></div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" value="1"
                            id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                </p>
            </div>
        </div>
    </div>
@endsection