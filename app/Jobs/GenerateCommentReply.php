<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;
use App\Services\AI\AIService;
use App\Services\Meta\MetaService;

class GenerateCommentReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $commentId;

    public function __construct($commentId)
    {
        $this->commentId = $commentId;
    }

    public function handle(AIService $aiService, MetaService $metaService): void
    {
        $comment = Comment::with('account')->find($this->commentId);

        if (!$comment || $comment->reply_status !== 'pending') {
            return;
        }

        $account = $comment->account;
        if (!$account) {
            $account = \App\Models\SocialAccount::where('platform', 'facebook')->where('is_active', true)->latest()->first();
        }

        $token = $account->access_token ?? null;

        $result = $aiService->generateResponse($comment->comment_text, $comment->platform ?? 'facebook', 'comment', [], $account);

        // Convert [PRODUCT_CARD:id] tags to readable plain text for public Facebook comments
        $replyText = $result['reply'];
        $replyText = preg_replace('/\[PRODUCT_CARD:(\d+)\]/', '', $replyText);
        $replyText = trim(preg_replace('/\n{3,}/', "\n\n", $replyText));

        $sent = ['ok' => false, 'error' => 'No access token'];

        if ($token) {
            // Try public comment reply first
            $sent = $metaService->replyToComment($token, $comment->external_comment_id, $replyText);

            // Fallback: If public reply fails (e.g. permission restriction), try sending a private Messenger DM
            if (!$sent['ok']) {
                Log::info("Public comment reply failed for comment {$comment->id}, trying private Messenger reply...");
                $privateSent = $metaService->sendPrivateReplyToComment($token, $comment->external_comment_id, $replyText);
                if ($privateSent['ok']) {
                    $sent = $privateSent;
                }
            }
        }

        $comment->update([
            'ai_classification' => $result['intent'] ?? 'auto_reply',
            'ai_reply_text' => $replyText,
            'reply_status' => 'replied',
        ]);

        if (!$sent['ok']) {
            Log::warning("Comment {$comment->id}: reply failed to send to Meta: " . ($sent['error'] ?? 'unknown error') . ". Saved locally anyway.");
        }
    }
}
