@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-3">
    @forelse ($notifications as $notification)
        <div class="rounded-lg bg-white p-4 shadow-sm {{ $notification->read_at ? 'opacity-70' : '' }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="rounded px-2 py-1 text-xs {{ $notification->type === 'danger' ? 'bg-coral/10 text-coral' : ($notification->type === 'warning' ? 'bg-amber/10 text-amber' : 'bg-moss/10 text-moss') }}">{{ $notification->type }}</span>
                    <h2 class="mt-2 font-bold">{{ $notification->title }}</h2>
                    <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->format('d M Y H:i') }}</p>
                </div>
                @unless ($notification->read_at)
                    <form method="post" action="{{ route('notifications.read', $notification) }}">
                        @csrf
                        <button class="rounded bg-ink px-3 py-2 text-sm text-white">Tandai Dibaca</button>
                    </form>
                @endunless
            </div>
        </div>
    @empty
        <p class="rounded-lg bg-white p-5 text-sm text-gray-500 shadow-sm">Belum ada notifikasi.</p>
    @endforelse
    {{ $notifications->links() }}
</div>
@endsection
