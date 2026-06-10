<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans text-ink antialiased">
<?php if(auth()->guard()->check()): ?>
    <div class="min-h-screen lg:flex">
        <aside class="bg-ink text-white lg:min-h-screen lg:w-72">
            <div class="flex items-center justify-between px-6 py-5">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-xl font-bold">Inventori 6A1</a>
                <span class="rounded bg-moss px-2 py-1 text-xs uppercase"><?php echo e(auth()->user()->role); ?></span>
            </div>
            <nav class="grid gap-1 px-4 pb-6 text-sm">
                <?php
                    $user = auth()->user();
                    $links = [['Dashboard', 'dashboard']];

                    if ($user->role === 'admin' || $user->role === 'petugas') {
                        $links = [
                            ...$links,
                            ['Barang', 'items.index'],
                            ['Riwayat Transaksi', 'transactions.index'],
                            ['Laporan Stok', 'reports.stock'],
                            ['Laporan Masuk', 'reports.transactions', ['type' => 'in']],
                            ['Laporan Keluar', 'reports.transactions', ['type' => 'out']],
                            ['Notifikasi', 'notifications.index'],
                            ['Chat Internal', 'chat.index'],
                        ];
                    }

                    if ($user->role === 'admin') {
                        array_splice($links, 2, 0, [['Kategori', 'categories.index']]);
                    }

                    if ($user->role === 'user') {
                        $links[] = ['Checkout', 'checkout.index'];
                    }

                    $links[] = ['Tracking J&T', 'tracking.index'];
                ?>
                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="rounded px-4 py-2 hover:bg-white/10 <?php echo e(request()->routeIs($link[1]) ? 'bg-white/15' : ''); ?>" href="<?php echo e(route($link[1], $link[2] ?? [])); ?>"><?php echo e($link[0]); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <form method="post" action="<?php echo e(route('logout')); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full rounded bg-coral px-4 py-2 text-left font-semibold text-white">Keluar</button>
                </form>
            </nav>
        </aside>
        <main class="flex-1 px-4 py-6 sm:px-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Halo, <?php echo e(auth()->user()->name); ?></p>
                    <h1 class="text-2xl font-bold"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                </div>
                <?php echo $__env->yieldContent('actions'); ?>
            </div>
            <?php if(session('success')): ?>
                <div class="mb-4 rounded border border-moss/20 bg-moss/10 px-4 py-3 text-sm text-moss"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('info')): ?>
                <div class="mb-4 rounded border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700"><?php echo e(session('info')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mb-4 rounded border border-coral/20 bg-coral/10 px-4 py-3 text-sm text-coral"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-4 rounded border border-coral/20 bg-coral/10 px-4 py-3 text-sm text-coral"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
<?php else: ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>