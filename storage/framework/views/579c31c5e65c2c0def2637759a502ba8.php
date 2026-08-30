<?php $__env->startSection('title', 'Daftar - Ruangisasi'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center py-4">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Buat Akun</h2>
                    <p class="text-muted">Daftar sebagai User Ruangisasi</p>
                </div>
                <form method="POST" action="<?php echo e(route('register.perform')); ?>"><?php echo csrf_field(); ?>
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input name="name" class="form-control"
                            value="<?php echo e(old('name')); ?>" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email"
                            class="form-control" value="<?php echo e(old('email')); ?>" required></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password"
                            class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Konfirmasi Password</label><input type="password"
                            name="password_confirmation" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Daftar</button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Login</a></p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\go_event12\resources\views/auth/register.blade.php ENDPATH**/ ?>