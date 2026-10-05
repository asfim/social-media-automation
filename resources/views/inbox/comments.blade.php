@extends('layouts.app')

@section('title', 'Social Comments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Social Comments</h3>
    <div>
        <select class="form-select rounded-pill px-4 d-inline-block w-auto me-2">
            <option>All Platforms</option>
            <option>Facebook</option>
            <option>Instagram</option>
        </select>
        <button class="btn btn-outline-primary rounded-pill px-3">
            <i class="fa-solid fa-filter me-1"></i> Filter
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold mb-0">Recent Posts</h6>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action active p-3 border-0">
                        <div class="d-flex gap-3">
                            <img src="https://via.placeholder.com/60" class="rounded" alt="Post">
                            <div>
                                <small class="text-white-50"><i class="fa-brands fa-facebook"></i> Today, 10:00 AM</small>
                                <div class="fw-bold text-truncate" style="max-width: 200px;">New Summer Collection 2026</div>
                                <small class="badge bg-light text-primary mt-1">45 Comments</small>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3 border-0">
                        <div class="d-flex gap-3">
                            <img src="https://via.placeholder.com/60" class="rounded" alt="Post">
                            <div>
                                <small class="text-muted"><i class="fa-brands fa-instagram"></i> Yesterday</small>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">50% Discount Offer!</div>
                                <small class="badge bg-light text-dark mt-1">12 Comments</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Comments on "New Summer Collection..."</h6>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="aiReplyToggle" checked>
                    <label class="form-check-label small" for="aiReplyToggle">AI Auto-Reply Active</label>
                </div>
            </div>
            
            <div class="card-body p-0">
                <!-- Comment 1 -->
                <div class="p-4 border-bottom">
                    <div class="d-flex gap-3">
                        <img src="https://ui-avatars.com/api/?name=Farhan+Ali" class="rounded-circle" width="40" height="40">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">Farhan Ali</span>
                                <small class="text-muted">10 mins ago</small>
                            </div>
                            <p class="mb-2">Price please?</p>
                            
                            <!-- AI Reply -->
                            <div class="bg-light p-3 rounded-3 mb-3 border border-primary-subtle">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold text-primary"><i class="fa-solid fa-robot"></i> AI Reply</small>
                                    <small class="text-muted">Just now</small>
                                </div>
                                <p class="mb-0 small">Hello Farhan! The price for this collection starts at 1,500 BDT. I have also sent you a DM with the full catalog!</p>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill">Reply as Page</button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill">Hide Comment</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comment 2 -->
                <div class="p-4">
                    <div class="d-flex gap-3">
                        <img src="https://ui-avatars.com/api/?name=Sadia+Hossain" class="rounded-circle" width="40" height="40">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">Sadia Hossain</span>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                            <p class="mb-3">Do you deliver outside Dhaka?</p>
                            
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm rounded-pill" placeholder="Write a reply...">
                                <button class="btn btn-sm btn-primary rounded-pill px-3">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
