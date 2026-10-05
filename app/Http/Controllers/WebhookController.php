<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Meta\MetaService;

class WebhookController extends Controller
{
    protected $metaService;

    public function __construct(MetaService $metaService)
    {
        $this->metaService = $metaService;
    }

    /**
     * Verify Webhook (Required by Meta/Facebook)
     */
    public function verify(Request $request)
    {
        $challenge = $this->metaService->verifyWebhook($request->all());

        if ($challenge) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Receive incoming messages and events from Meta
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Ensure this is a page event
        if (isset($payload['object']) && $payload['object'] === 'page') {
            
            // Fast acknowledge to Meta so they don't timeout (200 OK must be sent within 20 secs)
            // The actual processing is deferred to the MetaService/Queue
            $this->metaService->storeEvent($payload, 'facebook');
            
            return response('EVENT_RECEIVED', 200);
        }

        return response('Not Found', 404);
    }
}
