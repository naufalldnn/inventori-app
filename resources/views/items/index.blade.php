@extends('layouts.app')

@section('title', 'Barang')
@section('actions')
<a href="{{ route('items.create') }}" class="btn-primary">Tambah Barang</a>
@endsection

@section('content')
<form class="mb-4 flex flex-col gap-2 rounded-lg border border-line bg-white p-3 shadow-sm sm:flex-row">
    <input name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang" class="w-full rounded-md">
    <button class="btn-primary sm:w-28">Cari</button>
</form>

<div class="table-shell">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="p-4">Media</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
            @foreach ($items as $item)
                <tr class="transition hover:bg-blue-50/40">
                    <td class="p-4">
                        @if ($item->media_url)
                            @if ($item->media_type === 'video')
                                <video src="{{ $item->media_url }}" class="h-14 w-20 rounded-md object-cover" muted></video>
                            @else
                                <img src="{{ $item->media_url }}" alt="{{ $item->name }}" class="h-14 w-20 rounded-md object-cover">
                            @endif
                        @else
                            <span class="grid h-14 w-20 place-items-center rounded-md bg-slate-100 text-xs font-bold text-slate-400">No img</span>
                        @endif
                    </td>
                    <td class="p-4 font-mono text-xs font-bold text-slate-600">{{ $item->code }}</td>
                    <td class="p-4 font-black">{{ $item->name }}</td>
                    <td class="p-4 text-slate-600">{{ $item->category->name }}</td>
                    <td class="p-4 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $item->stock }} {{ $item->unit }}</td>
                    <td class="p-4">
                        <span class="status-pill {{ $item->stock_status === 'aman' ? 'bg-teal-50 text-moss' : ($item->stock_status === 'menipis' ? 'bg-amber-50 text-amber' : 'bg-rose-50 text-coral') }}">{{ $item->stock_status }}</span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('items.edit', $item) }}" class="font-bold text-ocean hover:underline">Edit</a>
                        <form method="post" action="{{ route('items.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus barang?')">
                            @csrf @method('DELETE')
                            <button class="ml-3 font-bold text-coral hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="border-t border-line p-4">{{ $items->links() }}</div>
</div>
@endsection
