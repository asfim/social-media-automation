@extends('layouts.app')

@section('title', 'Business Profile Settings')

@section('content')
<div class='d-flex justify-content-between align-items-center mb-4'>
    <h3 class='mb-0'>Business Profile Settings</h3>
    <button class='btn btn-primary rounded-pill px-4'>
        <i class='fa-solid fa-plus me-2'></i> Add New
    </button>
</div>

<div class='card border-0 shadow-sm rounded-4'>
    <div class='card-body p-0'>
        <div class='table-responsive'>
            <table class='table table-hover align-middle mb-0'>
                <thead class='bg-light'>
                    <tr>
                        <th class='ps-4 py-3'>ID</th>
                        <th class='py-3'>Name / Details</th>
                        <th class='py-3'>Status</th>
                        <th class='py-3'>Date</th>
                        <th class='text-end pe-4 py-3'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='ps-4'>#1042</td>
                        <td>
                            <div class='fw-medium'>Sample Data Entry</div>
                            <small class='text-muted'>This is a placeholder row for Business Profile Settings</small>
                        </td>
                        <td><span class='badge bg-soft-success'>Active</span></td>
                        <td class='text-muted small'>Just now</td>
                        <td class='text-end pe-4'>
                            <button class='btn btn-sm btn-light rounded-circle'><i class='fa-solid fa-pen'></i></button>
                            <button class='btn btn-sm btn-light rounded-circle text-danger ms-1'><i class='fa-solid fa-trash'></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class='ps-4'>#1041</td>
                        <td>
                            <div class='fw-medium'>Another Record</div>
                            <small class='text-muted'>System generated</small>
                        </td>
                        <td><span class='badge bg-soft-warning'>Pending</span></td>
                        <td class='text-muted small'>2 hours ago</td>
                        <td class='text-end pe-4'>
                            <button class='btn btn-sm btn-light rounded-circle'><i class='fa-solid fa-pen'></i></button>
                            <button class='btn btn-sm btn-light rounded-circle text-danger ms-1'><i class='fa-solid fa-trash'></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class='card-footer bg-white border-top-0 p-4'>
        <nav aria-label='Page navigation'>
            <ul class='pagination pagination-sm justify-content-end mb-0'>
                <li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>
                <li class='page-item active'><a class='page-link' href='#'>1</a></li>
                <li class='page-item'><a class='page-link' href='#'>2</a></li>
                <li class='page-item'><a class='page-link' href='#'>Next</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

