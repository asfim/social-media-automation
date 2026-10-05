@extends('layouts.app')

@section('title', 'Sales Pipeline')

@push('styles')
<style>
    .kanban-board {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 20px;
        min-height: calc(100vh - 160px);
    }
    
    .kanban-column {
        min-width: 300px;
        max-width: 300px;
        background: #F4F5F7;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }
    
    .kanban-header {
        padding: 15px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid transparent;
    }
    
    .col-new { border-bottom-color: var(--primary); }
    .col-contacted { border-bottom-color: #0dcaf0; }
    .col-interested { border-bottom-color: #ffc107; }
    .col-negotiation { border-bottom-color: #fd7e14; }
    .col-won { border-bottom-color: #198754; }
    
    .kanban-cards {
        padding: 10px;
        flex-grow: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .kanban-card {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        cursor: grab;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .kanban-card:hover {
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .card-hot { border-left-color: #dc3545; }
    .card-warm { border-left-color: #ffc107; }
    .card-cold { border-left-color: #6c757d; }
    
    .card-title {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.95rem;
    }
    
    .card-service {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    
    .card-footer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        border-top: 1px solid var(--border-color);
        padding-top: 10px;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Sales Pipeline</h3>
    <div>
        <button class="btn btn-outline-secondary rounded-pill px-3 me-2">
            <i class="fa-solid fa-filter me-1"></i> Filter
        </button>
        <button class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-plus me-2"></i> Add Deal
        </button>
    </div>
</div>

<div class="kanban-board">
    
    <!-- Column: New -->
    <div class="kanban-column">
        <div class="kanban-header col-new">
            <span>New Leads</span>
            <span class="badge bg-secondary rounded-pill">2</span>
        </div>
        <div class="kanban-cards">
            <div class="kanban-card card-warm">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge bg-soft-warning text-dark" style="font-size: 0.65rem;">Score: 60</span>
                    <i class="fa-brands fa-facebook text-primary"></i>
                </div>
                <div class="card-title">Abdur Rahman</div>
                <div class="card-service">Digital Marketing Package</div>
                <div class="card-footer-info">
                    <span class="text-muted"><i class="fa-regular fa-clock me-1"></i> 2h ago</span>
                    <img src="https://ui-avatars.com/api/?name=Abdur+Rahman&background=random" class="rounded-circle" width="24" height="24">
                </div>
            </div>
            
            <div class="kanban-card card-cold">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge bg-soft-secondary text-dark" style="font-size: 0.65rem;">Score: 30</span>
                    <i class="fa-brands fa-instagram text-danger"></i>
                </div>
                <div class="card-title">Sadia Islam</div>
                <div class="card-service">General Inquiry</div>
                <div class="card-footer-info">
                    <span class="text-muted"><i class="fa-regular fa-clock me-1"></i> 5h ago</span>
                    <img src="https://ui-avatars.com/api/?name=Sadia+Islam&background=random" class="rounded-circle" width="24" height="24">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Column: Contacted -->
    <div class="kanban-column">
        <div class="kanban-header col-contacted">
            <span>Contacted</span>
            <span class="badge bg-secondary rounded-pill">0</span>
        </div>
        <div class="kanban-cards">
            <!-- Empty state -->
            <div class="text-center p-4 text-muted small border border-dashed rounded mt-2">
                No leads here yet
            </div>
        </div>
    </div>
    
    <!-- Column: Interested -->
    <div class="kanban-column">
        <div class="kanban-header col-interested">
            <span>Interested (Hot)</span>
            <span class="badge bg-secondary rounded-pill">1</span>
        </div>
        <div class="kanban-cards">
            <div class="kanban-card card-hot">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge bg-danger" style="font-size: 0.65rem;">Score: 95</span>
                    <i class="fa-brands fa-facebook text-primary"></i>
                </div>
                <div class="card-title">Rahim Ahmed</div>
                <div class="card-service">eCommerce Website (20k BDT)</div>
                <div class="card-footer-info">
                    <span class="text-danger fw-bold"><i class="fa-solid fa-fire me-1"></i> Hot</span>
                    <img src="https://ui-avatars.com/api/?name=Rahim+Ahmed&background=random" class="rounded-circle" width="24" height="24">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Column: Negotiation -->
    <div class="kanban-column">
        <div class="kanban-header col-negotiation">
            <span>Negotiation</span>
            <span class="badge bg-secondary rounded-pill">1</span>
        </div>
        <div class="kanban-cards">
            <div class="kanban-card card-warm">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge bg-soft-warning text-dark" style="font-size: 0.65rem;">Score: 75</span>
                    <i class="fa-solid fa-globe text-success"></i>
                </div>
                <div class="card-title">Creative Agency Ltd</div>
                <div class="card-service">SEO Optimization</div>
                <div class="card-footer-info">
                    <span class="text-muted"><i class="fa-solid fa-calendar me-1"></i> Meeting Tomorrow</span>
                    <img src="https://ui-avatars.com/api/?name=Creative+Agency&background=random" class="rounded-circle" width="24" height="24">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Column: Won -->
    <div class="kanban-column">
        <div class="kanban-header col-won">
            <span>Closed Won</span>
            <span class="badge bg-secondary rounded-pill">0</span>
        </div>
        <div class="kanban-cards">
            <!-- Empty state -->
        </div>
    </div>

</div>
@endsection
