<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\SystemSetting;
use App\Services\Meta\MetaService;
use Illuminate\Http\Request;

class MetaSettingsController extends Controller
{
    /**
     * Facebook settings page with real connection status.
     */
    public function facebook()
    {
        $account = SocialAccount::where('platform', 'facebook')->where('is_active', true)->latest()->first();

        return view('settings.facebook', compact('account'));
    }

    /**
     * Validate the Page Access Token with Meta, store the page and subscribe it to webhooks.
     */
    public function saveFacebook(Request $request, MetaService $meta)
    {
        $data = $request->validate([
            'app_id' => 'nullable|string|max:100',
            'app_secret' => 'nullable|string|max:255',
            'access_token' => 'required|string',
        ]);

        $token = trim($data['access_token']);

        $page = $meta->me($token);
        if (!$page['ok'] || empty($page['data']['id'])) {
            return back()->withInput($request->except(['app_secret', 'access_token']))
                ->withErrors(['access_token' => 'Meta rejected this token: ' . ($page['error'] ?? 'unknown error')]);
        }

        $account = SocialAccount::updateOrCreate(
            ['account_id' => $page['data']['id']],
            [
                'platform' => 'facebook',
                'account_name' => $page['data']['name'] ?? 'Facebook Page',
                'access_token' => $token,
                'is_active' => true,
            ]
        );

        foreach (['app_id' => $data['app_id'] ?? null, 'app_secret' => $data['app_secret'] ?? null] as $key => $value) {
            if ($value) {
                SystemSetting::updateOrCreate(['key' => "meta_{$key}"], ['value' => $value, 'group' => 'meta']);
            }
        }

        $sub = $meta->subscribePage($account->account_id, $token);

        // Automatically sync Page Info, Posts, Images & Captions for AI auto-reply
        $meta->syncPageDataToDatabase($account);

        $message = "Connected to Facebook Page: {$account->account_name}. Page posts, images, captions & info synced for AI Auto-Reply.";
        if (!$sub['ok']) {
            return back()->with('success', $message)
                ->with('warning', 'Could not auto-subscribe the page to webhooks (' . $sub['error'] . '). Subscribe it manually in the Meta Developer Portal.');
        }

        return back()->with('success', $message . ' Webhook subscription is active.');
    }

    /**
     * Disconnect the page (stops auto-replies).
     */
    public function disconnectFacebook()
    {
        SocialAccount::where('platform', 'facebook')->update(['is_active' => false]);

        return back()->with('success', 'Facebook page disconnected.');
    }

    /**
     * Manually Sync Facebook Page Posts, Captions & Info
     */
    public function syncFacebook(MetaService $meta)
    {
        $account = SocialAccount::where('platform', 'facebook')->where('is_active', true)->latest()->first();

        if (!$account) {
            return back()->withErrors(['account' => 'No active Facebook page connected.']);
        }

        $meta->syncPageDataToDatabase($account);

        return back()->with('success', 'Facebook Page posts, captions, images & info synced successfully! AI will now use your latest Facebook posts.');
    }
}
