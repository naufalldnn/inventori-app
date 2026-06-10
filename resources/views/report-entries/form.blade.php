@extends('layouts.app')

@section('title', $report->exists ? 'Edit Catatan Laporan' : 'Tambah Catatan Laporan')

@section('content')
<form method="post" action="{{ $report->exists ? route('report-entries.update', $report) : route('report-entries.store') }}" class="max-w-3xl rounded-lg bg-white p-6 shadow-sm">
    @csrf
    @if ($report->exists)
        @method('put')
    @endif

    <label class="mb-4 block text-sm font-medium">Judul Laporan
        <input name="title" value="{{ old('title', $report->title) }}" required maxlength="150" class="mt-1 w-full rounded border-gray-300">
    </label>

    <div class="mb-4 grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-medium">Jenis
            <select name="type" required class="mt-1 w-full rounded border-gray-300">
                <option value="stock" @selected(old('type', $report->type) === 'stock')>Stok</option>
                <option value="transaction" @selected(old('type', $report->type) === 'transaction')>Transaksi</option>
                <option value="incident" @selected(old('type', $report->type) === 'incident')>Kendala</option>
                <option value="other" @selected(old('type', $report->type) === 'other')>Lainnya</option>
            </select>
        </label>
        <label class="block text-sm font-medium">Status
            <select name="status" required class="mt-1 w-full rounded border-gray-300">
                <option value="draft" @selected(old('status', $report->status) === 'draft')>Draft</option>
                <option value="submitted" @selected(old('status', $report->status) === 'submitted')>Diajukan</option>
                <option value="reviewed" @selected(old('status', $report->status) === 'reviewed')>Ditinjau</option>
            </select>
        </label>
    </div>

    <div class="mb-4 grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-medium">Periode Mulai
            <input name="period_from" type="date" value="{{ old('period_from', $report->period_from?->format('Y-m-d')) }}" class="mt-1 w-full rounded border-gray-300">
        </label>
        <label class="block text-sm font-medium">Periode Selesai
            <input name="period_to" type="date" value="{{ old('period_to', $report->period_to?->format('Y-m-d')) }}" class="mt-1 w-full rounded border-gray-300">
        </label>
    </div>

    <label class="mb-6 block text-sm font-medium">Isi Catatan
        <textarea name="summary" rows="8" required class="mt-1 w-full rounded border-gray-300">{{ old('summary', $report->summary) }}</textarea>
    </label>

    <div class="flex flex-wrap gap-2">
        <button class="btn-primary">Simpan</button>
        <a href="{{ $report->exists ? route('report-entries.show', $report) : route('report-entries.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
