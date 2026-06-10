<?php $__env->startSection('content'); ?>
<div class="grid min-h-screen px-4 py-8 lg:grid-cols-[1fr_30rem] lg:px-0 lg:py-0">
    <section class="hidden border-r border-line bg-white px-10 py-12 lg:flex lg:flex-col lg:justify-between">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-lg bg-ocean text-sm font-black text-white">I6</span>
            <span>
                <span class="block text-lg font-black leading-tight">Inventori 6A1</span>
                <span class="block text-xs font-semibold text-slate-500">Workspace persediaan</span>
            </span>
        </a>
        <div class="max-w-xl">
            <p class="w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-black uppercase tracking-wide text-ocean">Akses tim</p>
            <h1 class="mt-5 text-5xl font-black leading-tight tracking-tight">Masuk ke ruang kerja stok yang baru.</h1>
            <p class="mt-5 text-base leading-7 text-slate-600">Kelola barang, transaksi, laporan, chat, dan checkout dari panel yang lebih bersih dan cepat dipindai.</p>
        </div>
        <div class="grid grid-cols-3 gap-3 text-sm">
            <div class="rounded-lg border border-line bg-cloud p-4">
                <p class="font-black text-ocean">Admin</p>
                <p class="mt-1 text-slate-500">Kelola data</p>
            </div>
            <div class="rounded-lg border border-line bg-cloud p-4">
                <p class="font-black text-moss">Petugas</p>
                <p class="mt-1 text-slate-500">Transaksi</p>
            </div>
            <div class="rounded-lg border border-line bg-cloud p-4">
                <p class="font-black text-coral">User</p>
                <p class="mt-1 text-slate-500">Checkout</p>
            </div>
        </div>
    </section>

    <main class="grid place-items-center">
        <form method="post" action="<?php echo e(route('login')); ?>" class="w-full max-w-md rounded-lg border border-line bg-white p-6 shadow-sm sm:p-8">
            <?php echo csrf_field(); ?>
            <div class="mb-6">
                <p class="text-sm font-black uppercase tracking-wide text-ocean">Selamat datang</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">Masuk akun</h1>
            </div>
            <?php if(session('success')): ?>
                <div class="mb-4 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-medium text-teal-800"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-coral"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>
            <label class="mb-4 block text-sm font-bold text-slate-700">Email
                <input name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus class="mt-1 w-full rounded-md">
            </label>
            <label class="mb-4 block text-sm font-bold text-slate-700">Password
                <input name="password" type="password" required class="mt-1 w-full rounded-md">
            </label>
            <label class="mb-6 flex items-center gap-2 text-sm font-medium text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-line text-ocean"> Ingat saya
            </label>
            <button class="btn-primary w-full">Masuk</button>
            <div class="my-4 flex items-center gap-3 text-xs font-semibold text-slate-400">
                <span class="h-px flex-1 bg-line"></span>
                <span>atau</span>
                <span class="h-px flex-1 bg-line"></span>
            </div>
            <?php if(config('services.google.client_id') && config('services.google.client_secret')): ?>
                <a href="<?php echo e(route('auth.google.redirect')); ?>" class="btn-secondary w-full">
                    <span class="mr-2 grid h-5 w-5 place-items-center rounded-full border border-line bg-white text-xs font-black text-coral">G</span>
                    Masuk dengan Google
                </a>
            <?php else: ?>
                <div class="rounded-lg border border-line bg-cloud px-4 py-2 text-center text-sm text-slate-500">
                    Login Google belum aktif.
                </div>
            <?php endif; ?>
            <a href="<?php echo e(route('register')); ?>" class="mt-4 block text-center text-sm font-bold text-ocean hover:underline">Daftar akun user</a>
        </form>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>