@extends('layouts.app')

@section('title', 'Products & Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Products & Services Catalog</h3>
        <p class="text-muted mb-0">If the AI knows your pricing and stock, it can sell on your behalf.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i> Add Item
    </button>
</div>

<div class="row">
    <!-- Item 1 -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="bg-light text-center p-4 border-bottom">
                <i class="fa-solid fa-laptop-code text-primary" style="font-size: 4rem;"></i>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0">eCommerce Website</h5>
                    <span class="badge bg-success rounded-pill">Available</span>
                </div>
                <h4 class="text-primary fw-bold mb-3">৳20,000</h4>
                <p class="text-muted small mb-4">Complete Laravel based eCommerce website with bKash/Nagad integration and admin panel.</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary rounded-pill">Edit Details</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Item 2 -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="bg-light text-center p-4 border-bottom">
                <i class="fa-solid fa-bullhorn text-danger" style="font-size: 4rem;"></i>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0">Social Media Marketing</h5>
                    <span class="badge bg-success rounded-pill">Available</span>
                </div>
                <h4 class="text-primary fw-bold mb-3">৳15,000 <small class="text-muted fs-6 fw-normal">/ month</small></h4>
                <p class="text-muted small mb-4">Monthly retainer for managing Facebook and Instagram pages including 15 posts and ad management.</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary rounded-pill">Edit Details</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
