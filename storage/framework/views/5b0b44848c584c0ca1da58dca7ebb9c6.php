<?php $__env->startSection('title', 'Catatan Laporan'); ?>
<?php $__env->startSection('actions'); ?>
<a href="<?php echo e(route('report-entries.create')); ?>" class="btn-primary">Tambah Catatan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form class="mb-4 grid gap-2 rounded-lg border border-line bg-white p-3 shadow-sm md:grid-cols-5">
    <select name="type" class="rounded-md">
        <option value="">Semua Jenis</option>
        <option value="stock" <?php if(request('type') === 'stock'): echo 'selected'; endif; ?>>Stok</option>
        <option value="transaction" <?php if(request('type') === 'transaction'): echo 'selected'; endif; ?>>Transaksi</option>
        <option value="incident" <?php if(request('type') === 'incident'): echo 'selected'; endif; ?>>Kendala</option>
        <option value="other" <?php if(request('type') === 'other'): echo 'selected'; endif; ?>>Lainnya</option>
    </select>
    <select name="status" class="rounded-md">
        <option value="">Semua Status</option>
        <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
        <option value="submitted" <?php if(request('status') === 'submitted'): echo 'selected'; endif; ?>>Diajukan</option>
        <option value="reviewed" <?php if(request('status') === 'reviewed'): echo 'selected'; endif; ?>>Ditinjau</option>
    </select>
    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="rounded-md">
    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="rounded-md">
    <button class="btn-primary">Filter</button>
</form>

<div class="table-shell">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500">
                <tr><th class="p-4">Tanggal</th><th class="p-4">Judul</th><th class="p-4">Jenis</th><th class="p-4">Status</th><th class="p-4">Periode</th><th class="p-4">Petugas</th><th class="p-4"></th></tr>
            </thead>
            <tbody class="divide-y divide-line">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition hover:bg-blue-50/40">
                    <td class="p-4 font-medium text-slate-600"><?php echo e($report->created_at->format('d M Y')); ?></td>
                    <td class="p-4 font-black"><?php echo e($report->title); ?></td>
                    <td class="p-4"><?php echo e($report->type_label); ?></td>
                    <td class="p-4"><span class="status-pill bg-blue-50 text-ocean"><?php echo e($report->status_label); ?></span></td>
                    <td class="p-4 text-slate-600">
                        <?php echo e($report->period_from?->format('d M Y') ?? '-'); ?> - <?php echo e($report->period_to?->format('d M Y') ?? '-'); ?>

                    </td>
                    <td class="p-4 text-slate-600"><?php echo e($report->user->name); ?></td>
                    <td class="p-4 text-right"><a href="<?php echo e(route('report-entries.show', $report)); ?>" class="font-bold text-ocean">Detail</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="p-6 text-center text-slate-500">Belum ada catatan laporan.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4"><?php echo e($reports->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/report-entries/index.blade.php ENDPATH**/ ?>