<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\AI\AIService;

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
    public function handle(AIService $aiService): void
    {
        Log::info("Generating AI response for conversation {$this->conversationId}");

        // 1. Check if AI is paused/human took over
        // 2. Load Conversation History
        // 3. Load Business Knowledge & FAQs
        // 4. Call AIService to get response from OpenAI
        // 5. Save AI response to DB
        // 6. Call Meta API to send message back to user

        // Trigger Lead Scoring in parallel
        CalculateLeadScore::dispatch($this->conversationId);
    }
}
