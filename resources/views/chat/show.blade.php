@extends('layouts.app')

@section('title', 'Chat dengan '.$otherUser->name)

@section('content')
<div class="rounded-lg bg-white p-4 shadow-sm">
    <div id="messages" class="mb-4 grid max-h-[60vh] gap-3 overflow-y-auto">
        @foreach ($conversation->messages as $message)
            <div class="rounded p-3 {{ $message->user_id === auth()->id() ? 'ml-auto bg-moss text-white' : 'mr-auto bg-gray-100' }}">
                <p class="text-sm">{{ $message->body }}</p>
                <small class="opacity-75">{{ $message->user->name }} - {{ $message->created_at->format('d M Y H:i') }}</small>
            </div>
        @endforeach
    </div>
    <form method="post" action="{{ route('chat.store', $conversation) }}" class="flex gap-2">
        @csrf
        <input name="body" autocomplete="off" required placeholder="Tulis pesan" class="w-full rounded border-gray-300">
        <button class="rounded bg-moss px-4 py-2 font-semibold text-white">Kirim</button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('messages');
    box.scrollTop = box.scrollHeight;
    if (window.Echo) {
        window.Echo.private('conversation.{{ $conversation->id }}')
            .listen('MessageSent', (event) => {
                const mine = event.user.id === {{ auth()->id() }};
                const bubble = document.createElement('div');
                bubble.className = 'rounded p-3 ' + (mine ? 'ml-auto bg-moss text-white' : 'mr-auto bg-gray-100');
                bubble.innerHTML = `<p class="text-sm">${event.body}</p><small class="opacity-75">${event.user.name} - ${event.created_at}</small>`;
                box.appendChild(bubble);
                box.scrollTop = box.scrollHeight;
            });
    }
});
</script>
@endsection
