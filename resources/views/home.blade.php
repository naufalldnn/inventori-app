<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventori 6A1 - Katalog Barang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased">
    <header class="sticky top-0 z-20 border-b border-line bg-white/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-ocean text-sm font-black text-white shadow-sm">I6</span>
                <span>
                    <span class="block text-base font-black leading-tight">Inventori 6A1</span>
                    <span class="block text-xs font-semibold text-slate-500">Katalog stok sekolah</span>
                </span>
            </a>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <section class="border-b border-line">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8 lg:py-12">
                <div class="flex min-h-[390px] flex-col justify-center">
                    <p class="w-fit rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-black uppercase tracking-wide text-ocean">Live inventory desk</p>
                    <h1 class="mt-5 max-w-2xl text-4xl font-black leading-tight tracking-tight text-ink sm:text-5xl">
                        Cari barang, cek stok, lalu checkout tanpa bolak-balik tanya admin.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">
                        Tampilan katalog dibuat lebih bersih untuk pembeli, sementara admin tetap punya ruang kerja stok, laporan, pembayaran, dan tracking.
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Buka Dashboard</a>
                            <a href="{{ route('checkout.index') }}" class="btn-secondary">Checkout Manual</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">Masuk untuk Pesan</a>
                            <a href="{{ route('register') }}" class="btn-secondary">Buat Akun</a>
                        @endauth
                    </div>
                    <div class="mt-8 grid max-w-xl grid-cols-3 gap-3">
                        <div class="rounded-lg border border-line bg-white p-4">
                            <p class="text-2xl font-black">{{ $items->count() }}</p>
                            <p class="text-xs font-semibold text-slate-500">barang tampil</p>
                        </div>
                        <div class="rounded-lg border border-line bg-white p-4">
                            <p class="text-2xl font-black text-moss">DOKU</p>
                            <p class="text-xs font-semibold text-slate-500">payment</p>
                        </div>
                        <div class="rounded-lg border border-line bg-white p-4">
                            <p class="text-2xl font-black text-coral">J&T</p>
                            <p class="text-xs font-semibold text-slate-500">tracking</p>
                        </div>
                    </div>
                </div>

                <div class="grid content-center">
                    <div class="rounded-lg border border-line bg-white p-3 shadow-sm">
                        <div class="grid gap-3 sm:grid-cols-2">
                            @forelse ($items->take(4) as $item)
                                <article class="{{ $loop->first ? 'sm:row-span-2' : '' }} overflow-hidden rounded-md border border-line bg-cloud">
                                    <div class="{{ $loop->first ? 'aspect-[4/3] sm:aspect-[4/5]' : 'aspect-[4/3]' }} bg-slate-100">
                                        @if ($item->media_url && $item->media_type !== 'video')
                                            <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                                        @elseif ($item->media_url && $item->media_type === 'video')
                                            <video src="{{ $item->media_url }}" class="h-full w-full object-cover" muted preload="metadata"></video>
                                        @else
                                            <div class="grid h-full place-items-center px-4 text-center text-sm font-bold text-slate-500">
                                                {{ $item->category->name ?? 'Barang' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h2 class="truncate font-black">{{ $item->name }}</h2>
                                                <p class="truncate text-xs font-medium text-slate-500">{{ $item->code }} / {{ $item->category->name ?? '-' }}</p>
                                                <p class="mt-1 text-sm font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                            <span class="status-pill shrink-0 bg-teal-50 text-moss">{{ $item->stock }} {{ $item->unit }}</span>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-lg border border-dashed border-line bg-cloud p-10 text-center sm:col-span-2">
                                    <h2 class="font-black">Katalog masih kosong</h2>
                                    <p class="mt-2 text-sm text-slate-600">Tambahkan barang dari dashboard admin supaya tampil di halaman utama.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-wide text-ocean">Etalase barang</p>
                    <h2 class="text-2xl font-black tracking-tight">Pilih barang tersedia</h2>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-ocean hover:underline">Lihat dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-ocean hover:underline">Masuk untuk checkout</a>
                @endauth
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($items as $item)
                    <article class="flex min-h-full flex-col overflow-hidden rounded-lg border border-line bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="aspect-[4/3] bg-slate-100">
                            @if ($item->media_url && $item->media_type !== 'video')
                                <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                            @elseif ($item->media_url && $item->media_type === 'video')
                                <video src="{{ $item->media_url }}" class="h-full w-full object-cover" muted preload="metadata"></video>
                            @else
                                <div class="grid h-full place-items-center px-3 text-center text-sm font-bold text-slate-500">
                                    {{ $item->category->name ?? 'Barang' }}
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate font-black">{{ $item->name }}</h3>
                                    <p class="mt-1 truncate text-xs font-medium text-slate-500">{{ $item->code }} / {{ $item->category->name ?? '-' }}</p>
                                </div>
                                <span class="status-pill shrink-0 bg-blue-50 text-ocean">{{ $item->stock }}</span>
                            </div>
                            <p class="mt-3 font-black">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $item->description ?: 'Barang siap dipesan dari katalog.' }}</p>
                            @auth
                                <a href="{{ route('checkout.index', ['item_id' => $item->id, 'description' => 'Pesanan '.$item->name]) }}" class="btn-primary mt-auto">Pesan</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary mt-auto">Masuk untuk Pesan</a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border border-dashed border-line bg-white p-8 text-center text-sm text-slate-500 sm:col-span-2 lg:col-span-4">
                        Belum ada barang tersedia.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
