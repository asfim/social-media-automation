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

        $token = $comment->account->access_token ?? null;
        if (!$token) {
            Log::warning("Comment {$comment->id}: no access token, reply not sent.");
            return;
        }

        $result = $aiService->generateResponse($comment->comment_text, $comment->platform, 'comment', [], $comment->account);

        // Try public comment reply first
        $sent = $metaService->replyToComment($token, $comment->external_comment_id, $result['reply']);

        // Fallback: If public reply fails (e.g. permission restriction), send a private Messenger DM to the commenter
        if (!$sent['ok']) {
            Log::info("Public comment reply failed for comment {$comment->id}, trying private Messenger reply...");
            $privateSent = $metaService->sendPrivateReplyToComment($token, $comment->external_comment_id, "Hello! Regarding your comment: " . $result['reply']);
            if ($privateSent['ok']) {
                $sent = $privateSent;
            }
        }

        $comment->update([
            'ai_classification' => $result['intent'],
            'ai_reply_text' => $result['reply'],
            'reply_status' => 'replied',
        ]);

        if (!$sent['ok']) {
            Log::warning("Comment {$comment->id}: reply failed to send to Meta: " . $sent['error'] . ". Saved locally anyway.");
        }
    }
}
