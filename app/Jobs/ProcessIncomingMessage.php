<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\SocialAccount;
use App\Services\Meta\MetaService;

class ProcessIncomingMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;
    protected $platform;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload, string $platform = 'facebook')
    {
        $this->payload = $payload;
        $this->platform = $platform;
    }

    /**
     * Execute the job.
     */
    public function handle(MetaService $metaService): void
    {
        Log::info("Processing incoming {$this->platform} event.", ['payload' => $this->payload]);

        if ($this->platform !== 'facebook') {
            Log::info("Auto-reply for {$this->platform} is not implemented yet; event ignored.");
            return;
        }

        foreach ($this->payload['entry'] ?? [] as $entry) {
            $pageId = (string) ($entry['id'] ?? '');

            $account = SocialAccount::where('platform', 'facebook')
                ->where('account_id', $pageId)
                ->where('is_active', true)
                ->first();

            if (!$account) {
                Log::warning("No connected Facebook page for id {$pageId}. Connect it in Settings > Facebook.");
                continue;
            }

            foreach ($entry['messaging'] ?? [] as $event) {
                $this->handleMessage($account, $event, $metaService);
            }

            foreach ($entry['changes'] ?? [] as $change) {
                $this->handleChange($account, $change);
            }
        }
    }

    /**
     * Messenger: save the customer message and trigger an AI reply.
     */
    protected function handleMessage(SocialAccount $account, array $event, MetaService $meta): void
    {
        $text = $event['message']['text'] ?? null;

        // Ignore delivery receipts, attachments-only messages and our own echoes
        if ($text === null || ($event['message']['is_echo'] ?? false)) {
            return;
        }

        $psid = (string) ($event['sender']['id'] ?? '');
        if ($psid === '' || $psid === $account->account_id) {
            return;
        }

        $mid = $event['message']['mid'] ?? ('m_' . uniqid());
        if (ConversationMessage::where('external_message_id', $mid)->exists()) {
            return; // Meta retried delivery
        }

        $externalId = "{$account->account_id}_{$psid}";
        $conversation = Conversation::where('external_conversation_id', $externalId)->first();

        if (!$conversation) {
            $profile = $account->access_token ? $meta->fetchProfile($account->access_token, $psid) : [];
            $conversation = Conversation::create([
                'social_account_id' => $account->id,
                'platform' => 'facebook',
                'external_conversation_id' => $externalId,
                'customer_name' => $profile['name'] ?? 'Facebook User',
                'customer_id' => $psid,
                'customer_avatar' => $profile['profile_pic'] ?? null,
                'ai_active' => true,
                'status' => 'open',
            ]);
        }

        $conversation->messages()->create([
            'external_message_id' => $mid,
            'message_text' => $text,
            'sender_type' => 'customer',
            'sender_id' => $psid,
            'is_read' => false,
        ]);
        $conversation->update(['last_message_at' => now()]);

        if ($conversation->ai_active && config('services.meta.auto_reply_messages')) {
            GenerateAIResponse::dispatchSync($conversation->id, $text);
        }
    }

    /**
     * Page feed: save a new comment on a post and trigger an AI reply.
     */
    protected function handleChange(SocialAccount $account, array $change): void
    {
        $v = $change['value'] ?? [];

        if (($change['field'] ?? '') !== 'feed' || ($v['item'] ?? '') !== 'comment' || ($v['verb'] ?? '') !== 'add') {
            return;
        }

        $from = $v['from'] ?? [];
        // Never reply to the Page's own comments (prevents reply loops)
        if ((string) ($from['id'] ?? '') === $account->account_id) {
            return;
        }

        $commentId = $v['comment_id'] ?? null;
        if (!$commentId || Comment::where('external_comment_id', $commentId)->exists()) {
            return;
        }

        $comment = Comment::create([
            'social_account_id' => $account->id,
            'platform' => 'facebook',
            'external_comment_id' => $commentId,
            'external_post_id' => $v['post_id'] ?? '',
            'customer_name' => $from['name'] ?? 'Facebook User',
            'customer_id' => $from['id'] ?? '',
            'comment_text' => $v['message'] ?? '',
            'reply_status' => 'pending',
        ]);

        if (config('services.meta.auto_reply_comments') && trim($comment->comment_text) !== '') {
            GenerateCommentReply::dispatchSync($comment->id);
        }
    }
}
