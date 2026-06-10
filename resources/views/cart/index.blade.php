@extends('layouts.app')

@section('title', 'Keranjang')

@section('actions')
@if ($cartItems->count() > 0)
    <form method="post" action="{{ route('cart.clear') }}" class="inline" onsubmit="return confirm('Hapus semua item dari keranjang?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-primary bg-slate-500 hover:bg-slate-600">Kosongkan Keranjang</button>
    </form>
@endif
@endsection

@section('content')
@if ($cartItems->count() > 0)
    <div class="grid gap-6 lg:grid-cols-[1fr_20rem]">
        <section class="surface p-5">
            <div class="mb-4">
                <p class="text-xs font-black uppercase tracking-wide text-ocean">Belanja</p>
                <h2 class="font-black">Item di Keranjang</h2>
            </div>
            <div class="divide-y divide-line">
                @foreach ($cartItems as $cartItem)
                    <div class="flex items-start justify-between gap-4 py-4">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold">{{ $cartItem->item->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cartItem->item->category->name }}</p>
                            <p class="mt-1 text-sm font-semibold text-ocean">Rp {{ number_format($cartItem->item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-3">
                            <div class="flex items-center gap-2">
                                <input type="number"
                                    min="1"
                                    max="{{ $cartItem->item->stock }}"
                                    value="{{ $cartItem->quantity }}"
                                    class="w-16 rounded border border-line px-2 py-1 text-center text-sm"
                                    data-cart-item-id="{{ $cartItem->id }}"
                                    data-update-url="{{ route('cart.update', $cartItem) }}"
                                    onchange="updateQuantity(this)">
                                <span class="text-xs text-slate-500">/ {{ $cartItem->item->stock }} {{ $cartItem->item->unit }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Subtotal:</p>
                                <p class="font-bold text-moss" data-subtotal="{{ $cartItem->id }}">Rp {{ number_format($cartItem->subtotal, 0, ',', '.') }}</p>
                            </div>
                            <form method="post" action="{{ route('cart.remove', $cartItem) }}" class="inline" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-coral hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="h-fit surface p-5 sticky top-24">
            <div class="mb-4">
                <p class="text-xs font-black uppercase tracking-wide text-moss">Ringkasan</p>
                <h3 class="font-black">Total Belanja</h3>
            </div>
            <div class="divide-y divide-line">
                <div class="flex items-center justify-between py-3 text-sm">
                    <span>{{ $cartItems->count() }} Item</span>
                    <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="pt-4">
                    <form method="post" action="{{ route('checkout.from-cart') }}">
                        @csrf
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
@else
    <div class="rounded-lg border border-dashed border-line bg-cloud p-12 text-center">
        <p class="text-slate-500 font-medium mb-4">Keranjang Anda kosong</p>
        <a href="{{ route('dashboard') }}" class="btn-primary bg-ocean hover:bg-blue-700">Kembali ke Dashboard</a>
    </div>
@endif
@endsection
