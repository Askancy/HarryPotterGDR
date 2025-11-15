<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show messages inbox.
     */
    public function index()
    {
        return view('messages.index');
    }

    /**
     * Show specific conversation.
     */
    public function show($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is part of this conversation
        if ($conversation->user_one_id != Auth::id() && $conversation->user_two_id != Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        return view('messages.show', compact('conversation'));
    }

    /**
     * Send message.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $receiver = User::findOrFail($validated['receiver_id']);

        if (Auth::id() == $receiver->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi inviare messaggi a te stesso!',
            ], 400);
        }

        $message = Auth::user()->sendMessage($receiver, $validated['message']);

        return response()->json([
            'success' => true,
            'message' => 'Messaggio inviato!',
            'data' => $message,
        ]);
    }

    /**
     * Mark messages as read.
     */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $conversation = Conversation::findOrFail($validated['conversation_id']);

        // Check if user is part of this conversation
        if ($conversation->user_one_id != Auth::id() && $conversation->user_two_id != Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        \App\Models\PrivateMessage::where('receiver_id', Auth::id())
            ->where(function ($q) use ($conversation) {
                $q->where('sender_id', $conversation->user_one_id)
                  ->orWhere('sender_id', $conversation->user_two_id);
            })
            ->where('is_read', false)
            ->get()
            ->each->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }
}
