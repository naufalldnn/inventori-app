@extends('layouts.app')

@section('title', 'Dashboard')
@section('actions')
<div class="flex gap-2">
    <a href="{{ route('transactions.create', 'in') }}" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white">Barang Masuk</a>
    <a href="{{ route('transactions.create', 'out') }}" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Barang Keluar</a>
</div>
@endsection

@section('content')
<div class="grid gap-4 md:grid-cols-4">
    @foreach ([['Barang', $itemCount], ['Kategori', $categoryCount], ['Stok Menipis', $lowStockCount], ['Stok Habis', $emptyStockCount]] as [$label, $value])
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
        </div>
    @endforeach
</div>
<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <section class="rounded-lg bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-bold">Transaksi Terbaru</h2>
        <div class="space-y-3">
            @forelse ($recentTransactions as $transaction)
                <div class="flex justify-between border-b pb-3 text-sm">
                    <span>{{ $transaction->item->name }} oleh {{ $transaction->user->name }}</span>
                    <strong class="{{ $transaction->type === 'in' ? 'text-moss' : 'text-coral' }}">{{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}</strong>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada transaksi.</p>
            @endforelse
        </div>
    </section>
    <section class="rounded-lg bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-bold">Stok Perlu Perhatian</h2>
        <div class="space-y-3">
            @forelse ($lowItems as $item)
                <div class="flex justify-between border-b pb-3 text-sm">
                    <span>{{ $item->name }} <small class="text-gray-500">({{ $item->category->name }})</small></span>
                    <strong class="{{ $item->stock <= 0 ? 'text-coral' : 'text-amber' }}">{{ $item->stock }} {{ $item->unit }}</strong>
                </div>
            @empty
                <p class="text-sm text-gray-500">Semua stok aman.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
