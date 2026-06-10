

<?php $__env->startSection('title', 'Riwayat Transaksi'); ?>
<?php $__env->startSection('actions'); ?>
<div class="flex gap-2"><a href="<?php echo e(route('transactions.create', 'in')); ?>" class="btn-primary bg-moss hover:bg-teal-700">Masuk</a><a href="<?php echo e(route('transactions.create', 'out')); ?>" class="btn-primary bg-coral hover:bg-rose-700">Keluar</a></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form class="mb-4 grid gap-2 rounded-lg border border-line bg-white p-3 shadow-sm md:grid-cols-4">
    <select name="type" class="rounded-md"><option value="">Semua</option><option value="in" <?php if(request('type')==='in'): echo 'selected'; endif; ?>>Masuk</option><option value="out" <?php if(request('type')==='out'): echo 'selected'; endif; ?>>Keluar</option></select>
    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="rounded-md">
    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="rounded-md">
    <button class="btn-primary">Filter</button>
</form>
<div class="table-shell">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[860px] text-left text-sm">
        <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500"><tr><th class="p-4">Tanggal</th><th class="p-4">Barang</th><th class="p-4">Jenis</th><th class="p-4">Jumlah</th><th class="p-4">Petugas</th><th class="p-4">Catatan</th></tr></thead>
        <tbody class="divide-y divide-line">
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="transition hover:bg-blue-50/40"><td class="p-4 font-medium text-slate-600"><?php echo e($transaction->transaction_date->format('d M Y')); ?></td><td class="p-4 font-black"><?php echo e($transaction->item->name); ?></td><td class="p-4"><span class="status-pill <?php echo e($transaction->type === 'in' ? 'bg-teal-50 text-moss' : 'bg-rose-50 text-coral'); ?>"><?php echo e($transaction->type === 'in' ? 'Masuk' : 'Keluar'); ?></span></td><td class="p-4 font-bold"><?php echo e($transaction->quantity); ?></td><td class="p-4 text-slate-600"><?php echo e($transaction->user->name); ?></td><td class="p-4 text-slate-600"><?php echo e($transaction->notes); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <div class="p-4"><?php echo e($transactions->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/transactions/index.blade.php ENDPATH**/ ?>