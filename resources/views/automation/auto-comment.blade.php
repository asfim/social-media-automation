@extends('layouts.app')

@section('title', 'Auto Comment Rules')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Auto Comment Rules</h3>
                <p class="text-muted mb-0">Configure how the AI automatically responds to comments on your posts.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
                    <div>
                        <h6 class="fw-bold mb-1"><i class="fa-solid fa-reply text-primary me-2"></i> Auto-Reply to all new comments</h6>
                        <small class="text-muted">The AI will read every new comment and write a contextual reply automatically.</small>
                    </div>
                    <div class="form-check form-switch fs-4">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3">Fallback Generic Comments</h6>
                <p class="text-muted small mb-3">If the AI is unsure how to reply to a comment (e.g., someone just commented "Wow!"), it will use one of these generic templates:</p>
                
                <div class="list-group mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="fst-italic">"Thank you for your lovely comment!"</span>
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i></button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="fst-italic">"We appreciate your support! Please inbox us if you need help."</span>
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i></button>
                    </div>
                    <div class="list-group-item bg-light p-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Add a new fallback generic comment...">
                            <button class="btn btn-primary px-3">Add</button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-3 bg-light border border-primary-subtle rounded-3">
                    <div>
                        <h6 class="fw-bold text-primary mb-1"><i class="fa-solid fa-bolt me-2"></i> Private Reply to Leads</h6>
                        <small class="text-muted">If a comment asks for "price" or "details", auto-send a direct message (DM) as well.</small>
                    </div>
                    <div class="form-check form-switch fs-4">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
