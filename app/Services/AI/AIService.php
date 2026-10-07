<?php

namespace App\Services\AI;

use App\Models\BusinessSetting;
use App\Models\BusinessKnowledge;
use App\Models\Faq;
use App\Models\Product;
use App\Models\SocialAccount;
use App\Services\Meta\MetaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate a reply for an incoming message or comment.
     *
     * @param array $history Prior turns as [['role' => 'user|assistant', 'content' => '...'], ...]
     * @param SocialAccount|null $account The connected Facebook/Instagram page account
     */
    public function generateResponse(string $message, string $platform, string $type = 'message', array $history = [], ?SocialAccount $account = null): array
    {
        if (config('services.openai.key')) {
            $ai = $this->callLlm($message, $type, $history, $account);
            if ($ai) {
                return $ai;
            }
        }

        return $this->ruleBasedReply($message, $type);
    }

    private function callLlm(string $message, string $type, array $history, ?SocialAccount $account): ?array
    {
        $messages = [['role' => 'system', 'content' => $this->buildSystemPrompt($type, $account)]];
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

    private function buildSystemPrompt(string $type, ?SocialAccount $account): string
    {
        $s = BusinessSetting::first();
        $businessName = $account->account_name ?? ($s->business_name ?? 'our business');

        $prompt = "You are the official AI assistant for {$businessName}. Tone: " 
            . ($s->tone ?? 'friendly and professional') 
            . '. Reply language: ' . ($s->language ?? 'same language as the customer') . ".\n\n";

        // STEP 1: Check Database Business Knowledge & Settings
        $prompt .= "=== STEP 1: DATABASE BUSINESS KNOWLEDGE ===\n";
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
                $prompt .= "{$k}: {$v}\n";
            }
        }

        // Add Knowledge Base Articles from Database
        $knowledgeArticles = BusinessKnowledge::where('is_active', true)->get();
        if ($knowledgeArticles->isNotEmpty()) {
            $prompt .= "\nKnowledge Base:\n";
            foreach ($knowledgeArticles as $item) {
                $prompt .= "- {$item->title}: {$item->content}\n";
            }
        }

        // FAQs
        $faqs = Faq::where('status', true)->limit(30)->get(['question', 'answer']);
        if ($faqs->isNotEmpty()) {
            $prompt .= "\nFAQs:\n";
            foreach ($faqs as $f) {
                $prompt .= "Q: {$f->question}\nA: {$f->answer}\n";
            }
        }

        // Products & Services
        $products = Product::limit(30)->get(['name', 'price', 'description']);
        if ($products->isNotEmpty()) {
            $prompt .= "\nProducts/Services:\n";
            foreach ($products as $p) {
                $prompt .= "- {$p->name}" . ($p->price ? " ({$p->price})" : '') . ($p->description ? ": {$p->description}" : '') . "\n";
            }
        }

        // STEP 2 & 3: Check Connected Facebook Page Info & Image Captions via Meta API
        if ($account && $account->platform === 'facebook' && !empty($account->access_token) && !empty($account->account_id)) {
            try {
                $meta = new MetaService();
                $pageInfo = $meta->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($pageInfo)) {
                    $prompt .= "\n=== STEP 2: CONNECTED FACEBOOK PAGE INFORMATION ===\n";
                    if (!empty($pageInfo['about'])) $prompt .= "Page About: {$pageInfo['about']}\n";
                    if (!empty($pageInfo['description'])) $prompt .= "Page Description: {$pageInfo['description']}\n";
                    if (!empty($pageInfo['phone'])) $prompt .= "Page Phone: {$pageInfo['phone']}\n";
                    if (!empty($pageInfo['emails'][0])) $prompt .= "Page Email: {$pageInfo['emails'][0]}\n";
                    if (!empty($pageInfo['website'])) $prompt .= "Page Website: {$pageInfo['website']}\n";
                    if (!empty($pageInfo['single_line_address'])) $prompt .= "Page Address: {$pageInfo['single_line_address']}\n";
                }

                $pagePosts = $meta->fetchPagePosts($account->account_id, $account->access_token);
                if (!empty($pagePosts)) {
                    $prompt .= "\n=== STEP 3: FACEBOOK PAGE RECENT POSTS & IMAGE CAPTIONS ===\n";
                    foreach (array_slice($pagePosts, 0, 10) as $idx => $post) {
                        $postContent = [];
                        if (!empty($post['message'])) $postContent[] = "Post Message: {$post['message']}";
                        if (!empty($post['caption'])) $postContent[] = "Caption: {$post['caption']}";
                        if (!empty($post['description'])) $postContent[] = "Description: {$post['description']}";

                        if (!empty($post['attachments']['data'])) {
                            foreach ($post['attachments']['data'] as $att) {
                                if (!empty($att['title'])) $postContent[] = "Image/Media Title: {$att['title']}";
                                if (!empty($att['description'])) $postContent[] = "Image/Media Description: {$att['description']}";
                            }
                        }

                        if (!empty($postContent)) {
                            $prompt .= "- Post #" . ($idx + 1) . ": " . implode(' | ', $postContent) . "\n";
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to fetch Facebook Page details or posts for AI prompt: ' . $e->getMessage());
            }
        }

        $prompt .= "\n\nRules:\n"
            . "1. Always check Database Knowledge first. If information is not in database, use the Connected Facebook Page info.\n"
            . "2. Never invent prices, policies, or facts that are not listed in Database or Facebook Page info.\n"
            . "3. If information is not available in both Database and Facebook Page, politely state that our team will follow up.\n";
        
        $prompt .= $type === 'comment'
            ? 'You are replying publicly to a Facebook post comment: keep it to 1-2 short sentences and invite them to inbox us for details.'
            : 'Keep replies concise, friendly, and conversational.';

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

