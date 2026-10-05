<?php

namespace App\Services\Automation;

use App\Models\AutomationRule;
use App\Models\AutoReplyRule;
use App\Models\AutoCommentRule;

class AutomationService
{
    /**
     * Check if an incoming message matches any keyword rules
     */
    public function checkKeywordRules(string $messageText, string $platform): ?AutomationRule
    {
        $rules = AutomationRule::where('is_active', true)
            ->whereIn('platform', [$platform, 'all'])
            ->get();
            
        foreach ($rules as $rule) {
            $keywords = explode(',', $rule->keywords);
            
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                
                if ($rule->trigger_type === 'exact_match' && strtolower($messageText) === strtolower($keyword)) {
                    return $rule;
                }
                
                if ($rule->trigger_type === 'contains' && stripos($messageText, $keyword) !== false) {
                    return $rule;
                }
            }
        }
        
        return null;
    }

    /**
     * Find auto-reply template based on intent
     */
    public function getAutoReplyByIntent(string $intent): ?AutoReplyRule
    {
        return AutoReplyRule::where('is_active', true)
                            ->where('trigger_intent', $intent)
                            ->first();
    }
}
