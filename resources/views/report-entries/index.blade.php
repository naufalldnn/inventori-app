@extends('layouts.app')

@section('title', 'Catatan Laporan')
@section('actions')
<a href="{{ route('report-entries.create') }}" class="btn-primary">Tambah Catatan</a>
@endsection

@section('content')
<form class="mb-4 grid gap-2 rounded-lg border border-line bg-white p-3 shadow-sm md:grid-cols-5">
    <select name="type" class="rounded-md">
        <option value="">Semua Jenis</option>
        <option value="stock" @selected(request('type') === 'stock')>Stok</option>
        <option value="transaction" @selected(request('type') === 'transaction')>Transaksi</option>
        <option value="incident" @selected(request('type') === 'incident')>Kendala</option>
        <option value="other" @selected(request('type') === 'other')>Lainnya</option>
    </select>
    <select name="status" class="rounded-md">
        <option value="">Semua Status</option>
        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        <option value="submitted" @selected(request('status') === 'submitted')>Diajukan</option>
        <option value="reviewed" @selected(request('status') === 'reviewed')>Ditinjau</option>
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-md">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-md">
    <button class="btn-primary">Filter</button>
</form>

<div class="table-shell">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left text-sm">
            <thead class="border-b border-line bg-cloud text-xs font-black uppercase tracking-wide text-slate-500">
                <tr><th class="p-4">Tanggal</th><th class="p-4">Judul</th><th class="p-4">Jenis</th><th class="p-4">Status</th><th class="p-4">Periode</th><th class="p-4">Petugas</th><th class="p-4"></th></tr>
            </thead>
            <tbody class="divide-y divide-line">
            @forelse ($reports as $report)
                <tr class="transition hover:bg-blue-50/40">
                    <td class="p-4 font-medium text-slate-600">{{ $report->created_at->format('d M Y') }}</td>
                    <td class="p-4 font-black">{{ $report->title }}</td>
                    <td class="p-4">{{ $report->type_label }}</td>
                    <td class="p-4"><span class="status-pill bg-blue-50 text-ocean">{{ $report->status_label }}</span></td>
                    <td class="p-4 text-slate-600">
                        {{ $report->period_from?->format('d M Y') ?? '-' }} - {{ $report->period_to?->format('d M Y') ?? '-' }}
                    </td>
                    <td class="p-4 text-slate-600">{{ $report->user->name }}</td>
                    <td class="p-4 text-right"><a href="{{ route('report-entries.show', $report) }}" class="font-bold text-ocean">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="p-6 text-center text-slate-500">Belum ada catatan laporan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $reports->links() }}</div>
</div>
@endsection
