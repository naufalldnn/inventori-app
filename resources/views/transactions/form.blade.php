@extends('layouts.app')

@section('title', $type === 'in' ? 'Barang Masuk' : 'Barang Keluar')

@section('content')
<form method="post" action="{{ route('transactions.store') }}" class="max-w-2xl rounded-lg bg-white p-6 shadow-sm">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <label class="mb-4 block text-sm font-medium">Barang
        <select name="item_id" required class="mt-1 w-full rounded border-gray-300">
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} (stok {{ $item->stock }})</option>
            @endforeach
        </select>
    </label>
    <label class="mb-4 block text-sm font-medium">Jumlah
        <input name="quantity" type="number" min="1" required class="mt-1 w-full rounded border-gray-300">
    </label>
    <label class="mb-4 block text-sm font-medium">Tanggal
        <input name="transaction_date" type="date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded border-gray-300">
    </label>
    <label class="mb-6 block text-sm font-medium">Catatan
        <textarea name="notes" rows="3" class="mt-1 w-full rounded border-gray-300"></textarea>
    </label>
    <button class="rounded {{ $type === 'in' ? 'bg-moss' : 'bg-coral' }} px-4 py-2 font-semibold text-white">Simpan Transaksi</button>
</form>
@endsection
