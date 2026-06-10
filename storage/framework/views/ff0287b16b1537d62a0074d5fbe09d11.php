

<?php $__env->startSection('content'); ?>
<div class="grid min-h-screen place-items-center px-4">
    <form method="post" action="<?php echo e(route('login')); ?>" class="w-full max-w-md rounded-lg bg-white p-8 shadow-sm">
        <?php echo csrf_field(); ?>
        <h1 class="mb-6 text-2xl font-bold">Masuk Inventori</h1>
        <?php if(session('success')): ?>
            <div class="mb-4 rounded border border-moss/20 bg-moss/10 px-4 py-3 text-sm text-moss"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="mb-4 rounded border border-coral/20 bg-coral/10 px-4 py-3 text-sm text-coral"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>
        <label class="mb-4 block text-sm font-medium">Email
            <input name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="mb-4 block text-sm font-medium">Password
            <input name="password" type="password" required class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="mb-6 flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="rounded border-gray-300"> Ingat saya
        </label>
        <button class="w-full rounded bg-moss px-4 py-2 font-semibold text-white">Masuk</button>
        <div class="my-4 flex items-center gap-3 text-xs text-gray-400">
            <span class="h-px flex-1 bg-gray-200"></span>
            <span>atau</span>
            <span class="h-px flex-1 bg-gray-200"></span>
        </div>
        <?php if(config('services.google.client_id') && config('services.google.client_secret')): ?>
            <a href="<?php echo e(route('auth.google.redirect')); ?>" class="flex w-full items-center justify-center gap-2 rounded border border-gray-300 px-4 py-2 text-sm font-semibold text-ink">
                <span class="grid h-5 w-5 place-items-center rounded-full bg-white text-sm font-bold text-coral">G</span>
                Masuk dengan Google
            </a>
        <?php else: ?>
            <div class="rounded border border-gray-200 bg-gray-50 px-4 py-2 text-center text-sm text-gray-500">
                Login Google belum aktif.
            </div>
        <?php endif; ?>
        <a href="<?php echo e(route('register')); ?>" class="mt-4 block text-center text-sm text-moss">Daftar akun user</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>