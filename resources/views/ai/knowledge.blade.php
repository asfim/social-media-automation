@extends('layouts.app')

@section('title', 'Business Knowledge Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Business Knowledge</h3>
        <p class="text-muted mb-0">Feed the AI with your company's documents, policies, and history.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i> Add Document
    </button>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Document Title</th>
                                <th class="py-3">Type</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">Company Return Policy 2026</div>
                                    <small class="text-muted">Added 2 days ago</small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><i class="fa-regular fa-file-pdf text-danger me-1"></i> PDF Document</span></td>
                                <td><span class="badge bg-success rounded-pill px-3">Trained</span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light rounded-circle text-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">About Us & Company History</div>
                                    <small class="text-muted">Added today</small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><i class="fa-solid fa-align-left text-primary me-1"></i> Text Content</span></td>
                                <td><span class="badge bg-warning text-dark rounded-pill px-3"><i class="fa-solid fa-spinner fa-spin me-1"></i> Training...</span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light rounded-circle text-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-brain" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Vector Database Status</h5>
                <p class="small text-white-50 mb-4">The AI converts your text into vectors to instantly retrieve answers.</p>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Documents Trained</span>
                    <span class="fw-bold">12</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vector Embeddings</span>
                    <span class="fw-bold">1,452</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Storage Used</span>
                    <span class="fw-bold">4.2 MB</span>
                </div>
                
                <button class="btn btn-light w-100 rounded-pill fw-bold text-primary">
                    <i class="fa-solid fa-arrows-rotate me-2"></i> Retrain Entire Model
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
