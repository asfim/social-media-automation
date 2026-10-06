<?php

namespace App\Services\AI;

use App\Models\BusinessSetting;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate a reply for an incoming message or comment.
     *
     * Uses an OpenAI-compatible API when OPENAI_API_KEY is set, otherwise
     * falls back to FAQ keyword matching so auto-reply still works.
     *
     * @param array $history Prior turns as [['role' => 'user|assistant', 'content' => '...'], ...]
     */
    public function generateResponse(string $message, string $platform, string $type = 'message', array $history = []): array
    {
        if (config('services.openai.key')) {
            $ai = $this->callLlm($message, $type, $history);
            if ($ai) {
                return $ai;
            }
        }

        return $this->ruleBasedReply($message, $type);
    }

    private function callLlm(string $message, string $type, array $history): ?array
    {
        $messages = [['role' => 'system', 'content' => $this->buildSystemPrompt($type)]];
        foreach ($history as $turn) {
            $messages[] = $turn;
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $res = Http::timeout(25)
                ->withToken(config('services.openai.key'))
                ->post(rtrim(config('services.openai.base_url'), '/') . '/chat/completions', [
                    'model' => config('services.openai.model'),
                    'messages' => $messages,
                    'temperature' => 0.4,
                    'max_tokens' => 300,
                ]);

            $text = trim((string) $res->json('choices.0.message.content', ''));
            if ($res->successful() && $text !== '') {
                return ['reply' => $text, 'confidence' => 85, 'intent' => 'ai', 'requires_human' => false];
            }

            Log::warning('AI API returned no usable reply', ['status' => $res->status(), 'body' => $res->json()]);
        } catch (\Throwable $e) {
            Log::warning('AI API call failed: ' . $e->getMessage());
        }

        return null;
    }

    private function buildSystemPrompt(string $type): string
    {
        $s = BusinessSetting::first();
        $prompt = $s
            ? "You are the official AI assistant for {$s->business_name}. Tone: " . ($s->tone ?: 'friendly') . '. Reply language: ' . ($s->language ?: 'same language as the customer') . '.'
            : 'You are a helpful business assistant.';

        if ($s) {
            $facts = array_filter([
                'About' => $s->business_description,
                'Location' => $s->business_location,
                'Phone' => $s->phone,
                'WhatsApp' => $s->whatsapp,
                'Email' => $s->email,
                'Website' => $s->website,
                'Hours' => $s->business_hours,
            ]);
            foreach ($facts as $k => $v) {
                $prompt .= "\n{$k}: {$v}";
            }
        }

        $faqs = Faq::where('status', true)->limit(30)->get(['question', 'answer']);
        if ($faqs->isNotEmpty()) {
            $prompt .= "\n\nFAQs:\n";
            foreach ($faqs as $f) {
                $prompt .= "Q: {$f->question}\nA: {$f->answer}\n";
            }
        }

        $products = Product::limit(30)->get(['name', 'price', 'description']);
        if ($products->isNotEmpty()) {
            $prompt .= "\nProducts/Services:\n";
            foreach ($products as $p) {
                $prompt .= "- {$p->name}" . ($p->price ? " ({$p->price})" : '') . ($p->description ? ": {$p->description}" : '') . "\n";
            }
        }

        $prompt .= "\n\nRules: Never invent prices, policies or facts that are not listed above. "
            . 'If you do not know, politely say the team will follow up. ';
        $prompt .= $type === 'comment'
            ? 'You are replying publicly to a Facebook post comment: keep it to 1-2 short sentences and invite them to inbox us for details.'
            : 'Keep replies short and conversational.';

        return $prompt;
    }

    private function ruleBasedReply(string $message, string $type): array
    {
        $tokens = $this->tokenize($message);
        $best = null;
        $bestScore = 0;

        foreach (Faq::where('status', true)->get() as $faq) {
            $faqTokens = $this->tokenize($faq->question . ' ' . $faq->keywords);
            if (!$faqTokens) {
                continue;
            }
            $score = count(array_intersect($tokens, $faqTokens));
            $needed = min(2, count($faqTokens));
            if ($score >= $needed && $score > $bestScore) {
                $best = $faq;
                $bestScore = $score;
            }
        }

        if ($best) {
            return ['reply' => $best->answer, 'confidence' => 90, 'intent' => 'faq', 'requires_human' => false];
        }

        $name = BusinessSetting::first()->business_name ?? 'us';
        $reply = $type === 'comment'
            ? "Thank you for your comment! Please inbox {$name} for more details."
            : "Thank you for contacting {$name}! We have received your message and our team will get back to you shortly.";

        return ['reply' => $reply, 'confidence' => 50, 'intent' => 'general', 'requires_human' => true];
    }

    private function tokenize(string $text): array
    {
        $parts = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_filter($parts, fn ($w) => mb_strlen($w) >= 3)));
    }
}
