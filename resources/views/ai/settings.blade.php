@extends('layouts.app')

@section('title', 'AI Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-microchip text-primary me-2"></i> Large Language Model (LLM) Settings</h5>
                <p class="text-muted small mt-1">Select the brain powering your AI Assistant.</p>
            </div>
            
            <div class="card-body p-4">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Active AI Model</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="w-100">
                                <input type="radio" name="ai_model" class="btn-check" checked>
                                <div class="btn btn-outline-primary w-100 p-3 rounded-4 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold">OpenAI GPT-4o</span>
                                        <i class="fa-solid fa-circle-check text-primary fs-5"></i>
                                    </div>
                                    <small class="text-muted d-block">Best for general chat and intelligence. (Recommended)</small>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="w-100">
                                <input type="radio" name="ai_model" class="btn-check">
                                <div class="btn btn-outline-secondary w-100 p-3 rounded-4 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark">Anthropic Claude 3.5 Sonnet</span>
                                    </div>
                                    <small class="text-muted d-block">Excellent for following strict boundaries and long contexts.</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">OpenAI API Key</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control font-monospace" value="sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                        <button class="btn btn-outline-secondary" type="button"><i class="fa-regular fa-eye"></i></button>
                    </div>
                    <div class="form-text">Your API key is securely encrypted in the database.</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Max Tokens (Response Length)</label>
                    <select class="form-select rounded-3">
                        <option>150 Tokens (Short replies)</option>
                        <option selected>300 Tokens (Medium replies)</option>
                        <option>500 Tokens (Long explanations)</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-end mt-5">
                    <button class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save AI Engine Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
