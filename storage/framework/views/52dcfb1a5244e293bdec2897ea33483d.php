<?php $__env->startSection('title', 'Keranjang'); ?>

<?php $__env->startSection('actions'); ?>
<?php if($cartItems->count() > 0): ?>
    <form method="post" action="<?php echo e(route('cart.clear')); ?>" class="inline" onsubmit="return confirm('Hapus semua item dari keranjang?')">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn-primary bg-slate-500 hover:bg-slate-600">Kosongkan Keranjang</button>
    </form>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if($cartItems->count() > 0): ?>
    <div class="grid gap-6 lg:grid-cols-[1fr_20rem]">
        <section class="surface p-5">
            <div class="mb-4">
                <p class="text-xs font-black uppercase tracking-wide text-ocean">Belanja</p>
                <h2 class="font-black">Item di Keranjang</h2>
            </div>
            <div class="divide-y divide-line">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start justify-between gap-4 py-4">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold"><?php echo e($cartItem->item->name); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($cartItem->item->category->name); ?></p>
                            <p class="mt-1 text-sm font-semibold text-ocean">Rp <?php echo e(number_format($cartItem->item->price, 0, ',', '.')); ?></p>
                        </div>
                        <div class="flex flex-col items-end gap-3">
                            <div class="flex items-center gap-2">
                                <input type="number"
                                    min="1"
                                    max="<?php echo e($cartItem->item->stock); ?>"
                                    value="<?php echo e($cartItem->quantity); ?>"
                                    class="w-16 rounded border border-line px-2 py-1 text-center text-sm"
                                    data-cart-item-id="<?php echo e($cartItem->id); ?>"
                                    data-update-url="<?php echo e(route('cart.update', $cartItem)); ?>"
                                    onchange="updateQuantity(this)">
                                <span class="text-xs text-slate-500">/ <?php echo e($cartItem->item->stock); ?> <?php echo e($cartItem->item->unit); ?></span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Subtotal:</p>
                                <p class="font-bold text-moss" data-subtotal="<?php echo e($cartItem->id); ?>">Rp <?php echo e(number_format($cartItem->subtotal, 0, ',', '.')); ?></p>
                            </div>
                            <form method="post" action="<?php echo e(route('cart.remove', $cartItem)); ?>" class="inline" onsubmit="return confirm('Hapus item ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-xs font-bold text-coral hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>

        <aside class="h-fit surface p-5 sticky top-24">
            <div class="mb-4">
                <p class="text-xs font-black uppercase tracking-wide text-moss">Ringkasan</p>
                <h3 class="font-black">Total Belanja</h3>
            </div>
            <div class="divide-y divide-line">
                <div class="flex items-center justify-between py-3 text-sm">
                    <span><?php echo e($cartItems->count()); ?> Item</span>
                    <span class="font-bold">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </div>
                <div class="pt-4">
                    <form method="post" action="<?php echo e(route('checkout.from-cart')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full rounded-lg bg-moss px-4 py-2.5 font-bold text-white transition hover:bg-teal-700">
                            Lanjut ke Checkout
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    </div>

    <script>
        function updateQuantity(input) {
            const cartItemId = input.dataset.cartItemId;
            const quantity = input.value;
            const updateUrl = input.dataset.updateUrl;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ quantity: parseInt(quantity) })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        alert(data.error || 'Terjadi kesalahan');
                        location.reload();
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const subtotalElement = document.querySelector(`[data-subtotal="${cartItemId}"]`);
                    if (subtotalElement) {
                        subtotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.subtotal);
                    }
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate');
            });
        }
    </script>
<?php else: ?>
    <div class="rounded-lg border border-dashed border-line bg-cloud p-12 text-center">
        <p class="text-slate-500 font-medium mb-4">Keranjang Anda kosong</p>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn-primary bg-ocean hover:bg-blue-700">Kembali ke Dashboard</a>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/cart/index.blade.php ENDPATH**/ ?>