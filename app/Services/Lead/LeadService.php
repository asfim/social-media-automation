<?php

namespace App\Services\Lead;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Conversation;

class LeadService
{
    /**
     * Identify or create a lead from a conversation
     */
    public function detectLeadFromConversation(Conversation $conversation, string $intent): ?Lead
    {
        // Check if customer is already a lead
        $lead = Lead::where('profile_id', $conversation->customer_id)
                    ->where('platform', $conversation->platform)
                    ->first();
                    
        if (!$lead && in_array($intent, ['pricing', 'product', 'service', 'lead'])) {
            // Create new lead
            $lead = Lead::create([
                'name' => $conversation->customer_name,
                'platform' => $conversation->platform,
                'profile_id' => $conversation->customer_id,
                'lead_score' => 40,
                'lead_status' => 'new',
                'source' => 'social_inbox'
            ]);
            
            $this->logActivity($lead->id, 'status_change', 'Lead automatically created from conversation.');
        }
        
        return $lead;
    }
    
    /**
     * Calculate and update lead score
     */
    public function updateLeadScore(Lead $lead, int $scoreToAdd)
    {
        $newScore = min(100, $lead->lead_score + $scoreToAdd);
        $status = $this->determineStatusFromScore($newScore);
        
        $lead->update([
            'lead_score' => $newScore,
            'lead_status' => $status
        ]);
        
        return $lead;
    }
    
    private function determineStatusFromScore(int $score): string
    {
        if ($score >= 90) return 'hot';
        if ($score >= 70) return 'interested';
        if ($score >= 40) return 'contacted';
        return 'new';
    }

    public function logActivity(int $leadId, string $type, string $description)
    {
        return LeadActivity::create([
            'lead_id' => $leadId,
            'activity_type' => $type,
            'description' => $description
        ]);
    }
}
