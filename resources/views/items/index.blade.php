@extends('layouts.app')

@section('title', 'Barang')
@section('actions')
<a href="{{ route('items.create') }}" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white">Tambah Barang</a>
@endsection

@section('content')
<form class="mb-4 flex gap-2">
    <input name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang" class="w-full rounded border-gray-300">
    <button class="rounded bg-ink px-4 py-2 text-white">Cari</button>
</form>
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Media</th><th class="p-4">Kode</th><th class="p-4">Nama</th><th class="p-4">Kategori</th><th class="p-4">Harga</th><th class="p-4">Stok</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead>
        <tbody>
        @foreach ($items as $item)
            <tr class="border-t">
                <td class="p-4">
                    @if ($item->media_url)
                        @if ($item->media_type === 'video')
                            <video src="{{ $item->media_url }}" class="h-14 w-20 rounded object-cover" muted></video>
                        @else
                            <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-14 w-20 rounded object-cover">
                        @endif
                    @else
                        <span class="block h-14 w-20 rounded bg-gray-100"></span>
                    @endif
                </td>
                <td class="p-4 font-semibold">{{ $item->code }}</td>
                <td class="p-4">{{ $item->name }}</td>
                <td class="p-4">{{ $item->category->name }}</td>
                <td class="p-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="p-4">{{ $item->stock }} {{ $item->unit }}</td>
                <td class="p-4"><span class="rounded px-2 py-1 text-xs {{ $item->stock_status === 'aman' ? 'bg-moss/10 text-moss' : ($item->stock_status === 'menipis' ? 'bg-amber/10 text-amber' : 'bg-coral/10 text-coral') }}">{{ $item->stock_status }}</span></td>
                <td class="p-4 text-right">
                    <a href="{{ route('items.edit', $item) }}" class="text-moss">Edit</a>
                    <form method="post" action="{{ route('items.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus barang?')">
                        @csrf @method('DELETE')
                        <button class="ml-3 text-coral">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $items->links() }}</div>
</div>
@endsection
