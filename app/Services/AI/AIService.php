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
     * Fully Automated AI Response Engine.
     * Automatically uses connected Facebook Page's information, image captions,
     * posts, database knowledge, FAQs & LLM to reply to customers.
     */
    public function generateResponse(string $message, string $platform, string $type = 'message', array $history = [], ?SocialAccount $account = null): array
    {
        if (!$account) {
            $account = SocialAccount::where('platform', 'facebook')->where('is_active', true)->latest()->first();
        }

        $lower = mb_strtolower($message);

        // 1. Call OpenAI LLM if API key is configured
        if (config('services.openai.key')) {
            $ai = $this->callLlm($message, $type, $history, $account);
            if ($ai) {
                if ($this->isProductCatalogRequest($message) && strpos($ai['reply'], '[PRODUCT_CARD:') === false) {
                    $ai['reply'] .= "\n\n" . $this->getProductCardTags();
                }
                return $ai;
            }
        }

        // 2. Greeting / Salam Intent
        if ($this->isGreetingRequest($lower)) {
            $s = BusinessSetting::first();
            $name = $account->account_name ?? ($s->business_name ?? 'আমাদের পেজে');
            return [
                'reply' => "ওয়ালাইকুম আসসালাম! {$name}-এ আপনাকে স্বাগতম। কীভাবে সাহায্য করতে পারি বলুন?",
                'confidence' => 99,
                'intent' => 'greeting',
                'requires_human' => false
            ];
        }

        // 3. Delivery Charge / Delivery Time Intent
        if ($this->isDeliveryRequest($lower)) {
            return [
                'reply' => "আমাদের ডেলিভারি সংক্রান্ত তথ্য:\n• ঢাকা সিটির ভিতরে ডেলিভারি চার্জ: ৳৭০\n• ঢাকা সিটির বাহিরে ডেলিভারি চার্জ: ৳১৩০\n• অর্ডার কনফার্মের পর সাধারণত ২-৩ দিনের মধ্যে হোম ডেলিভারি দেওয়া হয়।",
                'confidence' => 98,
                'intent' => 'delivery',
                'requires_human' => false
            ];
        }

        // 4. Office Address / Location Intent
        if ($this->isLocationRequest($lower)) {
            $s = BusinessSetting::first();
            $loc = "Ashkona Bazer, Dakshinkhan, S S Tower (Ground floor), Under Jamuna Bank, Uttara, Dhaka";
            $phone = "+8801866877958";

            if ($account && $account->access_token) {
                $details = app(MetaService::class)->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($details['single_line_address'])) $loc = $details['single_line_address'];
                if (!empty($details['phone'])) $phone = $details['phone'];
            }

            return [
                'reply' => "আমাদের অফিসের ঠিকানা:\n🏢 {$loc}\n📞 যোগাযোগ: {$phone}",
                'confidence' => 98,
                'intent' => 'location',
                'requires_human' => false
            ];
        }

        // 5. Contact / Phone Number Intent
        if ($this->isContactRequest($lower)) {
            $phone = "+8801866877958";
            if ($account && $account->access_token) {
                $details = app(MetaService::class)->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($details['phone'])) $phone = $details['phone'];
            }
            return [
                'reply' => "আমাদের সাথে সরাসরি কথা বলতে ফোন করুন: 📞 {$phone}",
                'confidence' => 98,
                'intent' => 'contact',
                'requires_human' => false
            ];
        }

        // 6. How to Order Intent
        if ($this->isOrderProcessRequest($lower)) {
            return [
                'reply' => "অর্ডার করার নিয়ম:\n১. পছন্দের সার্ভিস বা পণ্যের নিচে থাকা 'অর্ডার করুন (Order Now)' বাটনে ক্লিক করুন।\n২. আপনার নাম, ফোন নম্বর ও ডেলিভারি ঠিকানা দিন।\n৩. কনফার্ম বাটনে চাপ দিলেই অর্ডার সফলভাবে সাবমিট হয়ে যাবে।",
                'confidence' => 98,
                'intent' => 'order_process',
                'requires_human' => false
            ];
        }

        // 7. Specific Single Product Search (e.g. asking for "school software" or "e-commerce website")
        $singleProduct = $this->matchSingleProduct($lower);
        if ($singleProduct) {
            $pr = $singleProduct->discount_price > 0 ? "৳" . number_format($singleProduct->discount_price) : "৳" . number_format($singleProduct->price);
            return [
                'reply' => "জি, আমাদের কাছে '{$singleProduct->name}' এভেলেবেল রয়েছে!\nমূল্য: {$pr}\n\n[PRODUCT_CARD:{$singleProduct->id}]\n\nঅর্ডার করতে কার্ডের 'অর্ডার করুন' বাটনে ক্লিক করুন।",
                'confidence' => 99,
                'intent' => 'single_product',
                'requires_human' => false
            ];
        }

        // 8. Full Product Catalog Request ("ki ki product", "catalogue", "all products")
        if ($this->isProductCatalogRequest($lower)) {
            return $this->buildDynamicProductReply();
        }

        // 9. Match Customer Question with Connected Facebook Page Posts & Captions
        $pagePostMatch = $this->matchConnectedPagePosts($message, $account);
        if ($pagePostMatch) {
            return $pagePostMatch;
        }

        // 10. Dynamic FAQ Match directly from Database
        $faqMatch = $this->matchFaqFromDatabase($message);
        if ($faqMatch) {
            return $faqMatch;
        }

        // 11. Dynamic Knowledge Base Match directly from Database
        $kbMatch = $this->matchKnowledgeFromDatabase($message);
        if ($kbMatch) {
            return $kbMatch;
        }

        // 12. Dynamic Fallback Context Reply
        return $this->buildDynamicContextReply($message, $account);
    }

    private function isGreetingRequest(string $lower): bool
    {
        $greetings = ['hi', 'hello', 'assalamu alaikum', 'slam', 'সালাম', 'আসসালামু আলাইকুম', 'হাই', 'হ্যালো', 'হেই', 'hey'];
        foreach ($greetings as $g) {
            if (mb_strpos($lower, $g) !== false) return true;
        }
        return false;
    }

    private function isDeliveryRequest(string $lower): bool
    {
        $keywords = ['delivery', 'ডেলিভারি', 'শিপিং', 'পৌঁছাবে', 'কত দিন', 'কতো দিন', 'কত সময়', 'চার্জ'];
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) return true;
        }
        return false;
    }

    private function isLocationRequest(string $lower): bool
    {
        $keywords = ['location', 'address', 'ঠিকানা', 'লোকেশন', 'কোথায়', 'অফিস', 'উত্তরা', 'আশ কোনা'];
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) return true;
        }
        return false;
    }

    private function isContactRequest(string $lower): bool
    {
        $keywords = ['phone', 'number', 'নম্বর', 'ফোন', 'কল', 'যোগাযোগ', 'mobile'];
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) return true;
        }
        return false;
    }

    private function isOrderProcessRequest(string $lower): bool
    {
        $keywords = ['কীভাবে অর্ডার', 'কিভাবে অর্ডার', 'অর্ডার করবো', 'অর্ডার নিয়ম', 'কিভাবে কিনব', 'অর্ডার করব'];
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) return true;
        }
        return false;
    }

    private function isProductCatalogRequest(string $lower): bool
    {
        $keywords = [
            'ki ki product', 'kiki product', 'কী কী প্রোডাক্ট', 'কি কি প্রোডাক্ট', 'প্রোডাক্ট কি কি',
            'সব প্রোডাক্ট', 'তালিকা', 'ক্যাটালগ', 'catalogue', 'catalog', 'list', 'items'
        ];
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, $kw) !== false) return true;
        }
        return false;
    }

    private function matchSingleProduct(string $lower): ?Product
    {
        $products = Product::where('status', true)->get();
        foreach ($products as $p) {
            $nameLower = mb_strtolower($p->name);
            $words = explode(' ', $nameLower);
            foreach ($words as $w) {
                if (mb_strlen($w) >= 4 && mb_strpos($lower, $w) !== false) {
                    return $p;
                }
            }
        }
        return null;
    }

    /**
     * Dynamically build Product Reply using product card tags.
     */
    private function buildDynamicProductReply(): array
    {
        $products = Product::where('status', true)->take(6)->get();
        $syncedPosts = BusinessKnowledge::where('category', 'facebook_post')->where('is_active', true)->take(3)->get();

        if ($products->isEmpty() && $syncedPosts->isEmpty()) {
            $s = BusinessSetting::first();
            $name = $s->business_name ?? '';
            return [
                'reply' => $name ? "{$name}-এর প্রোডাক্ট ক্যাটালগ দ্রুত আপডেট করা হচ্ছে।" : "প্রোডাক্ট ক্যাটালগ শীঘ্রই আপডেট করা হবে।",
                'confidence' => 90,
                'intent' => 'products',
                'requires_human' => false
            ];
        }

        $reply = "জি অবশ্যই! আসসালামু আলাইকুম।\nআমাদের ফেসবুক পেজের প্রোডাক্ট ক্যাটালগ ও সেরা আইটেমগুলোর তালিকা নিচে দেওয়া হলো:\n\n";

        if (!$syncedPosts->isEmpty()) {
            foreach ($syncedPosts as $sp) {
                $reply .= "📌 " . \Illuminate\Support\Str::limit($sp->content, 150) . "\n\n";
            }
        }

        if (!$products->isEmpty()) {
            foreach ($products as $p) {
                $reply .= "[PRODUCT_CARD:" . $p->id . "]\n\n";
            }
        }

        $reply .= "পছন্দের পণ্যের নিচে 'অর্ডার করুন' বাটনে ক্লিক করে নাম, ফোন নম্বর ও ঠিকানা দিয়ে সরাসরি অর্ডার করতে পারেন।";

        return [
            'reply' => trim($reply),
            'confidence' => 98,
            'intent' => 'products',
            'requires_human' => false
        ];
    }

    private function getProductCardTags(): string
    {
        $products = Product::where('status', true)->take(4)->get();
        $tags = "";
        foreach ($products as $p) {
            $tags .= "[PRODUCT_CARD:" . $p->id . "]\n";
        }
        return trim($tags);
    }

    /**
     * Match customer query with connected Facebook Page posts, images, and captions.
     */
    private function matchConnectedPagePosts(string $message, ?SocialAccount $account): ?array
    {
        if (!$account || empty($account->account_id) || empty($account->access_token)) {
            return null;
        }

        try {
            $meta = app(MetaService::class);
            $posts = $meta->fetchPagePosts($account->account_id, $account->access_token);
            $tokens = $this->tokenize($message);

            if (empty($tokens) || empty($posts)) return null;

            $bestPost = null;
            $bestScore = 0;

            foreach ($posts as $post) {
                $postText = ($post['message'] ?? '') . ' ' . ($post['caption'] ?? '') . ' ' . ($post['description'] ?? '');
                if (!empty($post['attachments']['data'])) {
                    foreach ($post['attachments']['data'] as $att) {
                        $postText .= ' ' . ($att['title'] ?? '') . ' ' . ($att['description'] ?? '');
                    }
                }

                $postTokens = $this->tokenize($postText);
                if (empty($postTokens)) continue;

                $score = count(array_intersect($tokens, $postTokens));
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestPost = $postText;
                }
            }

            if ($bestPost && $bestScore >= 1) {
                return [
                    'reply' => trim($bestPost),
                    'confidence' => min(96, 75 + ($bestScore * 10)),
                    'intent' => 'facebook_page_post',
                    'requires_human' => false
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to match connected Page posts: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Match customer question with FAQs in Database.
     */
    private function matchFaqFromDatabase(string $message): ?array
    {
        $tokens = $this->tokenize($message);
        if (empty($tokens)) return null;

        $faqs = Faq::where('status', true)->get();
        $bestFaq = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            $faqTokens = $this->tokenize($faq->question . ' ' . $faq->keywords . ' ' . $faq->answer);
            if (empty($faqTokens)) continue;

            $score = count(array_intersect($tokens, $faqTokens));
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestFaq = $faq;
            }
        }

        if ($bestFaq && $bestScore >= 1) {
            return [
                'reply' => $bestFaq->answer,
                'confidence' => min(98, 70 + ($bestScore * 10)),
                'intent' => 'faq',
                'requires_human' => false
            ];
        }

        return null;
    }

    /**
     * Match customer question with Business Knowledge Base in Database.
     */
    private function matchKnowledgeFromDatabase(string $message): ?array
    {
        $tokens = $this->tokenize($message);
        if (empty($tokens)) return null;

        $articles = BusinessKnowledge::where('is_active', true)->get();
        $bestKb = null;
        $bestScore = 0;

        foreach ($articles as $kb) {
            $kbTokens = $this->tokenize($kb->title . ' ' . $kb->content . ' ' . $kb->category);
            if (empty($kbTokens)) continue;

            $score = count(array_intersect($tokens, $kbTokens));
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestKb = $kb;
            }
        }

        if ($bestKb && $bestScore >= 1) {
            return [
                'reply' => "{$bestKb->title}:\n{$bestKb->content}",
                'confidence' => min(95, 70 + ($bestScore * 10)),
                'intent' => 'knowledge',
                'requires_human' => false
            ];
        }

        return null;
    }

    /**
     * Dynamically build context reply using Business Settings & Connected Page Profile.
     */
    private function buildDynamicContextReply(string $message, ?SocialAccount $account): array
    {
        $s = BusinessSetting::first();
        $businessName = $account->account_name ?? ($s->business_name ?? 'আমাদের পেজে');

        $lower = mb_strtolower($message);

        // Location
        if (mb_strpos($lower, 'location') !== false || mb_strpos($lower, 'address') !== false || mb_strpos($lower, 'ঠিকানা') !== false || mb_strpos($lower, 'কোথায়') !== false) {
            $loc = $s->business_location ?? null;
            if ($account && $account->access_token) {
                $details = app(MetaService::class)->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($details['single_line_address'])) $loc = $details['single_line_address'];
            }
            if ($loc) {
                return [
                    'reply' => "{$businessName}-এর ঠিকানা: {$loc}",
                    'confidence' => 95,
                    'intent' => 'location',
                    'requires_human' => false
                ];
            }
        }

        // Contact / Phone
        if (mb_strpos($lower, 'phone') !== false || mb_strpos($lower, 'number') !== false || mb_strpos($lower, 'ফোন') !== false || mb_strpos($lower, 'যোগাযোগ') !== false) {
            $phone = $s->phone ?? ($s->whatsapp ?? null);
            if ($account && $account->access_token) {
                $details = app(MetaService::class)->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($details['phone'])) $phone = $details['phone'];
            }
            if ($phone) {
                return [
                    'reply' => "{$businessName}-এর যোগাযোগের ফোন নম্বর: {$phone}",
                    'confidence' => 95,
                    'intent' => 'contact',
                    'requires_human' => false
                ];
            }
        }

        $desc = $s->business_description ? "\n{$s->business_description}" : '';

        return [
            'reply' => "{$businessName}-এ আপনাকে স্বাগতম।{$desc}\nকীভাবে আপনাকে সাহায্য করতে পারি?",
            'confidence' => 80,
            'intent' => 'general',
            'requires_human' => false
        ];
    }

    /**
     * Call OpenAI API to dynamically process & answer any question using connected Page context.
     */
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
                    'max_tokens' => 400,
                ]);

            $text = trim((string) $res->json('choices.0.message.content', ''));
            if ($res->successful() && $text !== '') {
                return ['reply' => $text, 'confidence' => 95, 'intent' => 'ai_generated', 'requires_human' => false];
            }
        } catch (\Throwable $e) {
            Log::warning('AI API call failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Build System Prompt for OpenAI LLM enforcing [PRODUCT_CARD:id] tags.
     */
    private function buildSystemPrompt(string $type, ?SocialAccount $account): string
    {
        $s = BusinessSetting::first();
        $businessName = $account->account_name ?? ($s->business_name ?? 'আমাদের পেজ');

        $prompt = "You are an AI assistant for the connected Facebook Page: {$businessName}.\n"
            . "Understand customer questions dynamically and reply in polite, natural Bangla or Banglish.\n"
            . "MANDATORY PRODUCT RULE: Whenever listing products or responding to product inquiries (e.g., 'kiki product as', 'show products', 'price'), NEVER draw markdown tables or text lists. ALWAYS output [PRODUCT_CARD:id] tags for each product (e.g. [PRODUCT_CARD:1] [PRODUCT_CARD:2] [PRODUCT_CARD:3]).\n\n"
            . "=== BUSINESS PROFILE & KNOWLEDGE ===\n";

        if ($s) {
            if ($s->business_description) $prompt .= "About: {$s->business_description}\n";
            if ($s->business_location) $prompt .= "Location: {$s->business_location}\n";
            if ($s->phone) $prompt .= "Phone: {$s->phone}\n";
            if ($s->whatsapp) $prompt .= "WhatsApp: {$s->whatsapp}\n";
            if ($s->email) $prompt .= "Email: {$s->email}\n";
        }

        // Fetch Connected Facebook Page Details & Recent Posts with Captions
        if ($account && !empty($account->account_id) && !empty($account->access_token)) {
            try {
                $meta = app(MetaService::class);
                $pageInfo = $meta->fetchPageDetails($account->account_id, $account->access_token);
                if (!empty($pageInfo)) {
                    $prompt .= "\n=== CONNECTED FACEBOOK PAGE INFO ===\n";
                    if (!empty($pageInfo['name'])) $prompt .= "Page Name: {$pageInfo['name']}\n";
                    if (!empty($pageInfo['about'])) $prompt .= "About: {$pageInfo['about']}\n";
                    if (!empty($pageInfo['description'])) $prompt .= "Description: {$pageInfo['description']}\n";
                    if (!empty($pageInfo['phone'])) $prompt .= "Phone: {$pageInfo['phone']}\n";
                    if (!empty($pageInfo['emails'][0])) $prompt .= "Email: {$pageInfo['emails'][0]}\n";
                    if (!empty($pageInfo['website'])) $prompt .= "Website: {$pageInfo['website']}\n";
                    if (!empty($pageInfo['single_line_address'])) $prompt .= "Address: {$pageInfo['single_line_address']}\n";
                }

                $pagePosts = $meta->fetchPagePosts($account->account_id, $account->access_token);
                if (!empty($pagePosts)) {
                    $prompt .= "\n=== CONNECTED FACEBOOK PAGE RECENT POSTS & IMAGE CAPTIONS ===\n";
                    foreach (array_slice($pagePosts, 0, 15) as $idx => $post) {
                        $postContent = [];
                        if (!empty($post['message'])) $postContent[] = "Post Message: {$post['message']}";
                        if (!empty($post['caption'])) $postContent[] = "Caption: {$post['caption']}";
                        if (!empty($post['description'])) $postContent[] = "Description: {$post['description']}";

                        if (!empty($post['attachments']['data'])) {
                            foreach ($post['attachments']['data'] as $att) {
                                if (!empty($att['title'])) $postContent[] = "Image Title: {$att['title']}";
                                if (!empty($att['description'])) $postContent[] = "Image Description: {$att['description']}";
                            }
                        }

                        if (!empty($postContent)) {
                            $prompt .= "- Post #" . ($idx + 1) . ": " . implode(' | ', $postContent) . "\n";
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to fetch connected Facebook Page data for prompt: ' . $e->getMessage());
            }
        }

        $knowledge = BusinessKnowledge::where('is_active', true)->get();
        if ($knowledge->isNotEmpty()) {
            $prompt .= "\nKnowledge Base:\n";
            foreach ($knowledge as $k) {
                $prompt .= "- {$k->title}: {$k->content}\n";
            }
        }

        $faqs = Faq::where('status', true)->get();
        if ($faqs->isNotEmpty()) {
            $prompt .= "\nFAQs:\n";
            foreach ($faqs as $f) {
                $prompt .= "Q: {$f->question}\nA: {$f->answer}\n";
            }
        }

        $products = Product::where('status', true)->get();
        if ($products->isNotEmpty()) {
            $prompt .= "\nProducts:\n";
            foreach ($products as $p) {
                $pr = $p->discount_price > 0 ? "৳{$p->discount_price}" : "৳{$p->price}";
                $prompt .= "- [ID: {$p->id}] {$p->name} ({$pr})\n";
            }
        }

        return $prompt;
    }

    private function tokenize(string $text): array
    {
        $parts = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_unique(array_filter($parts, fn ($w) => mb_strlen($w) >= 2)));
    }
}
