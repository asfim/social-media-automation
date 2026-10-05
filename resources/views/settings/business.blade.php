@extends('layouts.app')

@section('title', 'Business Profile Settings')

@section('content')
<div class="row">
    <!-- Sidebar Settings Navigation -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="list-group list-group-flush rounded-4">
                    <a href="{{ route('settings.business') }}" class="list-group-item list-group-item-action active border-0 px-4 py-3">
                        <i class="fa-solid fa-building me-2"></i> Business Profile
                    </a>
                    <a href="{{ route('settings.facebook') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                        <i class="fa-brands fa-facebook me-2 text-primary"></i> Facebook Integration
                    </a>
                    <a href="{{ route('settings.instagram') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                        <i class="fa-brands fa-instagram me-2 text-danger"></i> Instagram Integration
                    </a>
                    <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 rounded-bottom-4">
                        <i class="fa-solid fa-sliders me-2"></i> System Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Settings Form -->
    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="mb-0 fw-bold">Business Profile Information</h5>
                <p class="text-muted small mt-1">This information is used by the AI to answer customer questions accurately.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="#" method="POST">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Business Name</label>
                            <input type="text" class="form-control rounded-3" value="Atomation Digital" placeholder="e.g. Acme Corp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Industry / Niche</label>
                            <input type="text" class="form-control rounded-3" value="IT Services & Digital Marketing">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Business Description (For AI Context)</label>
                        <textarea class="form-control rounded-3" rows="4">We are a premium digital agency providing web development, social media management, and custom AI chatbot solutions for businesses in Bangladesh.</textarea>
                        <div class="form-text">The AI will read this to understand exactly what your business does.</div>
                    </div>
                    
                    <h6 class="fw-bold mt-5 mb-3 border-bottom pb-2">AI Persona & Tone</h6>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">AI Agent Name</label>
                            <input type="text" class="form-control rounded-3" value="Atom Assistant">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Primary Language</label>
                            <select class="form-select rounded-3">
                                <option>English</option>
                                <option selected>Bengali (Bangla)</option>
                                <option>Banglish (Romanized Bengali)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Response Tone</label>
                        <select class="form-select rounded-3">
                            <option>Professional & Corporate</option>
                            <option selected>Friendly & Helpful</option>
                            <option>Casual & Enthusiastic</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
