

<?php $__env->startSection('title', 'Chat Internal'); ?>

<?php $__env->startSection('content'); ?>
<form method="post" action="<?php echo e(route('chat.start')); ?>" class="mb-6 flex gap-2 rounded-lg bg-white p-4 shadow-sm">
    <?php echo csrf_field(); ?>
    <select name="user_id" class="w-full rounded border-gray-300">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> - <?php echo e($user->role); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="rounded bg-moss px-4 py-2 font-semibold text-white">Mulai</button>
</form>
<div class="grid gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('chat.show', $conversation)); ?>" class="rounded-lg bg-white p-4 shadow-sm hover:ring-2 hover:ring-moss/20">
            <strong><?php echo e($conversation->otherUser(auth()->user())->name); ?></strong>
            <p class="mt-1 text-sm text-gray-500"><?php echo e(optional($conversation->messages->first())->body ?? 'Belum ada pesan.'); ?></p>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="rounded-lg bg-white p-5 text-sm text-gray-500 shadow-sm">Belum ada percakapan.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/chat/index.blade.php ENDPATH**/ ?>