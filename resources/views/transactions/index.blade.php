@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('actions')
<div class="flex gap-2"><a href="{{ route('transactions.create', 'in') }}" class="rounded bg-moss px-4 py-2 text-sm text-white">Masuk</a><a href="{{ route('transactions.create', 'out') }}" class="rounded bg-coral px-4 py-2 text-sm text-white">Keluar</a></div>
@endsection

@section('content')
<form class="mb-4 grid gap-2 md:grid-cols-4">
    <select name="type" class="rounded border-gray-300"><option value="">Semua</option><option value="in" @selected(request('type')==='in')>Masuk</option><option value="out" @selected(request('type')==='out')>Keluar</option></select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded border-gray-300">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded border-gray-300">
    <button class="rounded bg-ink px-4 py-2 text-white">Filter</button>
</form>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Tanggal</th><th class="p-4">Barang</th><th class="p-4">Jenis</th><th class="p-4">Jumlah</th><th class="p-4">Petugas</th><th class="p-4">Catatan</th></tr></thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr class="border-t"><td class="p-4">{{ $transaction->transaction_date->format('d M Y') }}</td><td class="p-4">{{ $transaction->item->name }}</td><td class="p-4">{{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}</td><td class="p-4">{{ $transaction->quantity }}</td><td class="p-4">{{ $transaction->user->name }}</td><td class="p-4">{{ $transaction->notes }}</td></tr>
        @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $transactions->links() }}</div>
</div>
@endsection
