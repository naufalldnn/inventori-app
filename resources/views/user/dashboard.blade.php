@extends('layouts.app')

@section('title', 'Dashboard Pembeli')

@section('actions')
    <a href="{{ route('checkout.index') }}" class="inline-block rounded bg-moss px-4 py-2 text-sm font-semibold text-white hover:bg-moss/90">
        Checkout Manual
    </a>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Total Pesanan</p>
            <p class="mt-1 text-2xl font-bold text-ink">{{ (int) ($orderSummary->total ?? 0) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Berhasil</p>
            <p class="mt-1 text-2xl font-bold text-moss">{{ (int) ($orderSummary->paid ?? 0) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Menunggu</p>
            <p class="mt-1 text-2xl font-bold text-yellow-600">{{ (int) ($orderSummary->pending ?? 0) }}</p>
        </div>
    </div>

    <section class="mt-6">
        <div class="mb-4 flex items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-moss">Katalog</p>
                <h2 class="text-xl font-bold text-ink">Barang tersedia</h2>
            </div>
            <a href="{{ route('checkout.index') }}" class="text-sm font-semibold text-moss hover:underline">Pesan manual</a>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($items as $item)
                <article class="flex overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm sm:block">
                    <div class="h-28 w-28 shrink-0 bg-gray-100 sm:h-auto sm:w-full sm:aspect-[4/3]">
                        @if ($item->media_url && $item->media_type !== 'video')
                            <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                        @else
                            <div class="grid h-full place-items-center px-2 text-center text-xs font-semibold text-gray-500">
                                {{ $item->category->name ?? 'Barang' }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="truncate font-bold text-ink">{{ $item->name }}</h3>
                        <p class="truncate text-xs text-gray-500">{{ $item->code }} &middot; {{ $item->category->name ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 rounded bg-moss/10 px-2 py-1 text-xs font-semibold text-moss">{{ $item->stock }}</span>
                        </div>
                        <p class="mt-2 font-semibold text-ink">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <p class="mt-2 line-clamp-2 text-sm text-gray-600">{{ $item->description ?: 'Barang siap dipesan.' }}</p>
                        <div class="mt-3 flex gap-2">
                            <form method="post" action="{{ route('cart.add') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full rounded bg-ocean px-3 py-2 text-center text-sm font-semibold text-white hover:bg-ocean/90">
                                    + Keranjang
                                </button>
                            </form>
                            <a
                                href="{{ route('checkout.index', ['item_id' => $item->id, 'description' => 'Pesanan '.$item->name]) }}"
                                class="flex-1 rounded bg-ink px-3 py-2 text-center text-sm font-semibold text-white hover:bg-ink/90"
                            >
                                Pesan
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500 sm:col-span-2 xl:col-span-4">
                    Belum ada barang tersedia untuk dipesan.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-8 rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-4 py-3">
            <h2 class="font-bold text-ink">Riwayat Pesanan</h2>
        </div>

        @if ($orders->isEmpty())
            <div class="p-8 text-center">
                <h3 class="font-semibold text-gray-900">Belum ada pesanan</h3>
                <p class="mt-1 text-sm text-gray-600">Pilih barang dari katalog untuk membuat pesanan pertama.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Invoice</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b last:border-0">
                                <td class="px-4 py-3 font-mono text-sm font-medium text-ink">{{ $order->invoice_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if ($order->items()->exists())
                                        {{ $order->items->pluck('item.name')->join(', ') }}
                                    @else
                                        {{ $order->item?->name ?? $order->description ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if ($order->status === 'paid')
                                            bg-moss/10 text-moss
                                        @elseif ($order->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif ($order->status === 'failed')
                                            bg-coral/10 text-coral
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($order->status === 'pending' && $order->payment_url)
                                        <a href="{{ $order->payment_url }}" class="font-semibold text-moss hover:underline">Bayar</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
