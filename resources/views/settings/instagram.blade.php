@extends('layouts.app')

@section('title', 'Instagram Integration')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        @include('settings._nav', ['active' => 'instagram'])
    </div>

    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-danger">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="bg-light p-3 rounded-circle">
                    <i class="fa-brands fa-instagram text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Not Connected</h5>
                    <p class="text-muted small mb-0">Your AI cannot read or reply to Instagram DMs yet.</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold">Instagram API Credentials</h5>
                <p class="text-muted small mt-1">Instagram uses the same Meta app and Page Access Token as Facebook. Your Instagram account must be a <strong>Professional</strong> account linked to your Facebook Page.</p>
            </div>
            <div class="card-body p-4">
                <form action="#" method="POST" autocomplete="off">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Instagram Business Account ID</label>
                        <input type="text" name="instagram_account_id" class="form-control rounded-3 font-monospace" placeholder="e.g. 17841400000000000" autocomplete="off">
                        <div class="form-text">Graph API Explorer: <code>GET /{page-id}?fields=instagram_business_account</code></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Access Token</label>
                        <textarea name="instagram_access_token" class="form-control rounded-3 font-monospace" rows="3" placeholder="EAABwz..." autocomplete="off"></textarea>
                        <div class="form-text">Use the same never-expiring Page Access Token from the Facebook page.</div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-satellite-dish me-2"></i> Webhook URL</label>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control bg-white" value="{{ url('/api/meta/webhook') }}" readonly id="igWebhookUrl">
                            <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('igWebhookUrl').value); alert('Copied!');"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <div class="form-text mt-2">Meta Developer Portal &rarr; Webhooks &rarr; select <strong>Instagram</strong> &rarr; subscribe to <code>messages</code> and <code>comments</code>.</div>

                        <label class="form-label fw-bold text-primary mt-3">Verify Token</label>
                        <input type="text" class="form-control bg-white font-monospace" value="{{ config('services.meta.webhook_token') }}" readonly>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save Credentials</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Required permissions</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border">instagram_basic</span>
                    <span class="badge bg-light text-dark border">instagram_manage_messages</span>
                    <span class="badge bg-light text-dark border">instagram_manage_comments</span>
                    <span class="badge bg-light text-dark border">pages_show_list</span>
                    <span class="badge bg-light text-dark border">pages_manage_metadata</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
