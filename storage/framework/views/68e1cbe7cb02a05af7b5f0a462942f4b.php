

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('actions'); ?>
<div class="flex gap-2">
    <a href="<?php echo e(route('transactions.create', 'in')); ?>" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white">Barang Masuk</a>
    <a href="<?php echo e(route('transactions.create', 'out')); ?>" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Barang Keluar</a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid gap-4 md:grid-cols-4">
    <?php $__currentLoopData = [['Barang', $itemCount], ['Kategori', $categoryCount], ['Stok Menipis', $lowStockCount], ['Stok Habis', $emptyStockCount]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500"><?php echo e($label); ?></p>
            <p class="mt-2 text-3xl font-bold"><?php echo e($value); ?></p>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <section class="rounded-lg bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-bold">Transaksi Terbaru</h2>
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between border-b pb-3 text-sm">
                    <span><?php echo e($transaction->item->name); ?> oleh <?php echo e($transaction->user->name); ?></span>
                    <strong class="<?php echo e($transaction->type === 'in' ? 'text-moss' : 'text-coral'); ?>"><?php echo e($transaction->type === 'in' ? '+' : '-'); ?><?php echo e($transaction->quantity); ?></strong>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500">Belum ada transaksi.</p>
            <?php endif; ?>
        </div>
    </section>
    <section class="rounded-lg bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-bold">Stok Perlu Perhatian</h2>
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $lowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between border-b pb-3 text-sm">
                    <span><?php echo e($item->name); ?> <small class="text-gray-500">(<?php echo e($item->category->name); ?>)</small></span>
                    <strong class="<?php echo e($item->stock <= 0 ? 'text-coral' : 'text-amber'); ?>"><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></strong>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500">Semua stok aman.</p>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>