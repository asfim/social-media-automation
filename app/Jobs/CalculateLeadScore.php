<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;
use App\Models\Lead;

class CalculateLeadScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversationId;

    /** High-intent keywords (English + Bangla) */
    protected array $keywords = [
        'price', 'cost', 'buy', 'order', 'address', 'details', 'package', 'demo', 'phone', 'number',
        'দাম', 'কত', 'টাকা', 'অর্ডার', 'কিনতে', 'ঠিকানা', 'প্যাকেজ', 'ফোন', 'নম্বর',
    ];

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

        $conversation = Conversation::find($this->conversationId);
        if (!$conversation) {
            return;
        }

        // 1. Analyze the customer's last 10 messages
        $texts = $conversation->messages()->where('sender_type', 'customer')
            ->latest('id')->take(10)->pluck('message_text');
        $joined = mb_strtolower($texts->implode(' '));

        // 2. Look for high-intent keywords
        $hits = collect($this->keywords)->filter(fn ($k) => str_contains($joined, $k))->count();
        $score = min(100, 20 + ($hits * 15) + ($texts->count() * 3));

        // 3. Update the lead (create it on first contact)
        $lead = Lead::firstOrNew(['profile_id' => $conversation->customer_id, 'platform' => $conversation->platform]);
        if (!$lead->exists) {
            $lead->name = $conversation->customer_name;
            $lead->source = 'Auto';
            $lead->lead_status = 'New';
        }
        $lead->lead_score = max((int) $lead->lead_score, $score);
        if ($lead->lead_score >= 90 && $lead->lead_status === 'New') {
            $lead->lead_status = 'Interested';
        }
        $lead->last_contact = now();
        $lead->save();

        $conversation->update(['lead_score' => $lead->lead_score]);
    }
}
