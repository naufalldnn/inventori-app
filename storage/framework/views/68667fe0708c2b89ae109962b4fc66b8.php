

<?php $__env->startSection('title', 'Laporan Stok Barang'); ?>
<?php $__env->startSection('actions'); ?>
<a href="<?php echo e(route('reports.stock.pdf')); ?>" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Export PDF</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Kode</th><th class="p-4">Barang</th><th class="p-4">Kategori</th><th class="p-4">Stok</th><th class="p-4">Minimum</th><th class="p-4">Status</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t"><td class="p-4"><?php echo e($item->code); ?></td><td class="p-4"><?php echo e($item->name); ?></td><td class="p-4"><?php echo e($item->category->name); ?></td><td class="p-4"><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></td><td class="p-4"><?php echo e($item->minimum_stock); ?></td><td class="p-4"><?php echo e($item->stock_status); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/reports/stock.blade.php ENDPATH**/ ?>