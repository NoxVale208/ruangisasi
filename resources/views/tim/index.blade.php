@extends('layouts.app')
@section('title', 'Kelola Tim - Ruangisasi')
@section('content')
    @php $prefix = auth()->user()->role === 'admin' ? 'admin' : 'superadmin'; @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kelola Tim Pengerja</h2>
            <p class="text-muted mb-0">Tim aktif akan tersedia sebagai pilihan ketika Admin menugaskan pengerjaan.</p>
        </div><a href="{{ route($prefix . '.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Tambah Tim</h5>
                    <form method="POST" action="{{ route($prefix . '.tim.store') }}">@csrf<div class="mb-3"><label
                                class="form-label">Nama Tim</label><input name="nama_tim" class="form-control"
                                placeholder="Contoh: Tim Dekorasi A" required></div>
                        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi"
                                class="form-control" rows="4"></textarea></div><button class="btn btn-primary w-100">Tambah
                            Tim</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Daftar Tim</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Tim</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>@forelse($tim as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->nama_tim }}</td>
                                    <td>{{ $item->deskripsi ?: '-' }}</td>
                                    <td><span
                                            class="badge {{ $item->status === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route($prefix . '.tim.update', $item) }}"
                                            class="d-flex gap-1">@csrf @method('PATCH')<input type="hidden" name="nama_tim"
                                                value="{{ $item->nama_tim }}"><input type="hidden" name="deskripsi"
                                                value="{{ $item->deskripsi }}"><select name="status"
                                                class="form-select form-select-sm">
                                                <option value="aktif" @selected($item->status === 'aktif')>Aktif</option>
                                                <option value="nonaktif" @selected($item->status === 'nonaktif')>Nonaktif
                                                </option>
                                            </select><button class="btn btn-sm btn-outline-primary">Simpan</button></form>
                                    </td>
                            </tr>@empty<tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada tim.</td>
                                </tr>@endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection