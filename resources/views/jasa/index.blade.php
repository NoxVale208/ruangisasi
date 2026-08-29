@extends('layouts.app')
@section('title', 'Kelola Jasa - Ruangisasi')
@section('content')
    @php $prefix = auth()->user()->role === 'admin' ? 'admin' : 'superadmin'; @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kelola Jenis Jasa</h2>
            <p class="text-muted mb-0">Daftar jasa yang dapat dipilih user saat membuat pemesanan.</p>
        </div><a href="{{ route($prefix . '.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Tambah Jasa</h5>
                    <form method="POST" action="{{ route($prefix . '.jasa.store') }}">@csrf<div class="mb-3"><label
                                class="form-label">Nama Jasa</label><input name="nama" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi"
                                class="form-control" rows="4"></textarea></div><button class="btn btn-primary w-100">Tambah
                            Jasa</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Daftar Jasa</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Jasa</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>@forelse($jasa as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td>{{ $item->deskripsi ?: '-' }}</td>
                                    <td><span
                                            class="badge {{ $item->status === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route($prefix . '.jasa.update', $item) }}"
                                            class="d-flex gap-1">@csrf @method('PATCH')<input type="hidden" name="nama"
                                                value="{{ $item->nama }}"><input type="hidden" name="deskripsi"
                                                value="{{ $item->deskripsi }}"><select name="status"
                                                class="form-select form-select-sm">
                                                <option value="aktif" @selected($item->status === 'aktif')>Aktif</option>
                                                <option value="nonaktif" @selected($item->status === 'nonaktif')>Nonaktif
                                                </option>
                                            </select><button class="btn btn-sm btn-outline-primary">Simpan</button></form>
                                    </td>
                            </tr>@empty<tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada jasa.</td>
                                </tr>@endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection