<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Ruangisasi'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .brand { font-weight: 800; letter-spacing: .3px; }
        .hero { background: linear-gradient(135deg, #0d6efd, #6f42c1); color: white; border-radius: 24px; }
        .card { border: 0; border-radius: 18px; }
        .stat-card { min-height: 125px; }
        .table thead th { white-space: nowrap; }
        .badge-soft { background: #eef2ff; color: #4338ca; }
        .navbar { backdrop-filter: blur(10px); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <?php $role = auth()->user()->role ?? null; ?>
        <a class="navbar-brand brand text-primary" href="<?php echo e(route('home')); ?>">RUANGISASI</a>
        <div class="d-flex align-items-center gap-3">
            <?php if(auth()->guard()->check()): ?>
                <?php if($role === 'user'): ?>
                    <a class="text-decoration-none" href="<?php echo e(route('user.dashboard')); ?>">Pemesanan Jasa</a>
                <?php elseif($role === 'admin'): ?>
                    <a class="text-decoration-none" href="<?php echo e(route('admin.dashboard')); ?>">Pemesanan</a>
                    <a class="text-decoration-none" href="<?php echo e(route('admin.jasa.index')); ?>">Jasa</a>
                    <a class="text-decoration-none" href="<?php echo e(route('admin.tim.index')); ?>">Tim</a>
                <?php elseif($role === 'super_admin'): ?>
                    <a class="text-decoration-none" href="<?php echo e(route('superadmin.dashboard')); ?>">Pemesanan</a>
                    <a class="text-decoration-none" href="<?php echo e(route('superadmin.jasa.index')); ?>">Jasa</a>
                    <a class="text-decoration-none" href="<?php echo e(route('superadmin.tim.index')); ?>">Tim</a>
                <?php endif; ?>
                <span class="badge text-bg-dark"><?php echo e(str_replace('_', ' ', ucfirst($role))); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm">Keluar</button>
                </form>
            <?php else: ?>
                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('login')); ?>">Login</a>
                <a class="btn btn-primary btn-sm" href="<?php echo e(route('register')); ?>">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger shadow-sm">
            <strong>Periksa data:</strong>
            <ul class="mb-0 mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\go_event12\resources\views/layouts/app.blade.php ENDPATH**/ ?>