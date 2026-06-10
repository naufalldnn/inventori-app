<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $conversations = Conversation::with(['userOne', 'userTwo', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->where(fn ($query) => $query->where('user_one_id', $request->user()->id)->orWhere('user_two_id', $request->user()->id))
            ->latest()
            ->get();

        return view('chat.index', [
            'conversations' => $conversations,
            'users' => User::where('id', '!=', $request->user()->id)->orderBy('name')->get(),
        ]);
    }

    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate(['user_id' => ['required', 'exists:users,id']]);
        $ids = [auth()->id(), (int) $data['user_id']];
        sort($ids);

        $conversation = Conversation::firstOrCreate(['user_one_id' => $ids[0], 'user_two_id' => $ids[1]]);

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeConversation($request, $conversation);

        return view('chat.show', [
            'conversation' => $conversation->load(['userOne', 'userTwo', 'messages.user']),
            'otherUser' => $conversation->otherUser($request->user()),
        ]);
    }

    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversation($request, $conversation);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return back();
    }

    private function authorizeConversation(Request $request, Conversation $conversation): void
    {
        abort_unless(in_array($request->user()->id, [$conversation->user_one_id, $conversation->user_two_id], true), 403);
    }
}
