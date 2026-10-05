@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Frequently Asked Questions</h3>
        <p class="text-muted mb-0">Add direct Q&A pairs for the most common customer queries.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i> Add New FAQ
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="accordion" id="faqAccordion">
            
            <div class="accordion-item border-0 mb-3 bg-light rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button rounded-3 fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        Q: Do you offer discounts or EMI for web development?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body pt-0 pb-4">
                        <p class="mb-3 text-muted">A: Yes, we offer up to 3 months of 0% EMI on credit cards from selected banks. We also offer a flat 10% discount on upfront full payments.</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white text-primary border">Category: Pricing</span>
                            <span class="badge bg-white text-success border">Status: Active</span>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Edit</button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 mb-3 bg-light rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed rounded-3 fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        Q: Where is your office located? Can I meet you in person?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body pt-0 pb-4">
                        <p class="mb-3 text-muted">A: Our physical office is located at Banani, Road 11, Dhaka. You are always welcome to visit us from Sunday to Thursday between 10 AM and 6 PM.</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white text-primary border">Category: General Info</span>
                            <span class="badge bg-white text-success border">Status: Active</span>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Edit</button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
