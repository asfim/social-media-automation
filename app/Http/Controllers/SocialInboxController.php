<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Comment;
use App\Models\Lead;
use Illuminate\Support\Facades\Http;

class SocialInboxController extends Controller
{
    /**
     * Display the 3-pane Messenger Live Chat interface.
     */
    public function messenger(Request $request)
    {
        $conversations = Conversation::with('lastMessage')
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->paginate(30);

        // Selected conversation, or the most recent one by default
        $activeConversation = $request->filled('id')
            ? Conversation::findOrFail($request->id)
            : $conversations->first();

        $messages = collect();
        $lead = null;
        if ($activeConversation) {
            $messages = $activeConversation->messages()->oldest()->get();
            $activeConversation->messages()
                ->where('sender_type', 'customer')->where('is_read', false)
                ->update(['is_read' => true]);
            $lead = Lead::where('profile_id', $activeConversation->customer_id)->first();
        }

        return view('inbox.messenger', compact('conversations', 'activeConversation', 'messages', 'lead'));
    }

    /**
     * Display the conversations historical log.
     */
    public function conversations()
    {
        $conversations = Conversation::with('lastMessage')
            ->latest()
            ->paginate(20);

        return view('inbox.conversations', compact('conversations'));
    }

    /**
     * Display the Social Comments tracker with Post Grouping & Filters.
     */
    public function comments(Request $request)
    {
        $posts = Comment::select('external_post_id', \DB::raw('count(*) as comments_count'), \DB::raw('max(created_at) as latest_comment_at'))
            ->whereNotNull('external_post_id')
            ->where('external_post_id', '!=', '')
            ->groupBy('external_post_id')
            ->orderByDesc('latest_comment_at')
            ->get();

        $selectedPostId = $request->get('post_id');

        $query = Comment::latest();

        if ($selectedPostId) {
            $query->where('external_post_id', $selectedPostId);
        }

        if ($request->filled('platform') && $request->platform !== 'all') {
            $query->where('platform', $request->platform);
        }

        $comments = $query->paginate(20)->withQueryString();

        return view('inbox.comments', compact('comments', 'posts', 'selectedPostId'));
    }

    /**
     * API to toggle AI on/off for a conversation (Human Handover)
     */
    public function toggleAi(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        
        $conversation->ai_active = !$conversation->ai_active;
        $conversation->status = $conversation->ai_active ? 'open' : 'human_review';
        $conversation->save();

        return response()->json([
            'success' => true, 
            'ai_active' => (bool) $conversation->ai_active,
            'message' => $conversation->ai_active ? 'AI is now handling this chat.' : 'Human took over this chat.'
        ]);
    }

    /**
     * Send an admin reply: stored locally and delivered via Meta Graph API when possible.
     */
    public function sendMessage(Request $request, $id)
    {
        $data = $request->validate(['message' => 'required|string|max:2000']);
        $conversation = Conversation::with('account')->findOrFail($id);

        $delivered = false;
        $error = null;
        $token = $conversation->account->access_token ?? null;

        if ($token && in_array($conversation->platform, ['facebook', 'instagram'])) {
            $response = Http::withToken($token)->post('https://graph.facebook.com/v19.0/me/messages', [
                'recipient' => ['id' => $conversation->customer_id],
                'message' => ['text' => $data['message']],
                'messaging_type' => 'RESPONSE',
            ]);
            $delivered = $response->successful();
            $error = $delivered ? null : ($response->json('error.message') ?? 'Meta API error');
        } else {
            $error = 'No connected account token; message saved locally only.';
        }

        // Admin takes over: stop AI replies on this chat
        $message = $conversation->messages()->create([
            'external_message_id' => 'admin_' . uniqid(),
            'message_text' => $data['message'],
            'sender_type' => 'admin',
            'is_read' => true,
        ]);
        $conversation->update(['last_message_at' => now(), 'ai_active' => false, 'status' => 'human_review']);

        return response()->json([
            'success' => true,
            'delivered' => $delivered,
            'warning' => $error,
            'time' => $message->created_at->format('h:i A'),
        ]);
    /**
     * Simulate a customer commenting on a Facebook post for testing.
     */
    public function simulateComment(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string',
            'comment_text' => 'required|string',
            'external_post_id' => 'nullable|string'
        ]);

        $account = \App\Models\SocialAccount::where('platform', 'facebook')->where('is_active', true)->latest()->first();

        $comment = Comment::create([
            'social_account_id' => $account->id ?? 1,
            'platform' => 'facebook',
            'external_comment_id' => 'c_' . uniqid(),
            'external_post_id' => $validated['external_post_id'] ?? 'post_1001',
            'customer_name' => $validated['customer_name'] ?? 'Test Commenter',
            'customer_id' => 'cust_' . rand(100, 999),
            'comment_text' => $validated['comment_text'],
            'reply_status' => 'pending',
        ]);

        \App\Jobs\GenerateCommentReply::dispatchSync($comment->id);

        $comment->refresh();

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'ai_reply' => $comment->ai_reply_text
        ]);
    }
}
