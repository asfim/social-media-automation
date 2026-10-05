@extends('layouts.app')

@section('title', 'Auto Reply Rules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Auto Reply Rules</h3>
        <p class="text-muted mb-0">Configure specific templates for known AI Intents.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addRuleModal">
        <i class="fa-solid fa-plus me-2"></i> Create Rule
    </button>
</div>

<div class="row">
    <!-- Pricing Intent -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-light text-primary border mb-2">Intent: Pricing Inquiry</span>
                        <h5 class="fw-bold mb-0">Pricing & Packages</h5>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="bg-light p-3 rounded-3 mb-3">
                    <p class="mb-0 small fst-italic">"Hi {{ '{customer_name}' }}! Our basic package starts at $99/mo. Would you like a detailed PDF brochure?"</p>
                </div>
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span>Used 1,204 times</span>
                    <div>
                        <button class="btn btn-sm btn-link text-primary p-0 me-2"><i class="fa-solid fa-pen"></i> Edit</button>
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Greeting Intent -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-light text-success border mb-2">Intent: Greeting</span>
                        <h5 class="fw-bold mb-0">Welcome Message</h5>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="bg-light p-3 rounded-3 mb-3">
                    <p class="mb-0 small fst-italic">"Hello there! Welcome to Atomation. How can our AI assistant help you today?"</p>
                </div>
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span>Used 5,420 times</span>
                    <div>
                        <button class="btn btn-sm btn-link text-primary p-0 me-2"><i class="fa-solid fa-pen"></i> Edit</button>
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
