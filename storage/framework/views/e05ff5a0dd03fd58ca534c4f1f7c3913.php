

<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Checkout Pesanan</h2>
        <p class="mt-1 text-sm text-gray-600">Cek detail pesanan sebelum lanjut ke halaman pembayaran DOKU.</p>

        <?php if($errors->any()): ?>
            <div class="mb-4 rounded border border-coral/20 bg-coral/10 px-4 py-3 text-sm text-coral">
                <ul class="list-inside list-disc">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('checkout.process')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php if($item): ?>
                <input type="hidden" name="item_id" value="<?php echo e($item->id); ?>">
                <div class="mt-5 flex gap-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded bg-white">
                        <?php if($item->media_url && $item->media_type !== 'video'): ?>
                            <img src="<?php echo e($item->media_url); ?>" alt="<?php echo e($item->name); ?>" class="h-full w-full object-cover" loading="lazy">
                        <?php else: ?>
                            <div class="grid h-full place-items-center px-2 text-center text-xs font-semibold text-gray-500">
                                <?php echo e($item->category->name ?? 'Barang'); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-moss">Barang dipilih</p>
                        <h3 class="truncate font-bold text-ink"><?php echo e($item->name); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo e($item->code); ?> &middot; Stok <?php echo e($item->stock); ?> <?php echo e($item->unit); ?></p>
                        <p class="mt-1 text-sm font-semibold text-ink">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Amount Field -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">
                    Jumlah Pembayaran <span class="text-coral">*</span>
                </label>
                <?php if($item): ?>
                    <div class="mt-1 rounded border border-gray-200 bg-gray-100 px-3 py-2 font-semibold text-ink">
                        Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Jumlah pembayaran mengikuti harga barang dan tidak bisa diubah pembeli.
                    </p>
                <?php else: ?>
                    <div class="mt-1 flex items-center">
                        <span class="text-gray-500">Rp</span>
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            step="1000"
                            min="1000"
                            value="<?php echo e(old('amount', request('amount', 10000))); ?>"
                            class="ml-2 flex-1 rounded border border-gray-300 px-3 py-2 focus:border-ink focus:outline-none"
                            placeholder="10000"
                            required
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimal pembayaran: Rp 1.000</p>
                <?php endif; ?>
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi (Opsional)
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-ink focus:outline-none"
                    placeholder="Masukkan deskripsi pembelian..."
                ><?php echo e(old('description', request('description'))); ?></textarea>
            </div>

            <!-- Summary -->
            <div class="rounded bg-gray-50 p-4">
                <p class="text-sm text-gray-600">Jumlah yang akan dibayarkan:</p>
                <p class="mt-2 text-2xl font-bold text-ink">
                    Rp <span id="totalAmount"><?php echo e(number_format($item ? $item->price : old('amount', request('amount', 10000)), 0, ',', '.')); ?></span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex-1 rounded border border-gray-300 px-4 py-2 text-center font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="flex-1 rounded bg-moss px-4 py-2 font-semibold text-white hover:bg-moss/90">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </div>

    <script>
        const amountInput = document.getElementById('amount');
        const totalAmountSpan = document.getElementById('totalAmount');

        function updateTotal() {
            if (!amountInput) {
                return;
            }

            const amount = parseInt(amountInput.value) || 0;
            totalAmountSpan.textContent = amount.toLocaleString('id-ID');
        }

        if (amountInput) {
            amountInput.addEventListener('change', updateTotal);
            amountInput.addEventListener('input', updateTotal);
        }

        // Initial update
        updateTotal();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/checkout.blade.php ENDPATH**/ ?>