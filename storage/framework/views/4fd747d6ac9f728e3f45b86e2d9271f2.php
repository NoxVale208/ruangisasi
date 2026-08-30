<?php $__env->startSection('title', 'Super Admin - Ruangisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h2 class="fw-bold mb-1">Persetujuan Pemesanan</h2>
    <p class="text-muted mb-0">
        Super Admin dapat menyetujui atau menolak pengajuan pemesanan jasa.
    </p>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Pemesanan</h5>
                <small class="text-muted">
                    Periksa pesanan user sebelum disetujui atau ditolak.
                </small>
            </div>

            <span class="badge text-bg-warning">
                <?php echo e($pemesanan->where('status_persetujuan', 'menunggu')->count()); ?> Menunggu
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Pemesan</th>
                        <th>Jasa</th>
                        <th>Jadwal</th>
                        <th>Budget</th>
                        <th>Persetujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($item->user?->name ?? '-'); ?></strong>
                                <div class="small text-muted">
                                    <?php echo e($item->user?->email ?? '-'); ?>

                                </div>
                            </td>

                            <td>
                                <strong><?php echo e($item->jasa?->nama ?? $item->nama_jasa); ?></strong>
                                <div class="small text-muted">
                                    <?php echo e($item->alamat); ?>

                                </div>
                            </td>

                            <td>
                                <?php echo e($item->tanggal_mulai?->format('d/m/Y') ?? '-'); ?>

                                -
                                <?php echo e($item->tanggal_selesai?->format('d/m/Y') ?? '-'); ?>

                            </td>

                            <td class="fw-semibold">
                                Rp <?php echo e(number_format($item->budget, 0, ',', '.')); ?>

                            </td>

                            <td style="min-width: 240px;">
                                <?php if($item->status_persetujuan === 'menunggu'): ?>
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="<?php echo e(route('superadmin.pemesanan.approve', $item)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="keputusan" value="setuju">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Setuju
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#tolakSuperModal<?php echo e($item->id); ?>"
                                        >
                                            Tolak
                                        </button>
                                    </div>
                                <?php elseif($item->status_persetujuan === 'setuju'): ?>
                                    <span class="badge text-bg-success">Disetujui</span>
                                    <div class="small text-muted mt-1">
                                        Oleh: <?php echo e($item->diputuskanOleh?->name ?? '-'); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="badge text-bg-danger">Ditolak</span>
                                    <div class="small text-muted mt-1">
                                        Oleh: <?php echo e($item->diputuskanOleh?->name ?? '-'); ?>

                                    </div>
                                    <?php if($item->catatan_admin): ?>
                                        <div class="small mt-1">
                                            <strong>Alasan:</strong> <?php echo e($item->catatan_admin); ?>

                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
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
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada pemesanan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->status_persetujuan === 'menunggu'): ?>
        <div class="modal fade" id="tolakSuperModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="<?php echo e(route('superadmin.pemesanan.approve', $item)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <input type="hidden" name="keputusan" value="tidak_setuju">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Tolak Pemesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p>
                                Pesanan <strong><?php echo e($item->jasa?->nama ?? $item->nama_jasa); ?></strong>
                                akan ditolak.
                            </p>

                            <label class="form-label fw-semibold">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="catatan_admin"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan alasan penolakan..."
                                required
                            ></textarea>

                            <div class="form-text">
                                Alasan ini akan terlihat oleh user.
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
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\go_event12\resources\views/superadmin/dashboard.blade.php ENDPATH**/ ?>