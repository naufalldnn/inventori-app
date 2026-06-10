@extends('layouts.app')

@section('title', 'Dashboard')
@section('actions')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('transactions.create', 'in') }}" class="btn-primary bg-moss hover:bg-teal-700">Barang Masuk</a>
    <a href="{{ route('transactions.create', 'out') }}" class="btn-primary bg-coral hover:bg-rose-700">Barang Keluar</a>
</div>
@endsection

@section('content')
<div class="grid gap-4 md:grid-cols-4">
    @foreach ([['Barang', $itemCount, 'text-ocean', 'bg-blue-50'], ['Kategori', $categoryCount, 'text-moss', 'bg-teal-50'], ['Stok Menipis', $lowStockCount, 'text-amber', 'bg-amber-50'], ['Stok Habis', $emptyStockCount, 'text-coral', 'bg-rose-50']] as [$label, $value, $tone, $bg])
        <div class="surface p-5">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-bold text-slate-500">{{ $label }}</p>
                <span class="h-3 w-3 rounded-full {{ $bg }}"></span>
            </div>
            <p class="mt-3 text-3xl font-black tracking-tight {{ $tone }}">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
    <section class="surface p-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-black uppercase tracking-wide text-ocean">Aktivitas</p>
                <h2 class="font-black">Transaksi Terbaru</h2>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-ocean hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-line">
            @forelse ($recentTransactions as $transaction)
                <div class="flex items-center justify-between gap-4 py-3 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-bold">{{ $transaction->item->name }}</p>
                        <p class="text-xs text-slate-500">oleh {{ $transaction->user->name }}</p>
                    </div>
                    <strong class="rounded-full px-3 py-1 text-sm {{ $transaction->type === 'in' ? 'bg-teal-50 text-moss' : 'bg-rose-50 text-coral' }}">
                        {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}
                    </strong>
                </div>
            @empty
                <p class="rounded-lg border border-dashed border-line bg-cloud p-6 text-center text-sm text-slate-500">Belum ada transaksi.</p>
            @endforelse
        </div>
    </section>

    <section class="surface p-5">
        <div class="mb-4">
            <p class="text-xs font-black uppercase tracking-wide text-coral">Perlu dicek</p>
            <h2 class="font-black">Stok Perlu Perhatian</h2>
        </div>
        <div class="divide-y divide-line">
            @forelse ($lowItems as $item)
                <div class="flex items-center justify-between gap-4 py-3 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-bold">{{ $item->name }}</p>
                        <p class="text-xs text-slate-500">{{ $item->category->name }}</p>
                    </div>
                    <strong class="rounded-full px-3 py-1 {{ $item->stock <= 0 ? 'bg-rose-50 text-coral' : 'bg-amber-50 text-amber' }}">{{ $item->stock }} {{ $item->unit }}</strong>
                </div>
            @empty
                <p class="rounded-lg border border-dashed border-line bg-cloud p-6 text-center text-sm text-slate-500">Semua stok aman.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
