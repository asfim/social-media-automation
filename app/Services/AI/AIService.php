<?php

namespace App\Services\AI;

use App\Models\BusinessSetting;
use App\Models\BusinessKnowledge;
use App\Models\Faq;

class AIService
{
    /**
     * Generate response based on incoming message and business context
     */
    public function generateResponse(string $message, string $platform): array
    {
        $businessInfo = $this->getBusinessContext();
        $faqs = $this->getRelevantFaqs($message);
        
        // TODO: Call OpenAI / Anthropic API here
        // Simulated Response for now:
        
        $confidence = rand(70, 99);
        
        return [
            'reply' => "Thank you for your message! This is an AI generated response based on your query.",
            'confidence' => $confidence,
            'intent' => 'general',
            'requires_human' => $confidence < 60
        ];
    }
    
    /**
     * Get system prompt context for the AI
     */
    private function getBusinessContext(): string
    {
        $settings = BusinessSetting::first();
        if (!$settings) return "You are a helpful business assistant.";
        
        return "You are the official AI assistant for {$settings->business_name}. " .
               "Tone: {$settings->tone}. Language: {$settings->language}. " .
               "Never invent pricing or policies.";
    }

    private function getRelevantFaqs(string $message)
    {
        // Vector search or keyword match
        return Faq::where('status', true)->get();
    }
}
