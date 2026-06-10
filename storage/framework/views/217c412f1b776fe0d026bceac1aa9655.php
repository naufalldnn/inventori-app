

<?php $__env->startSection('title', 'Barang'); ?>
<?php $__env->startSection('actions'); ?>
<a href="<?php echo e(route('items.create')); ?>" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white">Tambah Barang</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form class="mb-4 flex gap-2">
    <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kode atau nama barang" class="w-full rounded border-gray-300">
    <button class="rounded bg-ink px-4 py-2 text-white">Cari</button>
</form>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Media</th><th class="p-4">Kode</th><th class="p-4">Nama</th><th class="p-4">Kategori</th><th class="p-4">Harga</th><th class="p-4">Stok</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t">
                <td class="p-4">
                    <?php if($item->media_url): ?>
                        <?php if($item->media_type === 'video'): ?>
                            <video src="<?php echo e($item->media_url); ?>" class="h-14 w-20 rounded object-cover" muted></video>
                        <?php else: ?>
                            <img src="<?php echo e($item->media_url); ?>" alt="<?php echo e($item->name); ?>" class="h-14 w-20 rounded object-cover">
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="block h-14 w-20 rounded bg-gray-100"></span>
                    <?php endif; ?>
                </td>
                <td class="p-4 font-semibold"><?php echo e($item->code); ?></td>
                <td class="p-4"><?php echo e($item->name); ?></td>
                <td class="p-4"><?php echo e($item->category->name); ?></td>
                <td class="p-4">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                <td class="p-4"><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></td>
                <td class="p-4"><span class="rounded px-2 py-1 text-xs <?php echo e($item->stock_status === 'aman' ? 'bg-moss/10 text-moss' : ($item->stock_status === 'menipis' ? 'bg-amber/10 text-amber' : 'bg-coral/10 text-coral')); ?>"><?php echo e($item->stock_status); ?></span></td>
                <td class="p-4 text-right">
                    <a href="<?php echo e(route('items.edit', $item)); ?>" class="text-moss">Edit</a>
                    <form method="post" action="<?php echo e(route('items.destroy', $item)); ?>" class="inline" onsubmit="return confirm('Hapus barang?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="ml-3 text-coral">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="p-4"><?php echo e($items->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/items/index.blade.php ENDPATH**/ ?>