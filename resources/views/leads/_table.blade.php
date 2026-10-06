@php
    $statusColors = [
        'New' => 'primary', 'Contacted' => 'info', 'Interested' => 'danger',
        'Negotiation' => 'warning', 'Won' => 'success', 'Lost' => 'secondary',
    ];
@endphp

@if(session('success'))
    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">{{ $heading }}</h3>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addLeadModal">
        <i class="fa-solid fa-plus me-2"></i> Add Lead
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Lead</th>
                        <th class="py-3">Contact</th>
                        <th class="py-3">Interested In</th>
                        <th class="py-3">Score</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Last Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium">{{ $lead->name }}</div>
                                <small class="text-muted">{{ $lead->platform ?? '-' }} &middot; {{ $lead->source ?? '-' }}</small>
                            </td>
                            <td class="small">
                                {{ $lead->phone ?? '-' }}<br>
                                <span class="text-muted">{{ $lead->email ?? '' }}</span>
                            </td>
                            <td>{{ $lead->interested_service ?? '-' }}</td>
                            <td><span class="fw-bold">{{ $lead->lead_score ?? 0 }}</span></td>
                            <td><span class="badge bg-{{ $statusColors[$lead->lead_status] ?? 'secondary' }}">{{ $lead->lead_status }}</span></td>
                            <td class="text-muted small">{{ $lead->last_contact ? \Illuminate\Support\Carbon::parse($lead->last_contact)->diffForHumans() : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-inbox fs-2 d-block mb-2"></i>
                                No leads yet. Leads appear here automatically from conversations, or add one manually.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($leads->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            {{ $leads->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content rounded-4 border-0" method="POST" action="{{ route('leads.store') }}">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Add Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Platform</label>
                        <select name="platform" class="form-select">
                            <option>Facebook</option>
                            <option>Instagram</option>
                            <option>WhatsApp</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Interested In</label>
                        <input type="text" name="interested_service" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Lead</button>
            </div>
        </form>
    </div>
</div>
