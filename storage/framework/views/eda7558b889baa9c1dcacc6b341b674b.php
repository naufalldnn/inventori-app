

<?php $__env->startSection('title', $item->exists ? 'Edit Barang' : 'Tambah Barang'); ?>

<?php $__env->startSection('content'); ?>
<form method="post" action="<?php echo e($item->exists ? route('items.update', $item) : route('items.store')); ?>" enctype="multipart/form-data" class="max-w-3xl rounded-lg bg-white p-6 shadow-sm">
    <?php echo csrf_field(); ?>
    <?php if($item->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-medium">Kategori
            <select name="category_id" required class="mt-1 w-full rounded border-gray-300">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $item->category_id) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label class="block text-sm font-medium">Kode
            <input name="code" value="<?php echo e(old('code', $item->code)); ?>" required class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Nama
            <input name="name" value="<?php echo e(old('name', $item->name)); ?>" required class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Satuan
            <input name="unit" value="<?php echo e(old('unit', $item->unit ?? 'pcs')); ?>" required class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Harga
            <div class="mt-1 flex items-center rounded border border-gray-300 bg-white">
                <span class="pl-3 text-sm text-gray-500">Rp</span>
                <input name="price" type="number" min="1000" step="1000" value="<?php echo e(old('price', $item->price ?? 10000)); ?>" required class="w-full border-0 bg-transparent focus:ring-0">
            </div>
        </label>
        <label class="block text-sm font-medium">Stok Awal
            <input name="stock" type="number" min="0" value="<?php echo e(old('stock', $item->stock ?? 0)); ?>" required class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Stok Minimum
            <input name="minimum_stock" type="number" min="0" value="<?php echo e(old('minimum_stock', $item->minimum_stock ?? 5)); ?>" required class="mt-1 w-full rounded border-gray-300">
        </label>
    </div>
    <label class="mt-4 block text-sm font-medium">Deskripsi
        <textarea name="description" rows="4" class="mt-1 w-full rounded border-gray-300"><?php echo e(old('description', $item->description)); ?></textarea>
    </label>
    <div class="mt-4">
        <label class="block text-sm font-medium">Gambar atau Video
            <input name="media" type="file" accept="image/*,video/*" class="mt-1 block w-full rounded border border-gray-300 p-2 text-sm">
        </label>

        <?php if($item->media_url): ?>
            <div class="mt-3 rounded border border-gray-200 p-3">
                <?php if($item->media_type === 'video'): ?>
                    <video src="<?php echo e($item->media_url); ?>" controls class="max-h-64 w-full rounded object-contain"></video>
                <?php else: ?>
                    <img src="<?php echo e($item->media_url); ?>" alt="<?php echo e($item->name); ?>" class="max-h-64 w-full rounded object-contain">
                <?php endif; ?>
                <label class="mt-3 flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remove_media" value="1" class="rounded border-gray-300">
                    Hapus media saat ini
                </label>
            </div>
        <?php endif; ?>
    </div>
    <button class="mt-6 rounded bg-moss px-4 py-2 font-semibold text-white">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/items/form.blade.php ENDPATH**/ ?>