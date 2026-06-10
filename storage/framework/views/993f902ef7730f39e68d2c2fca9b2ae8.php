<?php $__env->startSection('title', $report->exists ? 'Edit Catatan Laporan' : 'Tambah Catatan Laporan'); ?>

<?php $__env->startSection('content'); ?>
<form method="post" action="<?php echo e($report->exists ? route('report-entries.update', $report) : route('report-entries.store')); ?>" class="max-w-3xl rounded-lg bg-white p-6 shadow-sm">
    <?php echo csrf_field(); ?>
    <?php if($report->exists): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>

    <label class="mb-4 block text-sm font-medium">Judul Laporan
        <input name="title" value="<?php echo e(old('title', $report->title)); ?>" required maxlength="150" class="mt-1 w-full rounded border-gray-300">
    </label>

    <div class="mb-4 grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-medium">Jenis
            <select name="type" required class="mt-1 w-full rounded border-gray-300">
                <option value="stock" <?php if(old('type', $report->type) === 'stock'): echo 'selected'; endif; ?>>Stok</option>
                <option value="transaction" <?php if(old('type', $report->type) === 'transaction'): echo 'selected'; endif; ?>>Transaksi</option>
                <option value="incident" <?php if(old('type', $report->type) === 'incident'): echo 'selected'; endif; ?>>Kendala</option>
                <option value="other" <?php if(old('type', $report->type) === 'other'): echo 'selected'; endif; ?>>Lainnya</option>
            </select>
        </label>
        <label class="block text-sm font-medium">Status
            <select name="status" required class="mt-1 w-full rounded border-gray-300">
                <option value="draft" <?php if(old('status', $report->status) === 'draft'): echo 'selected'; endif; ?>>Draft</option>
                <option value="submitted" <?php if(old('status', $report->status) === 'submitted'): echo 'selected'; endif; ?>>Diajukan</option>
                <option value="reviewed" <?php if(old('status', $report->status) === 'reviewed'): echo 'selected'; endif; ?>>Ditinjau</option>
            </select>
        </label>
    </div>

    <div class="mb-4 grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-medium">Periode Mulai
            <input name="period_from" type="date" value="<?php echo e(old('period_from', $report->period_from?->format('Y-m-d'))); ?>" class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Periode Selesai
            <input name="period_to" type="date" value="<?php echo e(old('period_to', $report->period_to?->format('Y-m-d'))); ?>" class="mt-1 w-full rounded border-gray-300">
        </label>
    </div>

    <label class="mb-6 block text-sm font-medium">Isi Catatan
        <textarea name="summary" rows="8" required class="mt-1 w-full rounded border-gray-300"><?php echo e(old('summary', $report->summary)); ?></textarea>
    </label>

    <div class="flex flex-wrap gap-2">
        <button class="btn-primary">Simpan</button>
        <a href="<?php echo e($report->exists ? route('report-entries.show', $report) : route('report-entries.index')); ?>" class="btn-secondary">Batal</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/report-entries/form.blade.php ENDPATH**/ ?>