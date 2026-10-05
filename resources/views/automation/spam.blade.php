@extends('layouts.app')

@section('title', 'Spam Protection')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-shield-halved text-danger me-2"></i> Spam Protection Settings</h5>
                <p class="text-muted small mt-1">Automatically hide abusive comments and ban spammers from your social pages.</p>
            </div>
            
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 mb-4 border">
                    <div>
                        <h6 class="fw-bold mb-1">Master Toggle</h6>
                        <small class="text-muted">Enable or disable all automated spam protection.</small>
                    </div>
                    <div class="form-check form-switch fs-4">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3">Protection Rules</h6>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="hideLinks" checked>
                    <label class="form-check-label" for="hideLinks"><strong>Hide Links:</strong> Automatically hide comments containing URLs.</label>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="hideProfanity" checked>
                    <label class="form-check-label" for="hideProfanity"><strong>Profanity Filter:</strong> Hide comments containing swear words or abusive language.</label>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Custom Blocklisted Keywords (Comma separated)</label>
                    <textarea class="form-control rounded-3" rows="3">scam, fake, worst, cheat, fraud, https://, http://</textarea>
                    <div class="form-text">Any comment containing these words will be automatically hidden from the public view.</div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button class="btn btn-danger rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save Spam Rules</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
