@extends('layouts.app')
@section('title', 'Super Admin - Ruangisasi')
@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Persetujuan Pemesanan</h2>
        <p class="text-muted">Super Admin dapat menyetujui/menolak pengajuan. Admin juga memiliki hak persetujuan sesuai
            alur sistem.</p>
    </div>
    <div class="row g-4">@forelse($pemesanan as $item)
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div><span
                                class="badge {{ $item->status_persetujuan === 'menunggu' ? 'text-bg-warning' : 'text-bg-success' }}">{{ $item->status_persetujuan === 'menunggu' ? 'Menunggu Persetujuan' : 'Sudah Diputuskan' }}</span>
                            <h4 class="fw-bold mt-2 mb-1">{{ $item->jasa?->nama ?? $item->nama_jasa }}</h4>
                            <p class="text-muted mb-0">Pemesan: {{ $item->user->name }}</p>
                        </div>
                        <div class="text-end"><small class="text-muted">Budget</small>
                            <div class="fw-bold">Rp {{ number_format($item->budget, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="small"><strong>Alamat</strong><br>{{ $item->alamat }}
                        <div class="row mt-2">
                            <div class="col-6"><strong>Mulai</strong><br>{{ $item->tanggal_mulai->format('d/m/Y') }}</div>
                            <div class="col-6"><strong>Selesai</strong><br>{{ $item->tanggal_selesai->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>@if($item->status_persetujuan === 'menunggu')
                        <div class="d-flex gap-2 mt-4">
                            <form method="POST" action="{{ route('superadmin.pemesanan.approve', $item) }}" class="flex-fill">
                                @csrf @method('PATCH')<input type="hidden" name="keputusan" value="setuju"><button
                                    class="btn btn-success w-100">✓ Setuju</button></form>
                            <form method="POST" action="{{ route('superadmin.pemesanan.approve', $item) }}" class="flex-fill">
                                @csrf @method('PATCH')<input type="hidden" name="keputusan" value="tidak_setuju"><button
                                    class="btn btn-outline-danger w-100">✕ Tidak Setuju</button></form>
                    </div>@else<div class="alert alert-light mt-4 mb-0">Diputuskan oleh
                        <strong>{{ $item->diputuskanOleh?->name }}</strong>. Status proses:
                    <strong>{{ ucfirst(str_replace('_', ' ', $item->status_proses)) }}</strong>.</div>@endif
                </div>
            </div>
    </div>@empty<div class="col-12">
            <div class="card shadow-sm p-5 text-center">
                <h5>Belum ada pemesanan.</h5>
                <p class="text-muted mb-0">Pengajuan user akan muncul di sini.</p>
            </div>
        </div>@endforelse
    </div>
@endsection