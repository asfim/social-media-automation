<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CalculateLeadScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversationId;

    /**
     * Create a new job instance.
     */
    public function __construct($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Calculating lead score for conversation {$this->conversationId}");

        // 1. Analyze the customer's last 5 messages
        // 2. Look for high-intent keywords (price, buy, order, address)
        // 3. Update the `lead_score` and `lead_status` in the `leads` table
        // 4. If score > 90 (Hot Lead), trigger an Admin Notification
    }
}
