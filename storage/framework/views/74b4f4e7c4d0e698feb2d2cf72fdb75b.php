<?php $__env->startSection('title', 'Admin - Ruangisasi'); ?>
<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Admin</h2>
        <p class="text-muted">Setujui pesanan, pilih tim pengerja, lalu pantau progresnya.</p>
    </div>
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = [['Total Pesanan', 'total', 'text-dark'], ['Menunggu Persetujuan', 'menunggu', 'text-warning'], ['Sedang Berjalan', 'berjalan', 'text-primary'], ['Selesai', 'selesai', 'text-success']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
                <div class="card shadow-sm stat-card p-4"><small class="text-muted"><?php echo e($stat[0]); ?></small>
                    <h2 class="fw-bold <?php echo e($stat[2]); ?>"><?php echo e($stats[$stat[1]]); ?></h2>
                </div>
        </div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->user->name); ?>

                                    <div class="small text-muted"><?php echo e($item->user->email); ?></div>
                                </td>
                                <td><strong><?php echo e($item->jasa?->nama ?? $item->nama_jasa); ?></strong>
                                    <div class="small text-muted"><?php echo e($item->alamat); ?></div>
                                </td>
                                <td><?php echo e($item->tanggal_mulai->format('d/m/Y')); ?> - <?php echo e($item->tanggal_selesai->format('d/m/Y')); ?>

                                    <div>Rp <?php echo e(number_format($item->budget, 0, ',', '.')); ?></div>
                                </td>
                                <td><?php if($item->status_persetujuan === 'menunggu'): ?>
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="<?php echo e(route('admin.pemesanan.approve', $item)); ?>"><?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?><input type="hidden" name="keputusan" value="setuju"><button
                                                class="btn btn-sm btn-success">Setuju</button></form>
                                        <form method="POST" action="<?php echo e(route('admin.pemesanan.approve', $item)); ?>"><?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?><input type="hidden" name="keputusan" value="tidak_setuju"><button
                                                class="btn btn-sm btn-outline-danger">Tolak</button></form>
                                </div><?php else: ?><span
                                        class="badge <?php echo e($item->status_persetujuan === 'setuju' ? 'text-bg-success' : 'text-bg-danger'); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $item->status_persetujuan))); ?></span>
                                    <div class="small text-muted mt-1"><?php echo e($item->diputuskanOleh?->name); ?></div><?php endif; ?>
                                </td>
                                <td><?php if($item->status_persetujuan === 'setuju'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.pemesanan.assign', $item)); ?>"
                                        class="d-flex gap-1"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><select name="tim_id"
                                            class="form-select form-select-sm" required>
                                            <option value="">Pilih Tim</option><?php $__currentLoopData = $tim; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($t->id); ?>" <?php if($item->tim_id === $t->id): echo 'selected'; endif; ?>><?php echo e($t->nama_tim); ?>

                                            </option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select><button class="btn btn-sm btn-primary">Simpan</button></form><?php else: ?><span
                                        class="text-muted">Menunggu persetujuan</span><?php endif; ?>
                                </td>
                                <td><?php if($item->status_persetujuan === 'setuju' && $item->tim_id): ?>
                                    <form method="POST" action="<?php echo e(route('admin.pemesanan.status', $item)); ?>"
                                        class="d-flex gap-1"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><select name="status_proses"
                                            class="form-select form-select-sm">
                                            <option value="pengerjaan" <?php if($item->status_proses === 'pengerjaan'): echo 'selected'; endif; ?>>Pengerjaan
                                            </option>
                                            <option value="perbaikan" <?php if($item->status_proses === 'perbaikan'): echo 'selected'; endif; ?>>Perbaikan
                                            </option>
                                            <option value="selesai" <?php if($item->status_proses === 'selesai'): echo 'selected'; endif; ?>>Selesai</option>
                                </select><button class="btn btn-sm btn-outline-primary">Update</button></form><?php else: ?><span
                                        class="text-muted"><?php echo e($item->status_proses === 'ditolak' ? 'Ditolak' : 'Belum siap dikerjakan'); ?></span><?php endif; ?>
                                </td>
                        </tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pemesanan.</td>
                        </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\go_event12\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>