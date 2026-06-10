@extends('layouts.app')

@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<form method="post" action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}" class="max-w-2xl rounded-lg bg-white p-6 shadow-sm">
    @csrf
    @if ($category->exists) @method('PUT') @endif
    <label class="mb-4 block text-sm font-medium">Nama
        <input name="name" value="{{ old('name', $category->name) }}" required class="mt-1 w-full rounded border-gray-300">
    </label>
    <label class="mb-6 block text-sm font-medium">Deskripsi
        <textarea name="description" rows="4" class="mt-1 w-full rounded border-gray-300">{{ old('description', $category->description) }}</textarea>
    </label>
    <button class="rounded bg-moss px-4 py-2 font-semibold text-white">Simpan</button>
</form>
@endsection
