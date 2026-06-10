@extends('layouts.app')

@section('title', 'Kategori')
@section('actions')
<a href="{{ route('categories.create') }}" class="rounded bg-moss px-4 py-2 text-sm font-semibold text-white">Tambah Kategori</a>
@endsection

@section('content')
<div class="overflow-hidden rounded-lg bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Nama</th><th class="p-4">Deskripsi</th><th class="p-4">Barang</th><th class="p-4"></th></tr></thead>
        <tbody>
        @foreach ($categories as $category)
            <tr class="border-t">
                <td class="p-4 font-semibold">{{ $category->name }}</td>
                <td class="p-4">{{ $category->description }}</td>
                <td class="p-4">{{ $category->items_count }}</td>
                <td class="p-4 text-right">
                    <a href="{{ route('categories.edit', $category) }}" class="text-moss">Edit</a>
                    <form method="post" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori?')">
                        @csrf @method('DELETE')
                        <button class="ml-3 text-coral">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $categories->links() }}</div>
</div>
@endsection
