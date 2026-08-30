@extends('layouts.app')

@section('title', 'Admin - Ruangisasi')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Dashboard Admin</h2>
    <p class="text-muted mb-0">Kelola persetujuan, tim pengerja, dan status pemesanan.</p>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Pesanan', 'total', 'text-dark'],
        ['Menunggu Persetujuan', 'menunggu', 'text-warning'],
        ['Sedang Berjalan', 'berjalan', 'text-primary'],
        ['Selesai', 'selesai', 'text-success'],
    ] as $stat)
        <div class="col-md-3">
            <div class="card shadow-sm stat-card p-4">
                <small class="text-muted">{{ $stat[0] }}</small>
                <h2 class="fw-bold {{ $stat[2] }}">{{ $stats[$stat[1]] ?? 0 }}</h2>
            </div>
        </div>
    @endforeach
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Daftar Pemesanan</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <td>
                                <strong>{{ $item->user?->name ?? '-' }}</strong>
                                <div class="small text-muted">{{ $item->user?->email ?? '-' }}</div>
                            </td>

                            <td>
                                <strong>{{ $item->jasa?->nama ?? $item->nama_jasa }}</strong>
                                <div class="small text-muted">{{ $item->alamat }}</div>
                            </td>

                            <td>
                                {{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}
                                -
                                {{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}
                                <div class="fw-semibold">
                                    Rp {{ number_format($item->budget, 0, ',', '.') }}
                                </div>
                            </td>

                            <td style="min-width: 230px;">
                                @if($item->status_persetujuan === 'menunggu')
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.pemesanan.approve', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="keputusan" value="setuju">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Setuju
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#tolakModal{{ $item->id }}"
                                        >
                                            Tolak
                                        </button>
                                    </div>
                                @elseif($item->status_persetujuan === 'setuju')
                                    <span class="badge text-bg-success">Disetujui</span>
                                    <div class="small text-muted mt-1">
                                        {{ $item->diputuskanOleh?->name ?? '-' }}
                                    </div>
                                @else
                                    <span class="badge text-bg-danger">Ditolak</span>
                                    <div class="small text-muted mt-1">
                                        {{ $item->diputuskanOleh?->name ?? '-' }}
                                    </div>
                                    @if($item->catatan_admin)
                                        <div class="small mt-1">
                                            <strong>Alasan:</strong> {{ $item->catatan_admin }}
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <td style="min-width: 230px;">
                                @if($item->status_persetujuan === 'setuju')
                                    <form method="POST" action="{{ route('admin.pemesanan.assign', $item) }}" class="d-flex gap-1">
                                        @csrf
                                        @method('PATCH')

                                        <select name="tim_id" class="form-select form-select-sm" required>
                                            <option value="">Pilih Tim</option>
                                            @foreach($tim as $team)
                                                <option value="{{ $team->id }}" @selected($item->tim_id == $team->id)>
                                                    {{ $team->nama_tim }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Belum dapat dipilih</span>
                                @endif
                            </td>

                            <td style="min-width: 250px;">
                                @if($item->status_persetujuan === 'setuju' && $item->tim_id)
                                    <form method="POST" action="{{ route('admin.pemesanan.status', $item) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="d-flex gap-1">
                                            <select name="status_proses" class="form-select form-select-sm" required>
                                                <option value="pengerjaan" @selected($item->status_proses === 'pengerjaan')>
                                                    Pengerjaan
                                                </option>
                                                <option value="perbaikan" @selected($item->status_proses === 'perbaikan')>
                                                    Perbaikan
                                                </option>
                                                <option value="selesai" @selected($item->status_proses === 'selesai')>
                                                    Selesai
                                                </option>
                                            </select>

                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Update
                                            </button>
                                        </div>

                                        <textarea
                                            name="catatan_admin"
                                            class="form-control form-control-sm mt-2"
                                            rows="1"
                                            placeholder="Catatan status (opsional)"
                                        ></textarea>
                                    </form>
                                @elseif($item->status_proses === 'ditolak')
                                    <span class="badge text-bg-danger">Ditolak</span>
                                @elseif($item->status_proses === 'selesai')
                                    <span class="badge text-bg-success">Selesai</span>
                                @else
                                    <span class="text-muted">Belum siap dikerjakan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data pemesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal alasan penolakan --}}
@foreach($pemesanan as $item)
    @if($item->status_persetujuan === 'menunggu')
        <div class="modal fade" id="tolakModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.pemesanan.approve', $item) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="keputusan" value="tidak_setuju">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Tolak Pemesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p class="mb-3">
                                Pesanan <strong>{{ $item->jasa?->nama ?? $item->nama_jasa }}</strong>
                                akan ditolak.
                            </p>

                            <label class="form-label fw-semibold">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="catatan_admin"
                                class="form-control"
                                rows="4"
                                placeholder="Jelaskan alasan pesanan ditolak agar user mengetahuinya..."
                                required
                            ></textarea>

                            <div class="form-text">
                                Alasan ini akan dapat dilihat oleh user pada detail/status pesanannya.
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                Tolak Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
