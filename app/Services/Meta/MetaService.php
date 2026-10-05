<?php

namespace App\Services\Meta;

use App\Models\FacebookPage;
use App\Models\InstagramAccount;

class MetaService
{
    /**
     * Verify incoming webhook from Meta
     */
    public function verifyWebhook(array $requestData): ?string
    {
        $hubMode = $requestData['hub_mode'] ?? null;
        $hubVerifyToken = $requestData['hub_verify_token'] ?? null;
        $hubChallenge = $requestData['hub_challenge'] ?? null;

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
        // This will be processed by a queue job later
        return \App\Models\WebhookEvent::create([
            'platform' => $platform,
            'event_type' => $payload['object'] ?? 'unknown',
            'payload' => $payload,
            'status' => 'pending'
        ]);
    }
}
