<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventori 6A1 - Katalog Barang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f6f7f2] font-sans text-ink antialiased">
    <header class="sticky top-0 z-20 border-b border-black/5 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded bg-ink text-sm font-black text-white">I</span>
                <span class="text-lg font-bold">Inventori 6A1</span>
            </a>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded bg-ink px-4 py-2 text-sm font-semibold text-white hover:bg-ink/90">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded px-4 py-2 text-sm font-semibold text-ink hover:bg-black/5">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white hover:bg-moss/90">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <section class="border-b border-black/5 bg-[#eef2e8]">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-14">
                <div class="flex min-h-[430px] flex-col justify-center">
                    <p class="text-sm font-semibold uppercase tracking-wide text-coral">Katalog dan checkout</p>
                    <h1 class="mt-3 max-w-2xl text-4xl font-bold leading-tight text-ink sm:text-5xl">
                        Barang gudang siap dicek, dipilih, dan dibayar lewat DOKU.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-gray-700">
                        Pembeli bisa langsung pesan dari katalog, sementara admin tetap punya dashboard untuk stok, laporan, notifikasi, dan transaksi internal.
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded bg-ink px-5 py-3 text-center font-semibold text-white hover:bg-ink/90">Buka Dashboard</a>
                            <a href="{{ route('checkout.index') }}" class="rounded border border-ink/15 bg-white px-5 py-3 text-center font-semibold text-ink hover:bg-white/70">Checkout Manual</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded bg-ink px-5 py-3 text-center font-semibold text-white hover:bg-ink/90">Masuk untuk Pesan</a>
                            <a href="{{ route('register') }}" class="rounded border border-ink/15 bg-white px-5 py-3 text-center font-semibold text-ink hover:bg-white/70">Buat Akun</a>
                        @endauth
                    </div>
                    <div class="mt-8 grid max-w-xl grid-cols-3 gap-3 text-sm">
                        <div class="border-l-2 border-moss pl-3">
                            <p class="font-bold text-ink">{{ $items->count() }}</p>
                            <p class="text-gray-600">barang tampil</p>
                        </div>
                        <div class="border-l-2 border-amber pl-3">
                            <p class="font-bold text-ink">DOKU</p>
                            <p class="text-gray-600">pembayaran</p>
                        </div>
                        <div class="border-l-2 border-coral pl-3">
                            <p class="font-bold text-ink">Cloudinary</p>
                            <p class="text-gray-600">media produk</p>
                        </div>
                    </div>
                </div>

                <div class="grid content-center gap-4">
                    @if ($items->isNotEmpty())
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($items->take(4) as $item)
                                <article class="{{ $loop->first ? 'sm:row-span-2' : '' }} overflow-hidden rounded-lg border border-black/5 bg-white shadow-sm">
                                    <div class="{{ $loop->first ? 'aspect-[4/3] sm:aspect-[4/5]' : 'aspect-[4/3]' }} bg-gray-100">
                                        @if ($item->media_url && $item->media_type !== 'video')
                                            <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                                        @elseif ($item->media_url && $item->media_type === 'video')
                                            <video src="{{ $item->media_url }}" class="h-full w-full object-cover" muted preload="metadata"></video>
                                        @else
                                            <div class="grid h-full place-items-center px-4 text-center text-sm font-semibold text-gray-500">
                                                {{ $item->category->name ?? 'Barang' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h2 class="truncate font-bold text-ink">{{ $item->name }}</h2>
                                                <p class="truncate text-xs text-gray-500">{{ $item->code }} &middot; {{ $item->category->name ?? '-' }}</p>
                                                <p class="mt-1 text-sm font-semibold text-ink">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                            <span class="shrink-0 rounded bg-moss/10 px-2 py-1 text-xs font-semibold text-moss">{{ $item->stock }} {{ $item->unit }}</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-10 text-center">
                            <h2 class="font-bold text-ink">Katalog masih kosong</h2>
                            <p class="mt-2 text-sm text-gray-600">Tambahkan barang dari dashboard admin supaya tampil di halaman utama.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-moss">Barang tersedia</p>
                    <h2 class="text-2xl font-bold">Pilih dari katalog</h2>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-moss hover:underline">Lihat riwayat pesanan</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-moss hover:underline">Masuk untuk checkout</a>
                @endauth
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($items as $item)
                    <article class="flex min-h-full flex-col overflow-hidden rounded-lg border border-black/5 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="aspect-[4/3] bg-gray-100">
                            @if ($item->media_url && $item->media_type !== 'video')
                                <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                            @elseif ($item->media_url && $item->media_type === 'video')
                                <video src="{{ $item->media_url }}" class="h-full w-full object-cover" muted preload="metadata"></video>
                            @else
                                <div class="grid h-full place-items-center px-3 text-center text-sm font-semibold text-gray-500">
                                    {{ $item->category->name ?? 'Barang' }}
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <h3 class="truncate font-bold text-ink">{{ $item->name }}</h3>
                            <p class="mt-1 truncate text-xs text-gray-500">{{ $item->code }} &middot; {{ $item->category->name ?? '-' }}</p>
                            <p class="mt-3 font-semibold text-ink">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="mt-3 text-sm text-gray-600">Stok {{ $item->stock }} {{ $item->unit }}</p>
                            <p class="mt-2 line-clamp-2 text-sm text-gray-600">{{ $item->description ?: 'Barang siap dipesan dari katalog.' }}</p>
                            @auth
                                <a href="{{ route('checkout.index', ['item_id' => $item->id, 'description' => 'Pesanan '.$item->name]) }}" class="mt-auto rounded bg-ink px-4 py-2 text-center text-sm font-semibold text-white hover:bg-ink/90">Pesan</a>
                            @else
                                <a href="{{ route('login') }}" class="mt-auto rounded bg-ink px-4 py-2 text-center text-sm font-semibold text-white hover:bg-ink/90">Masuk untuk Pesan</a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500 sm:col-span-2 lg:col-span-4">
                        Belum ada barang tersedia.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
