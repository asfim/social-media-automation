<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessSetting;
use App\Models\BusinessKnowledge;
use App\Models\SocialAccount;

class MetaService
{
    /**
     * Verify incoming webhook from Meta
     */
    public function verifyWebhook(array $requestData): ?string
    {
        $hubMode = $requestData['hub_mode'] ?? ($requestData['hub.mode'] ?? null);
        $hubVerifyToken = $requestData['hub_verify_token'] ?? ($requestData['hub.verify_token'] ?? null);
        $hubChallenge = $requestData['hub_challenge'] ?? ($requestData['hub.challenge'] ?? null);

        $validToken = config('services.meta.webhook_token');

        if ($hubMode === 'subscribe' && $hubVerifyToken === $validToken) {
            return $hubChallenge;
        }

        return null;
    }

    /**
     * Store incoming event for processing
     */
    public function storeEvent(array $payload, string $platform)
    {
        return \App\Models\WebhookEvent::create([
            'platform' => $platform,
            'event_type' => $payload['object'] ?? 'unknown',
            'payload' => $payload,
            'status' => 'pending'
        ]);
    }

    protected function graph(string $path): string
    {
        return 'https://graph.facebook.com/' . config('services.meta.graph_version', 'v21.0') . '/' . ltrim($path, '/');
    }

    /**
     * Normalise a Graph API response into ['ok' => bool, 'error' => ?string, 'data' => array]
     */
    protected function result($response): array
    {
        $ok = $response->successful();
        if (!$ok) {
            Log::warning('Meta Graph API error', ['status' => $response->status(), 'body' => $response->json()]);
        }

        return [
            'ok' => $ok,
            'error' => $ok ? null : ($response->json('error.message') ?? 'Meta API error'),
            'data' => $response->json() ?? [],
        ];
    }

    /**
     * Identify the Page that owns a Page Access Token.
     */
    public function me(string $token): array
    {
        $res = $this->result(Http::timeout(15)->withToken($token)->get($this->graph('me'), ['fields' => 'id,name']));
        
        if (!$res['ok']) {
            $debugRes = Http::timeout(15)->get($this->graph('debug_token'), [
                'input_token' => $token,
                'access_token' => $token
            ])->json();
            
            if (isset($debugRes['data']['profile_id'])) {
                return [
                    'ok' => true,
                    'error' => null,
                    'data' => [
                        'id' => $debugRes['data']['profile_id'],
                        'name' => 'Facebook Page'
                    ]
                ];
            }
        }
        
        return $res;
    }

    /**
     * Subscribe the Page to this app's webhook so messages and comments are delivered.
     */
    public function subscribePage(string $pageId, string $token): array
    {
        return $this->result(Http::timeout(15)->withToken($token)->asForm()->post($this->graph("{$pageId}/subscribed_apps"), [
            'subscribed_fields' => 'messages,messaging_postbacks,feed',
        ]));
    }

    /**
     * Fetch connected Facebook Page details (about, description, contact, address).
     */
    public function fetchPageDetails(string $pageId, string $token): array
    {
        return \Cache::remember("fb_page_details_{$pageId}", 1800, function () use ($pageId, $token) {
            $res = $this->result(Http::timeout(10)->withToken($token)->get($this->graph($pageId), [
                'fields' => 'id,name,about,description,emails,phone,single_line_address,website'
            ]));
            return $res['ok'] ? $res['data'] : [];
        });
    }

    /**
     * Fetch connected Facebook Page recent posts with captions and image attachments.
     */
    public function fetchPagePosts(string $pageId, string $token): array
    {
        return \Cache::remember("fb_page_posts_{$pageId}", 900, function () use ($pageId, $token) {
            $res = $this->result(Http::timeout(12)->withToken($token)->get($this->graph("{$pageId}/published_posts"), [
                'fields' => 'message,caption,description,created_time,attachments{media,title,description}',
                'limit' => 20,
            ]));
            return $res['ok'] ? ($res['data']['data'] ?? []) : [];
        });
    }

    /**
     * Auto Sync Page Info, Posts, Images & Captions to Database so AI can answer customer questions.
     */
    public function syncPageDataToDatabase(SocialAccount $account): void
    {
        if (empty($account->account_id) || empty($account->access_token)) return;

        try {
            // 1. Sync Page Profile Info to BusinessSettings
            $details = $this->fetchPageDetails($account->account_id, $account->access_token);
            if (!empty($details)) {
                $setting = BusinessSetting::first() ?? new BusinessSetting();
                $setting->business_name = $details['name'] ?? $account->account_name;
                if (!empty($details['about'])) $setting->business_description = $details['about'];
                if (!empty($details['description'])) $setting->business_description = $details['description'];
                if (!empty($details['single_line_address'])) $setting->business_location = $details['single_line_address'];
                if (!empty($details['phone'])) $setting->phone = $details['phone'];
                if (!empty($details['emails'][0])) $setting->email = $details['emails'][0];
                if (!empty($details['website'])) $setting->website = $details['website'];
                $setting->save();
            }

            // 2. Sync Page Posts & Image Captions to BusinessKnowledge
            $posts = $this->fetchPagePosts($account->account_id, $account->access_token);
            foreach ($posts as $idx => $post) {
                $contentParts = [];
                if (!empty($post['message'])) $contentParts[] = $post['message'];
                if (!empty($post['caption'])) $contentParts[] = "Caption: " . $post['caption'];
                if (!empty($post['description'])) $contentParts[] = "Description: " . $post['description'];

                if (!empty($post['attachments']['data'])) {
                    foreach ($post['attachments']['data'] as $att) {
                        if (!empty($att['title'])) $contentParts[] = "Image Title: " . $att['title'];
                        if (!empty($att['description'])) $contentParts[] = "Image Caption: " . $att['description'];
                    }
                }

                if (!empty($contentParts)) {
                    $title = "Facebook Page Post #" . ($idx + 1);
                    $fullContent = implode("\n", array_unique($contentParts));

                    BusinessKnowledge::updateOrCreate(
                        ['title' => $title],
                        [
                            'category' => 'facebook_post',
                            'content' => $fullContent,
                            'is_active' => true,
                        ]
                    );
                }
            }
            Log::info("Synced Facebook Page information and posts for account {$account->account_name}");
        } catch (\Throwable $e) {
            Log::warning("Failed to sync Facebook Page data: " . $e->getMessage());
        }
    }

    /**
     * Fetch a Messenger user's name and avatar.
     */
    public function fetchProfile(string $token, string $psid): array
    {
        $res = $this->result(Http::timeout(15)->withToken($token)->get($this->graph($psid), ['fields' => 'name,profile_pic']));

        return $res['ok'] ? $res['data'] : [];
    }

    /**
     * Send a Messenger text message.
     */
    public function sendText(string $token, string $recipientId, string $text): array
    {
        return $this->result(Http::timeout(20)->withToken($token)->post($this->graph('me/messages'), [
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $text],
            'messaging_type' => 'RESPONSE',
        ]));
    }

    /**
     * Reply publicly to a Page post comment.
     */
    public function replyToComment(string $token, string $commentId, string $text): array
    {
        return $this->result(Http::timeout(20)->withToken($token)->asForm()->post($this->graph("{$commentId}/comments"), [
            'message' => $text,
        ]));
    }

    /**
     * Send a private Messenger message to a user who commented on a Page post.
     */
    public function sendPrivateReplyToComment(string $token, string $commentId, string $text): array
    {
        return $this->result(Http::timeout(20)->withToken($token)->post($this->graph('me/messages'), [
            'recipient' => ['comment_id' => $commentId],
            'message' => ['text' => $text],
        ]));
    }
}
