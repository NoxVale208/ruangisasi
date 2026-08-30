<?php $__env->startSection('title', 'Pemesanan Jasa - Ruangisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h2 class="fw-bold mb-1">Pemesanan Jasa</h2>
    <p class="text-muted mb-0">
        Halo, <?php echo e(auth()->user()->name); ?>. Pilih jasa yang tersedia lalu pantau proses pesananmu.
    </p>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Buat Pemesanan</h5>
        <p class="text-muted small mb-4">
            Jenis jasa dibuat oleh Admin/Super Admin. Tanggal mulai otomatis menggunakan hari ini.
        </p>

        <form method="POST" action="<?php echo e(route('user.pemesanan.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Jasa</label>
                    <select name="jasa_id" class="form-select" required>
                        <option value="">-- Pilih Jasa --</option>
                        <?php $__currentLoopData = $jasa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" <?php if(old('jasa_id') == $item->id): echo 'selected'; endif; ?>>
                                <?php echo e($item->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?php echo e(now()->format('d-m-Y')); ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="form-control"
                        min="<?php echo e(now()->format('Y-m-d')); ?>"
                        value="<?php echo e(old('tanggal_selesai')); ?>"
                        required
                    >
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <div class="input-group">
                        <textarea
                            name="alamat"
                            id="alamat"
                            class="form-control"
                            rows="2"
                            placeholder="Alamat lokasi jasa..."
                            required
                        ><?php echo e(old('alamat')); ?></textarea>
                        <button type="button" class="btn btn-outline-primary" id="lokasi-btn">
                            Gunakan Lokasi
                        </button>
                    </div>
                    <div class="form-text" id="lokasi-status">
                        Lokasi perangkat dapat digunakan untuk mengisi alamat otomatis.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Budget (Rp)</label>
                    <input
                        type="number"
                        min="0"
                        name="budget"
                        class="form-control"
                        placeholder="Contoh: 5000000"
                        value="<?php echo e(old('budget')); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        <?php echo e($jasa->isEmpty() ? 'disabled' : ''); ?>

                    >
                        Kirim Pemesanan
                    </button>
                </div>
            </div>
        </form>

        <?php if($jasa->isEmpty()): ?>
            <div class="alert alert-warning mt-3 mb-0">
                Belum ada jasa aktif. Tunggu Admin/Super Admin menambahkan jenis jasa.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">Status Pesanan Saya</h5>
                <p class="text-muted small mb-0">
                    Kamu dapat melihat persetujuan, tim pengerja, status, dan alasan penolakan.
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Jasa</th>
                        <th>Jadwal</th>
                        <th>Budget</th>
                        <th>Persetujuan</th>
                        <th>Tim</th>
                        <th>Status Pengerjaan</th>
                        <th>Riwayat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($item->jasa?->nama ?? $item->nama_jasa); ?></strong>
                                <div class="small text-muted"><?php echo e($item->alamat); ?></div>
                            </td>

                            <td>
                                <?php echo e($item->tanggal_mulai?->format('d/m/Y') ?? '-'); ?>

                                -
                                <?php echo e($item->tanggal_selesai?->format('d/m/Y') ?? '-'); ?>

                            </td>

                            <td>
                                Rp <?php echo e(number_format($item->budget, 0, ',', '.')); ?>

                            </td>

                            <td style="min-width: 220px;">
                                <?php if($item->status_persetujuan === 'setuju'): ?>
                                    <span class="badge text-bg-success">Disetujui</span>
                                <?php elseif($item->status_persetujuan === 'tidak_setuju'): ?>
                                    <span class="badge text-bg-danger">Ditolak</span>

                                    <?php if($item->catatan_admin): ?>
                                        <div class="small text-danger mt-2">
                                            <strong>Alasan:</strong><br>
                                            <?php echo e($item->catatan_admin); ?>

                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge text-bg-warning">Menunggu Persetujuan</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php echo e($item->tim?->nama_tim ?? 'Belum ditentukan'); ?>

                            </td>

                            <td>
                                <?php
                                    $statusLabel = [
                                        'menunggu' => 'Menunggu',
                                        'menunggu_tim' => 'Menunggu Tim',
                                        'pengerjaan' => 'Pengerjaan',
                                        'perbaikan' => 'Perbaikan',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                    ];
                                ?>

                                <span class="badge
                                    <?php echo e($item->status_proses === 'selesai' ? 'text-bg-success' : ''); ?>

                                    <?php echo e($item->status_proses === 'ditolak' ? 'text-bg-danger' : ''); ?>

                                    <?php echo e(in_array($item->status_proses, ['pengerjaan', 'perbaikan']) ? 'text-bg-primary' : ''); ?>

                                    <?php echo e(in_array($item->status_proses, ['menunggu', 'menunggu_tim']) ? 'text-bg-secondary' : ''); ?>

                                ">
                                    <?php echo e($statusLabel[$item->status_proses] ?? ucfirst($item->status_proses)); ?>

                                </span>
                            </td>

                            <td>
                                <?php if($item->riwayatStatus->count()): ?>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#riwayatModal<?php echo e($item->id); ?>"
                                    >
                                        Lihat
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada pemesanan jasa.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->riwayatStatus->count()): ?>
        <div class="modal fade" id="riwayatModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Riwayat Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <h6 class="fw-bold mb-3">
                            <?php echo e($item->jasa?->nama ?? $item->nama_jasa); ?>

                        </h6>

                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $item->riwayatStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>
                                            <?php switch($history->status):
                                                case ('menunggu'): ?> Menunggu Persetujuan <?php break; ?>
                                                <?php case ('menunggu_tim'): ?> Menunggu Tim <?php break; ?>
                                                <?php case ('pengerjaan'): ?> Pengerjaan <?php break; ?>
                                                <?php case ('perbaikan'): ?> Perbaikan <?php break; ?>
                                                <?php case ('selesai'): ?> Selesai <?php break; ?>
                                                <?php case ('ditolak'): ?> Ditolak <?php break; ?>
                                                <?php default: ?> <?php echo e(ucfirst($history->status)); ?>

                                            <?php endswitch; ?>
                                        </strong>

                                        <small class="text-muted">
                                            <?php echo e($history->diubah_pada?->format('d/m/Y H:i')); ?>

                                        </small>
                                    </div>

                                    <?php if($history->catatan): ?>
                                        <div class="small mt-1">
                                            <?php echo e($history->catatan); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php if($history->pengguna): ?>
                                        <small class="text-muted">
                                            Oleh: <?php echo e($history->pengguna->name); ?>

                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const lokasiButton = document.getElementById('lokasi-btn');
    const alamatInput = document.getElementById('alamat');
    const lokasiStatus = document.getElementById('lokasi-status');

    if (lokasiButton) {
        lokasiButton.addEventListener('click', () => {
            if (!navigator.geolocation) {
                lokasiStatus.textContent = 'Browser tidak mendukung lokasi. Isi alamat manual.';
                return;
            }

            lokasiButton.disabled = true;
            lokasiStatus.textContent = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                async ({ coords }) => {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${coords.latitude}&lon=${coords.longitude}`,
                            { headers: { 'Accept-Language': 'id' } }
                        );

                        const data = await response.json();
                        alamatInput.value = data.display_name || `${coords.latitude}, ${coords.longitude}`;
                        lokasiStatus.textContent = 'Alamat berhasil diisi otomatis.';
                    } catch (error) {
                        alamatInput.value = `${coords.latitude}, ${coords.longitude}`;
                        lokasiStatus.textContent = 'Alamat jalan gagal diterjemahkan, koordinat telah diisi.';
                    } finally {
                        lokasiButton.disabled = false;
                    }
                },
                () => {
                    lokasiStatus.textContent = 'Izin lokasi ditolak. Isi alamat manual.';
                    lokasiButton.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                }
            );
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\go_event12\resources\views/user/dashboard.blade.php ENDPATH**/ ?>