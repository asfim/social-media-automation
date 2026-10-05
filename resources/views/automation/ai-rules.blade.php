@extends('layouts.app')

@section('title', 'AI Response Rules')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">AI Engine Rules</h3>
                <p class="text-muted mb-0">Control the boundaries and confidence thresholds of your AI Assistant.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <div class="mb-5">
                    <h6 class="fw-bold mb-3">AI Confidence Threshold</h6>
                    <p class="text-muted small mb-3">If the AI's confidence in its answer falls below this percentage, it will NOT reply and will instead flag the conversation for Human Intervention.</p>
                    
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-danger fw-bold small">0%</span>
                        <input type="range" class="form-range flex-grow-1" min="0" max="100" value="70" id="confidenceRange">
                        <span class="text-success fw-bold small">100%</span>
                    </div>
                    <div class="text-center mt-2 fw-bold text-primary fs-5" id="confidenceValue">70% Minimum Confidence</div>
                </div>
                
                <hr class="mb-4">
                
                <h6 class="fw-bold mb-4">AI Boundaries</h6>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-medium mb-1">Prevent Price Inventing (Hallucination)</h6>
                        <small class="text-muted">Strictly forces the AI to only quote prices that exist in your Products & Services database.</small>
                    </div>
                    <div class="form-check form-switch fs-4">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-medium mb-1">Human Handoff Trigger</h6>
                        <small class="text-muted">Automatically stop the AI and notify you if the customer seems angry or specifically requests a human.</small>
                    </div>
                    <div class="form-check form-switch fs-4">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-medium mb-1">Delay AI Responses</h6>
                        <small class="text-muted">Wait for a few seconds before replying so it feels more human.</small>
                    </div>
                    <select class="form-select w-auto">
                        <option>No Delay (Instant)</option>
                        <option selected>3 Seconds</option>
                        <option>10 Seconds</option>
                        <option>30 Seconds</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-end mt-5">
                    <button class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Save AI Engine Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('confidenceRange').addEventListener('input', function(e) {
        document.getElementById('confidenceValue').innerText = e.target.value + '% Minimum Confidence';
    });
</script>
@endpush
@endsection
