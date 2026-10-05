@extends('layouts.app')

@section('title', 'WhatsApp Integration')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        @include('settings._nav', ['active' => 'whatsapp'])
    </div>

    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-success">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="bg-light p-3 rounded-circle">
                    <i class="fa-brands fa-whatsapp text-success fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Not Connected</h5>
                    <p class="text-muted small mb-0">Your AI cannot read or reply to WhatsApp messages yet.</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold">WhatsApp Cloud API Credentials</h5>
                <p class="text-muted small mt-1">From Meta Developer Portal &rarr; your app &rarr; <strong>WhatsApp &rarr; API Setup</strong>.</p>
            </div>
            <div class="card-body p-4">
                <form action="#" method="POST" autocomplete="off">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Phone Number ID</label>
                        <input type="text" name="whatsapp_phone_number_id" class="form-control rounded-3 font-monospace" placeholder="e.g. 109876543210987" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">WhatsApp Business Account ID</label>
                        <input type="text" name="whatsapp_business_account_id" class="form-control rounded-3 font-monospace" placeholder="e.g. 123456789012345" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Permanent Access Token</label>
                        <textarea name="whatsapp_access_token" class="form-control rounded-3 font-monospace" rows="3" placeholder="EAABwz..." autocomplete="off"></textarea>
                        <div class="form-text">The API Setup page token expires in 24 hours. For a permanent one, create a <strong>System User</strong> in Business Settings and generate a token with <code>whatsapp_business_messaging</code> and <code>whatsapp_business_management</code>.</div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-satellite-dish me-2"></i> Webhook URL</label>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control bg-white" value="{{ url('/api/meta/webhook') }}" readonly id="waWebhookUrl">
                            <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('waWebhookUrl').value); alert('Copied!');"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <div class="form-text mt-2">WhatsApp &rarr; Configuration &rarr; Webhook &rarr; subscribe to the <code>messages</code> field.</div>

                        <label class="form-label fw-bold text-primary mt-3">Verify Token</label>
                        <input type="text" class="form-control bg-white font-monospace" value="{{ config('services.meta.webhook_token') }}" readonly>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save Credentials</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="alert alert-info rounded-4 border-0">
            <i class="fa-solid fa-circle-info me-2"></i>
            WhatsApp lets you reply freely only within <strong>24 hours</strong> of the customer's last message. After that you must use an approved message template.
        </div>
    </div>
</div>
@endsection
