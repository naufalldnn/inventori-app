<?php $__env->startSection('title', 'Barang'); ?>
<?php $__env->startSection('actions'); ?>
<a href="<?php echo e(route('items.create')); ?>" class="btn-primary">Tambah Barang</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form class="mb-4 flex flex-col gap-2 rounded-lg border border-line bg-white p-3 shadow-sm sm:flex-row">
    <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kode atau nama barang" class="w-full rounded-md">
    <button class="btn-primary sm:w-28">Cari</button>
</form>

<div class="table-shell">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="p-4">Media</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="transition hover:bg-blue-50/40">
                    <td class="p-4">
                        <?php if($item->media_url): ?>
                            <?php if($item->media_type === 'video'): ?>
                                <video src="<?php echo e($item->media_url); ?>" class="h-14 w-20 rounded-md object-cover" muted></video>
                            <?php else: ?>
                                <img src="<?php echo e($item->media_url); ?>" alt="<?php echo e($item->name); ?>" class="h-14 w-20 rounded-md object-cover">
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="grid h-14 w-20 place-items-center rounded-md bg-slate-100 text-xs font-bold text-slate-400">No img</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 font-mono text-xs font-bold text-slate-600"><?php echo e($item->code); ?></td>
                    <td class="p-4 font-black"><?php echo e($item->name); ?></td>
                    <td class="p-4 text-slate-600"><?php echo e($item->category->name); ?></td>
                    <td class="p-4 font-bold">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                    <td class="p-4"><?php echo e($item->stock); ?> <?php echo e($item->unit); ?></td>
                    <td class="p-4">
                        <span class="status-pill <?php echo e($item->stock_status === 'aman' ? 'bg-teal-50 text-moss' : ($item->stock_status === 'menipis' ? 'bg-amber-50 text-amber' : 'bg-rose-50 text-coral')); ?>"><?php echo e($item->stock_status); ?></span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="<?php echo e(route('items.edit', $item)); ?>" class="font-bold text-ocean hover:underline">Edit</a>
                        <form method="post" action="<?php echo e(route('items.destroy', $item)); ?>" class="inline" onsubmit="return confirm('Hapus barang?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="ml-3 font-bold text-coral hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="border-t border-line p-4"><?php echo e($items->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/items/index.blade.php ENDPATH**/ ?>