

<?php $__env->startSection('title', $type === 'in' ? 'Laporan Barang Masuk' : 'Laporan Barang Keluar'); ?>
<?php $__env->startSection('actions'); ?>
<a href="<?php echo e(route('reports.transactions.pdf', ['type' => $type] + request()->only('date_from', 'date_to'))); ?>" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Export PDF</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form class="mb-4 grid gap-2 md:grid-cols-3">
    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="rounded border-gray-300">
    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="rounded border-gray-300">
    <button class="rounded bg-ink px-4 py-2 text-white">Filter Tanggal</button>
</form>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Tanggal</th><th class="p-4">Barang</th><th class="p-4">Kategori</th><th class="p-4">Jumlah</th><th class="p-4">Petugas</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t"><td class="p-4"><?php echo e($transaction->transaction_date->format('d M Y')); ?></td><td class="p-4"><?php echo e($transaction->item->name); ?></td><td class="p-4"><?php echo e($transaction->item->category->name); ?></td><td class="p-4"><?php echo e($transaction->quantity); ?></td><td class="p-4"><?php echo e($transaction->user->name); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/reports/transactions.blade.php ENDPATH**/ ?>