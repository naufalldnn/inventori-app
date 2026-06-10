<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, int $conversationId) {
    return Conversation::query()
        ->whereKey($conversationId)
        ->where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
        })
        ->exists();
});
