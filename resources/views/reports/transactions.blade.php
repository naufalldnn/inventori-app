@extends('layouts.app')

@section('title', $type === 'in' ? 'Laporan Barang Masuk' : 'Laporan Barang Keluar')
@section('actions')
<a href="{{ route('reports.transactions.pdf', ['type' => $type] + request()->only('date_from', 'date_to')) }}" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Export PDF</a>
@endsection

@section('content')
<form class="mb-4 grid gap-2 md:grid-cols-3">
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded border-gray-300">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded border-gray-300">
    <button class="rounded bg-ink px-4 py-2 text-white">Filter Tanggal</button>
</form>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Tanggal</th><th class="p-4">Barang</th><th class="p-4">Kategori</th><th class="p-4">Jumlah</th><th class="p-4">Petugas</th></tr></thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr class="border-t"><td class="p-4">{{ $transaction->transaction_date->format('d M Y') }}</td><td class="p-4">{{ $transaction->item->name }}</td><td class="p-4">{{ $transaction->item->category->name }}</td><td class="p-4">{{ $transaction->quantity }}</td><td class="p-4">{{ $transaction->user->name }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
