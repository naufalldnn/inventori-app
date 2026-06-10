@extends('layouts.app')

@section('title', 'Detail Catatan Laporan')
@section('actions')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('report-entries.edit', $report) }}" class="btn-secondary">Edit</a>
    <a href="{{ route('report-entries.index') }}" class="btn-primary">Daftar Laporan</a>
</div>
@endsection

@section('content')
<div class="max-w-4xl rounded-lg bg-white p-6 shadow-sm">
    <div class="mb-5 flex flex-wrap items-start justify-between gap-3 border-b border-line pb-5">
        <div>
            <p class="text-sm font-semibold text-slate-500">{{ $report->created_at->format('d M Y H:i') }}</p>
            <h2 class="mt-1 text-2xl font-black text-ink">{{ $report->title }}</h2>
        </div>
        <span class="status-pill bg-blue-50 text-ocean">{{ $report->status_label }}</span>
    </div>

    <dl class="mb-6 grid gap-4 text-sm md:grid-cols-3">
        <div><dt class="font-bold text-slate-500">Jenis</dt><dd class="mt-1 text-ink">{{ $report->type_label }}</dd></div>
        <div><dt class="font-bold text-slate-500">Periode</dt><dd class="mt-1 text-ink">{{ $report->period_from?->format('d M Y') ?? '-' }} - {{ $report->period_to?->format('d M Y') ?? '-' }}</dd></div>
        <div><dt class="font-bold text-slate-500">Dicatat oleh</dt><dd class="mt-1 text-ink">{{ $report->user->name }}</dd></div>
    </dl>

    <div class="prose max-w-none whitespace-pre-line text-slate-700">{{ $report->summary }}</div>

    <form method="post" action="{{ route('report-entries.destroy', $report) }}" class="mt-8 border-t border-line pt-5" onsubmit="return confirm('Hapus catatan laporan ini?')">
        @csrf
        @method('delete')
        <button class="rounded-md border border-rose-100 bg-rose-50 px-4 py-2 text-sm font-bold text-coral transition hover:bg-rose-100">Hapus Catatan</button>
    </form>
</div>
@endsection
