@extends('layouts.app')

@section('title', 'Keyword Rules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Keyword Rules</h3>
        <p class="text-muted mb-0">Override the AI and trigger strict responses based on specific words.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i> Add Keyword Rule
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Rule Name</th>
                        <th class="py-3">Trigger Condition</th>
                        <th class="py-3">Keywords</th>
                        <th class="py-3" style="width:30%">Static Response</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 fw-medium">Location Info</td>
                        <td><span class="badge bg-secondary">Contains</span></td>
                        <td>
                            <span class="badge bg-light text-dark border">address</span>
                            <span class="badge bg-light text-dark border">location</span>
                            <span class="badge bg-light text-dark border">where</span>
                        </td>
                        <td><span class="text-truncate d-inline-block" style="max-width:250px;">We are located at Banani, Road 11, Dhaka.</span></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-pen"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 fw-medium">Human Handover</td>
                        <td><span class="badge bg-dark">Exact Match</span></td>
                        <td>
                            <span class="badge bg-light text-dark border">talk to human</span>
                            <span class="badge bg-light text-dark border">customer care</span>
                        </td>
                        <td><span class="text-truncate d-inline-block text-primary" style="max-width:250px;"><i class="fa-solid fa-robot"></i> Handover to Human Admin</span></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-pen"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
