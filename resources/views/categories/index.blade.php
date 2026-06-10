@extends('layouts.app')

@section('title', 'Kategori')
@section('actions')
<a href="{{ route('categories.create') }}" class="btn-primary">Tambah Kategori</a>
@endsection

@section('content')
<div class="table-shell">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500"><tr><th class="p-4">Nama</th><th class="p-4">Deskripsi</th><th class="p-4">Barang</th><th class="p-4"></th></tr></thead>
        <tbody class="divide-y divide-line">
        @foreach ($categories as $category)
            <tr class="transition hover:bg-blue-50/40">
                <td class="p-4 font-black">{{ $category->name }}</td>
                <td class="p-4 text-slate-600">{{ $category->description }}</td>
                <td class="p-4"><span class="status-pill bg-blue-50 text-ocean">{{ $category->items_count }}</span></td>
                <td class="p-4 text-right">
                    <a href="{{ route('categories.edit', $category) }}" class="font-bold text-ocean hover:underline">Edit</a>
                    <form method="post" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori?')">
                        @csrf @method('DELETE')
                        <button class="ml-3 font-bold text-coral hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $categories->links() }}</div>
</div>
@endsection
