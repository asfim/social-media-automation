<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        Log::info("Processing incoming {$this->platform} message in background queue.", ['payload' => $this->payload]);

        // 1. Extract sender ID and message text from payload
        // 2. Find or create Customer/Lead in DB
        // 3. Save message to conversation_messages table
        // 4. Dispatch GenerateAIResponse job if AI is enabled for this conversation
        
        // Mock implementation logic:
        $messageText = "Mock extracted message";
        $conversationId = 1;
        
        // Dispatch the AI processing to another job to keep this job fast
        GenerateAIResponse::dispatch($conversationId, $messageText);
    }
}
