@extends('layouts.app')

@section('title', 'Laporan Stok Barang')
@section('actions')
<a href="{{ route('reports.stock.pdf') }}" class="rounded bg-coral px-4 py-2 text-sm font-semibold text-white">Export PDF</a>
@endsection

@section('content')
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Kode</th><th class="p-4">Barang</th><th class="p-4">Kategori</th><th class="p-4">Stok</th><th class="p-4">Minimum</th><th class="p-4">Status</th></tr></thead>
        <tbody>
        @foreach ($items as $item)
            <tr class="border-t"><td class="p-4">{{ $item->code }}</td><td class="p-4">{{ $item->name }}</td><td class="p-4">{{ $item->category->name }}</td><td class="p-4">{{ $item->stock }} {{ $item->unit }}</td><td class="p-4">{{ $item->minimum_stock }}</td><td class="p-4">{{ $item->stock_status }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
