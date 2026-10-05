@extends('layouts.app')

@section('title', 'Facebook Integration')

@section('content')
<div class="row">
    <!-- Sidebar Settings Navigation -->
    <div class="col-md-3 mb-4">
        @include('settings._nav', ['active' => 'facebook'])
    </div>
    
    <!-- Main Settings Form -->
    <div class="col-md-9">
        
        <!-- Connection Status -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-danger">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-3 rounded-circle">
                        <i class="fa-brands fa-facebook text-muted fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Not Connected</h5>
                        <p class="text-muted small mb-0">Your AI cannot read or reply to Facebook messages yet.</p>
                    </div>
                </div>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#instructionsModal">
                    <i class="fa-solid fa-link me-2"></i> Connect Page
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold">Meta API Credentials</h5>
                <p class="text-muted small mt-1">Enter your Meta Developer app credentials below to enable webhooks and APIs.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="#" method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Meta App ID</label>
                        <input type="text" class="form-control rounded-3 font-monospace" placeholder="e.g. 102938475610293">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Meta App Secret</label>
                        <div class="input-group">
                            <input type="password" class="form-control rounded-3 font-monospace" placeholder="••••••••••••••••••••••••••••••••">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Page Access Token</label>
                        <textarea class="form-control rounded-3 font-monospace" rows="3" placeholder="EAABwz..."></textarea>
                        <div class="form-text">Generate a never-expiring token from the Graph API Explorer.</div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-satellite-dish me-2"></i> Your Webhook URL (Copy this)</label>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control bg-white" value="{{ url('/api/meta/webhook') }}" readonly id="webhookUrl">
                            <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhookUrl').value); alert('Copied!');"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <div class="form-text mt-2">Paste this URL into the Meta Developer Portal Webhooks section.</div>
                        
                        <label class="form-label fw-bold text-primary mt-3">Verify Token</label>
                        <input type="text" class="form-control bg-white font-monospace" value="atomation_secure_webhook_2026" readonly>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save Credentials</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
