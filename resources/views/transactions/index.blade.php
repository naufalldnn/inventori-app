@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('actions')
<div class="flex gap-2"><a href="{{ route('transactions.create', 'in') }}" class="btn-primary bg-moss hover:bg-teal-700">Masuk</a><a href="{{ route('transactions.create', 'out') }}" class="btn-primary bg-coral hover:bg-rose-700">Keluar</a></div>
@endsection

@section('content')
<form class="mb-4 grid gap-2 rounded-lg border border-line bg-white p-3 shadow-sm md:grid-cols-4">
    <select name="type" class="rounded-md"><option value="">Semua</option><option value="in" @selected(request('type')==='in')>Masuk</option><option value="out" @selected(request('type')==='out')>Keluar</option></select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-md">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-md">
    <button class="btn-primary">Filter</button>
</form>
<div class="table-shell">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[860px] text-left text-sm">
        <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500"><tr><th class="p-4">Tanggal</th><th class="p-4">Barang</th><th class="p-4">Jenis</th><th class="p-4">Jumlah</th><th class="p-4">Petugas</th><th class="p-4">Catatan</th></tr></thead>
        <tbody class="divide-y divide-line">
        @foreach ($transactions as $transaction)
            <tr class="transition hover:bg-blue-50/40"><td class="p-4 font-medium text-slate-600">{{ $transaction->transaction_date->format('d M Y') }}</td><td class="p-4 font-black">{{ $transaction->item->name }}</td><td class="p-4"><span class="status-pill {{ $transaction->type === 'in' ? 'bg-teal-50 text-moss' : 'bg-rose-50 text-coral' }}">{{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td><td class="p-4 font-bold">{{ $transaction->quantity }}</td><td class="p-4 text-slate-600">{{ $transaction->user->name }}</td><td class="p-4 text-slate-600">{{ $transaction->notes }}</td></tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div class="p-4">{{ $transactions->links() }}</div>
</div>
@endsection
