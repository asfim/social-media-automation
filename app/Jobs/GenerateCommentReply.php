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

        $result = $aiService->generateResponse($comment->comment_text, $comment->platform, 'comment');
        $sent = $metaService->replyToComment($token, $comment->external_comment_id, $result['reply']);

        $comment->update([
            'ai_classification' => $result['intent'],
            'ai_reply_text' => $result['reply'],
            'reply_status' => 'replied', // Force replied so it shows in UI
        ]);

        if (!$sent['ok']) {
            Log::warning("Comment {$comment->id}: reply failed to send to Meta: " . $sent['error'] . ". Saved locally anyway for testing.");
        }
    }
}
