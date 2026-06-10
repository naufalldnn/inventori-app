

<?php $__env->startSection('title', 'Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-3">
    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rounded-lg bg-white p-4 shadow-sm <?php echo e($notification->read_at ? 'opacity-70' : ''); ?>">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="rounded px-2 py-1 text-xs <?php echo e($notification->type === 'danger' ? 'bg-coral/10 text-coral' : ($notification->type === 'warning' ? 'bg-amber/10 text-amber' : 'bg-moss/10 text-moss')); ?>"><?php echo e($notification->type); ?></span>
                    <h2 class="mt-2 font-bold"><?php echo e($notification->title); ?></h2>
                    <p class="text-sm text-gray-600"><?php echo e($notification->message); ?></p>
                    <p class="mt-1 text-xs text-gray-400"><?php echo e($notification->created_at->format('d M Y H:i')); ?></p>
                </div>
                <?php if (! ($notification->read_at)): ?>
                    <form method="post" action="<?php echo e(route('notifications.read', $notification)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="rounded bg-ink px-3 py-2 text-sm text-white">Tandai Dibaca</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="rounded-lg bg-white p-5 text-sm text-gray-500 shadow-sm">Belum ada notifikasi.</p>
    <?php endif; ?>
    <?php echo e($notifications->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/notifications/index.blade.php ENDPATH**/ ?>