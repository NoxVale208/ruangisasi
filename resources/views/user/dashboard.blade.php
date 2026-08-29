@extends('layouts.app')
@section('title', 'Pemesanan Jasa - Ruangisasi')
@section('content')
<div class="mb-4"><h2 class="fw-bold mb-1">Pemesanan Jasa</h2><p class="text-muted mb-0">Halo, {{ auth()->user()->name }}. Pilih jasa yang tersedia lalu pantau proses pesananmu.</p></div>

<div class="card shadow-sm mb-4"><div class="card-body p-4">
<h5 class="fw-bold">Buat Pemesanan</h5><p class="text-muted small">Jenis jasa dibuat oleh Admin/Super Admin. Tanggal mulai otomatis menggunakan hari ini.</p>
<form method="POST" action="{{ route('user.pemesanan.store') }}">@csrf
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Jenis Jasa</label><select name="jasa_id" class="form-select" required><option value="">-- Pilih Jasa --</option>@foreach($jasa as $item)<option value="{{ $item->id }}" @selected(old('jasa_id') == $item->id)>{{ $item->nama }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Tanggal Mulai</label><input class="form-control" value="{{ now()->format('d-m-Y') }}" readonly></div>
<div class="col-md-3"><label class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" min="{{ now()->format('Y-m-d') }}" value="{{ old('tanggal_selesai') }}" required></div>
<div class="col-12"><label class="form-label">Alamat</label><div class="input-group"><textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Alamat lokasi jasa..." required>{{ old('alamat') }}</textarea><button type="button" class="btn btn-outline-primary" id="lokasi-btn">Gunakan Lokasi</button></div><div class="form-text" id="lokasi-status">Lokasi perangkat dapat digunakan untuk mengisi alamat otomatis.</div></div>
<div class="col-md-6"><label class="form-label">Budget (Rp)</label><input type="number" min="0" name="budget" class="form-control" placeholder="Contoh: 5000000" value="{{ old('budget') }}" required></div>
<div class="col-md-6 d-flex align-items-end"><button class="btn btn-primary w-100" {{ $jasa->isEmpty() ? 'disabled' : '' }}>Kirim Pemesanan</button></div>
</div></form>
@if($jasa->isEmpty())<div class="alert alert-warning mt-3 mb-0">Belum ada jasa aktif. Tunggu Admin/Super Admin menambahkan jenis jasa.</div>@endif
</div></div>

<div class="card shadow-sm"><div class="card-body p-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h5 class="fw-bold mb-1">Status Pesanan Saya</h5><p class="text-muted small mb-0">Status akan berubah mengikuti proses persetujuan dan pengerjaan.</p></div></div>
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Jasa</th><th>Jadwal</th><th>Budget</th><th>Persetujuan</th><th>Tim</th><th>Status Pengerjaan</th></tr></thead><tbody>
@forelse($pemesanan as $item)
<tr><td><strong>{{ $item->jasa?->nama ?? $item->nama_jasa }}</strong><div class="small text-muted">{{ $item->alamat }}</div></td><td>{{ $item->tanggal_mulai->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }}</td><td>Rp {{ number_format($item->budget, 0, ',', '.') }}</td><td><span class="badge {{ $item->status_persetujuan === 'setuju' ? 'text-bg-success' : ($item->status_persetujuan === 'tidak_setuju' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $item->status_persetujuan === 'menunggu' ? 'Menunggu Persetujuan' : ucfirst(str_replace('_',' ', $item->status_persetujuan)) }}</span></td><td>{{ $item->tim?->nama_tim ?? 'Belum ditentukan' }}</td><td><span class="badge {{ in_array($item->status_proses, ['pengerjaan','perbaikan']) ? 'text-bg-primary' : ($item->status_proses === 'selesai' ? 'text-bg-success' : ($item->status_proses === 'ditolak' ? 'text-bg-danger' : 'text-bg-secondary')) }}">{{ ['menunggu'=>'Menunggu','menunggu_tim'=>'Menunggu Tim','pengerjaan'=>'Pengerjaan','perbaikan'=>'Perbaikan','selesai'=>'Selesai','ditolak'=>'Ditolak'][$item->status_proses] ?? ucfirst($item->status_proses) }}</span></td></tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">Belum ada pemesanan jasa.</td></tr>@endforelse
</tbody></table></div></div></div>
@endsection
@push('scripts')
<script>
const btn=document.getElementById('lokasi-btn'), alamat=document.getElementById('alamat'), status=document.getElementById('lokasi-status');
if(btn){btn.addEventListener('click',()=>{if(!navigator.geolocation){status.textContent='Browser tidak mendukung lokasi. Isi alamat manual.';return;}btn.disabled=true;status.textContent='Mengambil lokasi...';navigator.geolocation.getCurrentPosition(async({coords})=>{try{const r=await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${coords.latitude}&lon=${coords.longitude}`,{headers:{'Accept-Language':'id'}});const d=await r.json();alamat.value=d.display_name||`${coords.latitude}, ${coords.longitude}`;status.textContent='Alamat berhasil diisi otomatis.';}catch(e){alamat.value=`${coords.latitude}, ${coords.longitude}`;status.textContent='Alamat jalan gagal diterjemahkan, koordinat telah diisi.';}finally{btn.disabled=false;}},()=>{status.textContent='Izin lokasi ditolak. Isi alamat manual.';btn.disabled=false;},{enableHighAccuracy:true,timeout:10000});});}
</script>
@endpush
