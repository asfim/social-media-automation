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
        
        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning rounded-3">{{ session('warning') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
        @endif

        <!-- Connection Status -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 {{ $account ? 'border-success' : 'border-danger' }}">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-3 rounded-circle">
                        <i class="fa-brands fa-facebook {{ $account ? 'text-primary' : 'text-muted' }} fs-3"></i>
                    </div>
                    <div>
                        @if($account)
                            <h5 class="fw-bold mb-1">Connected: {{ $account->account_name }}</h5>
                            <p class="text-muted small mb-0">Page ID {{ $account->account_id }}. AI auto-replies to Messenger messages and post comments.</p>
                        @else
                            <h5 class="fw-bold mb-1">Not Connected</h5>
                            <p class="text-muted small mb-0">Your AI cannot read or reply to Facebook messages yet.</p>
                        @endif
                    </div>
                </div>
                @if($account)
                    <div class="d-flex gap-2">
                        <form action="{{ route('settings.facebook.sync') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-rotate me-2"></i> Sync Page Data</button>
                        </form>
                        <form action="{{ route('settings.facebook.disconnect') }}" method="POST" onsubmit="return confirm('Disconnect this page? Auto-replies will stop.');">
                            @csrf
                            <button class="btn btn-outline-danger rounded-pill px-3"><i class="fa-solid fa-link-slash me-2"></i> Disconnect</button>
                        </form>
                    </div>
                @else
                    <a href="#credentialsForm" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-link me-2"></i> Connect Page</a>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold">Meta API Credentials</h5>
                <p class="text-muted small mt-1">Enter your Meta Developer app credentials below to enable webhooks and APIs.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('settings.facebook.save') }}" method="POST" id="credentialsForm" autocomplete="off">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Meta App ID <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" name="app_id" value="{{ old('app_id') }}" class="form-control rounded-3 font-monospace" placeholder="e.g. 102938475610293" autocomplete="off">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Meta App Secret <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="password" name="app_secret" class="form-control rounded-3 font-monospace" placeholder="••••••••••••••••••••••••••••••••" autocomplete="new-password">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Page Access Token <span class="text-danger">*</span></label>
                        <textarea name="access_token" class="form-control rounded-3 font-monospace" rows="3" placeholder="EAABwz..." required autocomplete="off"></textarea>
                        <div class="form-text">Generate a never-expiring token from the Graph API Explorer. The page is detected automatically from this token.</div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-satellite-dish me-2"></i> Your Webhook URL (Copy this)</label>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control bg-white" value="{{ url('/api/meta/webhook') }}" readonly id="webhookUrl">
                            <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhookUrl').value); alert('Copied!');"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <div class="form-text mt-2">Paste this URL into the Meta Developer Portal Webhooks section.</div>
                        
                        <label class="form-label fw-bold text-primary mt-3">Verify Token</label>
                        <input type="text" class="form-control bg-white font-monospace" value="{{ config('services.meta.webhook_token') }}" readonly>
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
