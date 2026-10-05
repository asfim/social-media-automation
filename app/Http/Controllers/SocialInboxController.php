<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Comment;

class SocialInboxController extends Controller
{
    /**
     * Display the 3-pane Messenger Live Chat interface.
     */
    public function messenger(Request $request)
    {
        // Fetch conversations ordered by the latest message
        $conversations = Conversation::with(['customer', 'lastMessage'])->latest('updated_at')->paginate(15);
        
        // If a specific conversation is selected, load it
        $activeConversation = null;
        if ($request->has('id')) {
            $activeConversation = Conversation::with(['messages', 'customer', 'lead'])->findOrFail($request->id);
        }

        return view('inbox.messenger', compact('conversations', 'activeConversation'));
    }

    /**
     * Display the conversations historical log.
     */
    public function conversations()
    {
        $conversations = Conversation::with(['customer', 'lastMessage'])
            ->latest()
            ->paginate(20);

        return view('inbox.conversations', compact('conversations'));
    }

    /**
     * Display the Social Comments tracker.
     */
    public function comments()
    {
        $comments = Comment::with('post')
            ->latest()
            ->paginate(20);

        return view('inbox.comments', compact('comments'));
    }

    /**
     * API to toggle AI on/off for a conversation (Human Handover)
     */
    public function toggleAi(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        
        // Toggle the flag
        $conversation->ai_enabled = !$conversation->ai_enabled;
        if (!$conversation->ai_enabled) {
            $conversation->status = 'Human Review';
        } else {
            $conversation->status = 'AI Resolved';
        }
        $conversation->save();

        return response()->json([
            'success' => true, 
            'ai_enabled' => $conversation->ai_enabled,
            'message' => $conversation->ai_enabled ? 'AI is now handling this chat.' : 'Human took over this chat.'
        ]);
    }
}
