<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('actions'); ?>
<div class="flex flex-wrap gap-2">
    <a href="<?php echo e(route('transactions.create', 'in')); ?>" class="btn-primary bg-moss hover:bg-teal-700">Barang Masuk</a>
    <a href="<?php echo e(route('transactions.create', 'out')); ?>" class="btn-primary bg-coral hover:bg-rose-700">Barang Keluar</a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid gap-4 md:grid-cols-4">
    <?php $__currentLoopData = [['Barang', $itemCount, 'text-ocean', 'bg-blue-50'], ['Kategori', $categoryCount, 'text-moss', 'bg-teal-50'], ['Stok Menipis', $lowStockCount, 'text-amber', 'bg-amber-50'], ['Stok Habis', $emptyStockCount, 'text-coral', 'bg-rose-50']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $tone, $bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="surface p-5">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-bold text-slate-500"><?php echo e($label); ?></p>
                <span class="h-3 w-3 rounded-full <?php echo e($bg); ?>"></span>
            </div>
            <p class="mt-3 text-3xl font-black tracking-tight <?php echo e($tone); ?>"><?php echo e($value); ?></p>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
    <section class="surface p-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-black uppercase tracking-wide text-ocean">Aktivitas</p>
                <h2 class="font-black">Transaksi Terbaru</h2>
            </div>
            <a href="<?php echo e(route('transactions.index')); ?>" class="text-sm font-bold text-ocean hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-line">
            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between gap-4 py-3 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-bold"><?php echo e($transaction->item->name); ?></p>
                        <p class="text-xs text-slate-500">oleh <?php echo e($transaction->user->name); ?></p>
                    </div>
                    <strong class="rounded-full px-3 py-1 text-sm <?php echo e($transaction->type === 'in' ? 'bg-teal-50 text-moss' : 'bg-rose-50 text-coral'); ?>">
                        <?php echo e($transaction->type === 'in' ? '+' : '-'); ?><?php echo e($transaction->quantity); ?>

                    </strong>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="rounded-lg border border-dashed border-line bg-cloud p-6 text-center text-sm text-slate-500">Belum ada transaksi.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="surface p-5">
        <div class="mb-4">
            <p class="text-xs font-black uppercase tracking-wide text-coral">Perlu dicek</p>
            <h2 class="font-black">Stok Perlu Perhatian</h2>
        </div>
        <div class="divide-y divide-line">
            <?php $__empty_1 = true; $__currentLoopData = $lowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between gap-4 py-3 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-bold"><?php echo e($item->name); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($item->category->name); ?></p>
                    </div>
                    <strong class="rounded-full px-3 py-1 <?php echo e($item->stock <= 0 ? 'bg-rose-50 text-coral' : 'bg-amber-50 text-amber'); ?>"><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></strong>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="rounded-lg border border-dashed border-line bg-cloud p-6 text-center text-sm text-slate-500">Semua stok aman.</p>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>