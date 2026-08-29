<?php $__env->startSection('title', 'Login - Ruangisasi'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center py-4">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">RUANGISASI</h2>
                    <p class="text-muted">Masuk ke akun Anda</p>
                </div>
                <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
                <form method="POST" action="<?php echo e(route('login.perform')); ?>"><?php echo csrf_field(); ?>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email"
                            class="form-control" value="<?php echo e(old('email')); ?>" required autofocus></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password"
                            class="form-control" required></div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" value="1"
                            id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar</a>
                </p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\go_event12\resources\views/auth/login.blade.php ENDPATH**/ ?>