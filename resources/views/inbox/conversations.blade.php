@extends('layouts.app')

@section('title', 'All Conversations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Conversation History</h3>
    <button class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-download me-2"></i> Export Logs
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white pt-4 pb-3 px-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control rounded-pill" placeholder="Search by name or message...">
            </div>
            <div class="col-md-3">
                <select class="form-select rounded-pill">
                    <option>All Platforms</option>
                    <option>Facebook Messenger</option>
                    <option>Instagram Direct</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select rounded-pill">
                    <option>All Statuses</option>
                    <option>Resolved by AI</option>
                    <option>Human Intervention</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-primary rounded-pill w-100">Search</button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Customer</th>
                        <th class="py-3">Last Message</th>
                        <th class="py-3">Platform</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Hasan+Mahmud" class="rounded-circle" width="32">
                                <span class="fw-medium">Hasan Mahmud</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 250px;">Thanks! I'll order it from the website.</div>
                        </td>
                        <td><i class="fa-brands fa-facebook text-primary fs-5"></i></td>
                        <td><span class="badge bg-success rounded-pill px-3">Resolved by AI</span></td>
                        <td class="text-muted small">Oct 5, 2026</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('inbox.messenger') }}" class="btn btn-sm btn-light rounded-pill px-3">View Chat</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Anika+Tabassum" class="rounded-circle" width="32">
                                <span class="fw-medium">Anika Tabassum</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate text-dark fw-bold" style="max-width: 250px;">Can I talk to a real person?</div>
                        </td>
                        <td><i class="fa-brands fa-instagram text-danger fs-5"></i></td>
                        <td><span class="badge bg-danger rounded-pill px-3">Human Needed</span></td>
                        <td class="text-muted small">Oct 4, 2026</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('inbox.messenger') }}" class="btn btn-sm btn-primary rounded-pill px-3">Take Over</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
