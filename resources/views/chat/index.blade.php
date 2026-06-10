@extends('layouts.app')

@section('title', 'Chat Internal')

@section('content')
<form method="post" action="{{ route('chat.start') }}" class="mb-6 flex gap-2 rounded-lg bg-white p-4 shadow-sm">
    @csrf
    <select name="user_id" class="w-full rounded border-gray-300">
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->role }}</option>
        @endforeach
    </select>
    <button class="rounded bg-moss px-4 py-2 font-semibold text-white">Mulai</button>
</form>
<div class="grid gap-3">
    @forelse ($conversations as $conversation)
        <a href="{{ route('chat.show', $conversation) }}" class="rounded-lg bg-white p-4 shadow-sm hover:ring-2 hover:ring-moss/20">
            <strong>{{ $conversation->otherUser(auth()->user())->name }}</strong>
            <p class="mt-1 text-sm text-gray-500">{{ optional($conversation->messages->first())->body ?? 'Belum ada pesan.' }}</p>
        </a>
    @empty
        <p class="rounded-lg bg-white p-5 text-sm text-gray-500 shadow-sm">Belum ada percakapan.</p>
    @endforelse
</div>
@endsection
