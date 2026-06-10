<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased">
@auth
    <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
        <aside class="border-b border-line bg-white/90 backdrop-blur lg:sticky lg:top-0 lg:min-h-screen lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between px-5 py-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-ocean text-sm font-black text-white shadow-sm">I6</span>
                    <span>
                        <span class="block text-base font-black leading-tight tracking-tight">Inventori 6A1</span>
                        <span class="block text-xs font-semibold text-slate-500">Stock control panel</span>
                    </span>
                </a>
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold uppercase text-moss">{{ auth()->user()->role }}</span>
            </div>
            <nav class="grid gap-1 px-3 pb-5 text-sm lg:px-4">
                @php
                    $user = auth()->user();
                    $links = [['Dashboard', 'dashboard']];

                    if ($user->role === 'admin' || $user->role === 'petugas') {
                        $links = [
                            ...$links,
                            ['Barang', 'items.index'],
                            ['Riwayat Transaksi', 'transactions.index'],
                            ['Laporan Stok', 'reports.stock'],
                            ['Laporan Masuk', 'reports.transactions', ['type' => 'in']],
                            ['Laporan Keluar', 'reports.transactions', ['type' => 'out']],
                            ['Catatan Laporan', 'report-entries.index'],
                            ['Notifikasi', 'notifications.index'],
                            ['Chat Internal', 'chat.index'],
                        ];
                    }

                    if ($user->role === 'admin') {
                        array_splice($links, 2, 0, [['Kategori', 'categories.index']]);
                    }

                    if ($user->role === 'user') {
                        $links[] = ['Checkout', 'checkout.index'];
                    }

                    $links[] = ['Tracking J&T', 'tracking.index'];
                @endphp
                @foreach ($links as $link)
                    <a class="rounded-md px-3 py-2.5 font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-ocean {{ request()->routeIs($link[1]) ? 'bg-blue-50 text-ocean ring-1 ring-blue-100' : '' }}" href="{{ route($link[1], $link[2] ?? []) }}">{{ $link[0] }}</a>
                @endforeach
                <form method="post" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-md border border-rose-100 bg-rose-50 px-3 py-2.5 text-left font-bold text-coral transition hover:bg-rose-100">Keluar</button>
                </form>
            </nav>
        </aside>
        <main class="min-w-0 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 border-b border-line pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Halo, {{ auth()->user()->name }}</p>
                    <h1 class="text-2xl font-black tracking-tight text-ink">@yield('title', 'Dashboard')</h1>
                </div>
                @yield('actions')
            </div>
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-medium text-teal-800">{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">{{ session('info') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-coral">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-coral">{{ $errors->first() }}</div>
            @endif
            @yield('content')
        </main>
    </div>
@else
    @yield('content')
@endauth
</body>
</html>
