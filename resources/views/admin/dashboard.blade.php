@extends('layouts.app')
@section('title', 'Admin - Ruangisasi')
@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Admin</h2>
        <p class="text-muted">Setujui pesanan, pilih tim pengerja, lalu pantau progresnya.</p>
    </div>
    <div class="row g-3 mb-4">
        @foreach([['Total Pesanan', 'total', 'text-dark'], ['Menunggu Persetujuan', 'menunggu', 'text-warning'], ['Sedang Berjalan', 'berjalan', 'text-primary'], ['Selesai', 'selesai', 'text-success']] as $stat)
            <div class="col-md-3">
                <div class="card shadow-sm stat-card p-4"><small class="text-muted">{{ $stat[0] }}</small>
                    <h2 class="fw-bold {{ $stat[2] }}">{{ $stats[$stat[1]] }}</h2>
                </div>
        </div>@endforeach
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Daftar Pemesanan</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Pemesan</th>
                            <th>Jasa</th>
                            <th>Jadwal & Budget</th>
                            <th>Persetujuan</th>
                            <th>Tim</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanan as $item)
                            <tr>
                                <td>{{ $item->user->name }}
                                    <div class="small text-muted">{{ $item->user->email }}</div>
                                </td>
                                <td><strong>{{ $item->jasa?->nama ?? $item->nama_jasa }}</strong>
                                    <div class="small text-muted">{{ $item->alamat }}</div>
                                </td>
                                <td>{{ $item->tanggal_mulai->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }}
                                    <div>Rp {{ number_format($item->budget, 0, ',', '.') }}</div>
                                </td>
                                <td>@if($item->status_persetujuan === 'menunggu')
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.pemesanan.approve', $item) }}">@csrf
                                            @method('PATCH')<input type="hidden" name="keputusan" value="setuju"><button
                                                class="btn btn-sm btn-success">Setuju</button></form>
                                        <form method="POST" action="{{ route('admin.pemesanan.approve', $item) }}">@csrf
                                            @method('PATCH')<input type="hidden" name="keputusan" value="tidak_setuju"><button
                                                class="btn btn-sm btn-outline-danger">Tolak</button></form>
                                </div>@else<span
                                        class="badge {{ $item->status_persetujuan === 'setuju' ? 'text-bg-success' : 'text-bg-danger' }}">{{ ucfirst(str_replace('_', ' ', $item->status_persetujuan)) }}</span>
                                    <div class="small text-muted mt-1">{{ $item->diputuskanOleh?->name }}</div>@endif
                                </td>
                                <td>@if($item->status_persetujuan === 'setuju')
                                    <form method="POST" action="{{ route('admin.pemesanan.assign', $item) }}"
                                        class="d-flex gap-1">@csrf @method('PATCH')<select name="tim_id"
                                            class="form-select form-select-sm" required>
                                            <option value="">Pilih Tim</option>@foreach($tim as $t)
                                                <option value="{{ $t->id }}" @selected($item->tim_id === $t->id)>{{ $t->nama_tim }}
                                            </option>@endforeach
                                </select><button class="btn btn-sm btn-primary">Simpan</button></form>@else<span
                                        class="text-muted">Menunggu persetujuan</span>@endif
                                </td>
                                <td>@if($item->status_persetujuan === 'setuju' && $item->tim_id)
                                    <form method="POST" action="{{ route('admin.pemesanan.status', $item) }}"
                                        class="d-flex gap-1">@csrf @method('PATCH')<select name="status_proses"
                                            class="form-select form-select-sm">
                                            <option value="pengerjaan" @selected($item->status_proses === 'pengerjaan')>Pengerjaan
                                            </option>
                                            <option value="perbaikan" @selected($item->status_proses === 'perbaikan')>Perbaikan
                                            </option>
                                            <option value="selesai" @selected($item->status_proses === 'selesai')>Selesai</option>
                                </select><button class="btn btn-sm btn-outline-primary">Update</button></form>@else<span
                                        class="text-muted">{{ $item->status_proses === 'ditolak' ? 'Ditolak' : 'Belum siap dikerjakan' }}</span>@endif
                                </td>
                        </tr>@empty<tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pemesanan.</td>
                        </tr>@endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection