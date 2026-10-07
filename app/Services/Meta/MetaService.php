<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaService
{
    /**
     * Verify incoming webhook from Meta
     */
    public function verifyWebhook(array $requestData): ?string
    {
        // PHP converts "hub.mode" into "hub_mode" in request data
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
        
        // If GET /me fails (e.g. missing pages_read_engagement), fallback to debug_token
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
        return \Cache::remember("fb_page_details_{$pageId}", 3600, function () use ($pageId, $token) {
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
        return \Cache::remember("fb_page_posts_{$pageId}", 1800, function () use ($pageId, $token) {
            $res = $this->result(Http::timeout(12)->withToken($token)->get($this->graph("{$pageId}/published_posts"), [
                'fields' => 'message,caption,description,created_time,attachments{media,title,description}',
                'limit' => 15,
            ]));
            return $res['ok'] ? ($res['data']['data'] ?? []) : [];
        });
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
}
