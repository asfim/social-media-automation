@extends('layouts.app')

@section('title', 'Orders Management')

@push('styles')
<style>
    .order-card-stat {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .order-card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06);
    }
    .order-table-container {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .badge-pending { background: #FEF3C7; color: #92400E; }
    .badge-processing { background: #DBEAFE; color: #1E40AF; }
    .badge-completed { background: #D1FAE5; color: #065F46; }
    .badge-cancelled { background: #FEE2E2; color: #991B1B; }
    
    .product-thumb {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #E2E8F0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Top Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-cart-shopping text-primary me-2"></i> Orders Dashboard</h3>
            <p class="text-muted mb-0">Manage customer orders placed via Messenger, Web, and Social Channels.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('checkout') }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-external-link me-1"></i> Public Checkout Page
            </a>
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat">
                <div class="text-muted small fw-semibold text-uppercase">Total Orders</div>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat border-warning">
                <div class="text-warning small fw-semibold text-uppercase">Pending</div>
                <h3 class="fw-bold text-warning mb-0 mt-1">{{ $stats['pending'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat border-primary">
                <div class="text-primary small fw-semibold text-uppercase">Processing</div>
                <h3 class="fw-bold text-primary mb-0 mt-1">{{ $stats['processing'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat border-success">
                <div class="text-success small fw-semibold text-uppercase">Completed</div>
                <h3 class="fw-bold text-success mb-0 mt-1">{{ $stats['completed'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat border-danger">
                <div class="text-danger small fw-semibold text-uppercase">Cancelled</div>
                <h3 class="fw-bold text-danger mb-0 mt-1">{{ $stats['cancelled'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="order-card-stat bg-soft-primary">
                <div class="text-indigo small fw-semibold text-uppercase">Total Revenue</div>
                <h3 class="fw-bold text-indigo mb-0 mt-1">৳{{ number_format($stats['total_sales'], 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search order no, customer name or phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex gap-1 overflow-auto">
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => 'all'])) }}" class="btn btn-sm {{ request('status', 'all') === 'all' ? 'btn-primary' : 'btn-light' }} rounded-pill px-3">All</a>
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-light' }} rounded-pill px-3">Pending</a>
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => 'processing'])) }}" class="btn btn-sm {{ request('status') === 'processing' ? 'btn-info text-white' : 'btn-light' }} rounded-pill px-3">Processing</a>
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => 'completed'])) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-success' : 'btn-light' }} rounded-pill px-3">Completed</a>
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => 'cancelled'])) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-light' }} rounded-pill px-3">Cancelled</a>
                </div>
                <div class="col-12 col-md-2 text-md-end">
                    <button type="submit" class="btn btn-secondary btn-sm rounded-pill w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="order-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Customer</th>
                        <th>Product Details</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">
                                #{{ $order->order_number }}
                                @if($order->platform)
                                    <br><span class="badge bg-light text-muted fw-normal" style="font-size: 0.7rem;"><i class="fa-brands fa-{{ $order->platform }} me-1"></i> {{ ucfirst($order->platform) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                <div class="text-muted small"><i class="fa-solid fa-phone text-muted me-1"></i><a href="tel:{{ $order->customer_phone }}" class="text-decoration-none text-dark">{{ $order->customer_phone }}</a></div>
                                <div class="text-muted small text-truncate" style="max-width: 200px;" title="{{ $order->customer_address }}"><i class="fa-solid fa-location-dot text-muted me-1"></i>{{ $order->customer_address }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($order->product && $order->product->image)
                                        <img src="{{ $order->product->image }}" alt="{{ $order->product_name }}" class="product-thumb">
                                    @else
                                        <div class="product-thumb bg-light d-flex align-items-center justify-content-center text-muted fs-5"><i class="fa-solid fa-box"></i></div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $order->product_name }}</div>
                                        <div class="text-muted small">Qty: <span class="fw-bold text-dark">{{ $order->quantity }}</span> &bull; Price: ৳{{ number_format($order->product_price, 0) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">৳{{ number_format($order->total_amount, 2) }}</div>
                                <small class="text-muted">Cash on Delivery</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }} px-3 py-2 rounded-pill font-semibold">
                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark small">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item text-warning"><i class="fa-solid fa-clock me-2"></i> Set Pending</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item text-primary"><i class="fa-solid fa-spinner me-2"></i> Set Processing</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item text-success"><i class="fa-solid fa-check-circle me-2"></i> Set Completed</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-times-circle me-2"></i> Set Cancelled</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                <h5>No Orders Found</h5>
                                <p class="small mb-0">Customer orders will appear here automatically when they place an order via Messenger or Checkout page.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
