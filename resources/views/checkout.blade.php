@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="mx-auto max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Checkout Pesanan</h2>
        <p class="mt-1 text-sm text-gray-600">Cek detail pesanan sebelum lanjut ke halaman pembayaran DOKU.</p>

        @if ($errors->any())
            <div class="mb-4 rounded border border-coral/20 bg-coral/10 px-4 py-3 text-sm text-coral">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
            @csrf
            @if ($item)
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="mt-5 flex gap-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded bg-white">
                        @if ($item->media_url && $item->media_type !== 'video')
                            <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                        @else
                            <div class="grid h-full place-items-center px-2 text-center text-xs font-semibold text-gray-500">
                                {{ $item->category->name ?? 'Barang' }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-moss">Barang dipilih</p>
                        <h3 class="truncate font-bold text-ink">{{ $item->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->code }} &middot; Stok {{ $item->stock }} {{ $item->unit }}</p>
                        <p class="mt-1 text-sm font-semibold text-ink">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endif

            <!-- Amount Field -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">
                    Jumlah Pembayaran <span class="text-coral">*</span>
                </label>
                @if ($item)
                    <div class="mt-1 rounded border border-gray-200 bg-gray-100 px-3 py-2 font-semibold text-ink">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Jumlah pembayaran mengikuti harga barang dan tidak bisa diubah pembeli.
                    </p>
                @else
                    <div class="mt-1 flex items-center">
                        <span class="text-gray-500">Rp</span>
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            step="1000"
                            min="1000"
                            value="{{ old('amount', request('amount', 10000)) }}"
                            class="ml-2 flex-1 rounded border border-gray-300 px-3 py-2 focus:border-ink focus:outline-none"
                            placeholder="10000"
                            required
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimal pembayaran: Rp 1.000</p>
                @endif
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi (Opsional)
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-ink focus:outline-none"
                    placeholder="Masukkan deskripsi pembelian..."
                >{{ old('description', request('description')) }}</textarea>
            </div>

            <!-- Summary -->
            <div class="rounded bg-gray-50 p-4">
                <p class="text-sm text-gray-600">Jumlah yang akan dibayarkan:</p>
                <p class="mt-2 text-2xl font-bold text-ink">
                    Rp <span id="totalAmount">{{ number_format($item ? $item->price : old('amount', request('amount', 10000)), 0, ',', '.') }}</span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('dashboard') }}" class="flex-1 rounded border border-gray-300 px-4 py-2 text-center font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="flex-1 rounded bg-moss px-4 py-2 font-semibold text-white hover:bg-moss/90">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </div>

    <script>
        const amountInput = document.getElementById('amount');
        const totalAmountSpan = document.getElementById('totalAmount');

        function updateTotal() {
            if (!amountInput) {
                return;
            }

            const amount = parseInt(amountInput.value) || 0;
            totalAmountSpan.textContent = amount.toLocaleString('id-ID');
        }

        if (amountInput) {
            amountInput.addEventListener('change', updateTotal);
            amountInput.addEventListener('input', updateTotal);
        }

        // Initial update
        updateTotal();
    </script>
@endsection
