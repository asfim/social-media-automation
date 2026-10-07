<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;
use App\Services\AI\AIService;
use App\Services\Meta\MetaService;

class GenerateAIResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversationId;
    protected $messageText;

    /**
     * Create a new job instance.
     */
    public function __construct($conversationId, $messageText)
    {
        $this->conversationId = $conversationId;
        $this->messageText = $messageText;
    }

    /**
     * Execute the job.
     */
    public function handle(AIService $aiService, MetaService $metaService): void
    {
        Log::info("Generating AI response for conversation {$this->conversationId}");

        $conversation = Conversation::with('account')->find($this->conversationId);

        // Human took over (or chat deleted): stay silent
        if (!$conversation || !$conversation->ai_active) {
            return;
        }

        // Last turns of context, excluding the message we are answering (newest)
        $history = $conversation->messages()->latest('id')->take(11)->get()->reverse()->values()
            ->slice(0, -1)
            ->map(fn ($m) => [
                'role' => $m->sender_type === 'customer' ? 'user' : 'assistant',
                'content' => $m->message_text,
            ])->all();

        $result = $aiService->generateResponse($this->messageText, $conversation->platform, 'message', $history, $conversation->account);

        $token = $conversation->account->access_token ?? null;
        if (!$token) {
            Log::warning("Conversation {$conversation->id}: no access token, AI reply not sent.");
            return;
        }

        $sent = $metaService->sendText($token, $conversation->customer_id, $result['reply']);

        if (!$sent['ok']) {
            Log::warning("Conversation {$conversation->id}: AI reply failed to send to Meta: " . $sent['error'] . ". Saving locally anyway for testing.");
            // Do not return, continue to save the message locally so the UI shows it.
        }

        $conversation->messages()->create([
            'external_message_id' => $sent['data']['message_id'] ?? ('ai_' . uniqid()),
            'message_text' => $result['reply'],
            'sender_type' => 'ai',
            'ai_confidence' => $result['confidence'],
            'is_read' => true,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => $result['requires_human'] ? 'human_review' : 'open',
        ]);

        // Trigger Lead Scoring
        CalculateLeadScore::dispatchSync($conversation->id);
    }
}
