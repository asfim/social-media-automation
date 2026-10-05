@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="welcome-section">
    <h2>Good Morning, Admin 👋</h2>
    <p>Here's what's happening with your social media today: {{ now()->format('F j, Y') }}</p>
</div>

<!-- KPI Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Total Messages</span>
                <div class="kpi-icon icon-blue"><i class="fa-solid fa-message"></i></div>
            </div>
            <div class="kpi-value">1,284</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +18.4%
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Comments</span>
                <div class="kpi-icon icon-purple"><i class="fa-solid fa-comments"></i></div>
            </div>
            <div class="kpi-value">846</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +12.5%
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">AI Replies</span>
                <div class="kpi-icon icon-green"><i class="fa-solid fa-robot"></i></div>
            </div>
            <div class="kpi-value">1,052</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +21.7%
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">New Leads</span>
                <div class="kpi-icon icon-orange"><i class="fa-solid fa-user-plus"></i></div>
            </div>
            <div class="kpi-value">126</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +15.2%
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Hot Leads</span>
                <div class="kpi-icon icon-red"><i class="fa-solid fa-fire"></i></div>
            </div>
            <div class="kpi-value">38</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +8.6%
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Conversion Rate</span>
                <div class="kpi-icon icon-blue"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <div class="kpi-value">12.8%</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i> +3.4%
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Line Chart -->
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <span>Messages Overview</span>
                <select class="form-select form-select-sm w-auto border-0 bg-light text-muted">
                    <option>Today</option>
                    <option selected>7 Days</option>
                    <option>30 Days</option>
                    <option>90 Days</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="messagesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Donut Chart -->
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <span>AI Performance</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="position: relative; width: 100%; max-width: 250px;">
                    <canvas id="aiPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Bar Chart -->
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <span>Lead Overview</span>
            </div>
            <div class="card-body">
                <canvas id="leadsChart" height="150"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Conversations Table -->
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <span>Recent Conversations</span>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Customer</th>
                                <th>Platform</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=Rahim+Ahmed&background=random" class="rounded-circle" width="32" height="32" alt="Avatar">
                                        <div class="fw-medium">Rahim Ahmed</div>
                                    </div>
                                </td>
                                <td><i class="fa-brands fa-facebook text-primary fs-5"></i></td>
                                <td><span class="badge bg-soft-success">AI Replied</span></td>
                                <td class="text-muted small">2 min ago</td>
                                <td class="text-end"><button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-chevron-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=Karim+Hasan&background=random" class="rounded-circle" width="32" height="32" alt="Avatar">
                                        <div class="fw-medium">Karim Hasan</div>
                                    </div>
                                </td>
                                <td><i class="fa-brands fa-instagram text-danger fs-5"></i></td>
                                <td><span class="badge bg-soft-warning">Pending Review</span></td>
                                <td class="text-muted small">15 min ago</td>
                                <td class="text-end"><button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-chevron-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=Sumaiya+Islam&background=random" class="rounded-circle" width="32" height="32" alt="Avatar">
                                        <div class="fw-medium">Sumaiya Islam</div>
                                    </div>
                                </td>
                                <td><i class="fa-brands fa-facebook text-primary fs-5"></i></td>
                                <td><span class="badge bg-soft-primary">Human Active</span></td>
                                <td class="text-muted small">1 hour ago</td>
                                <td class="text-end"><button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-chevron-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=Jalal+Uddin&background=random" class="rounded-circle" width="32" height="32" alt="Avatar">
                                        <div class="fw-medium">Jalal Uddin</div>
                                    </div>
                                </td>
                                <td><i class="fa-brands fa-whatsapp text-success fs-5"></i></td>
                                <td><span class="badge bg-soft-success">AI Replied</span></td>
                                <td class="text-muted small">3 hours ago</td>
                                <td class="text-end"><button class="btn btn-sm btn-light rounded-circle"><i class="fa-solid fa-chevron-right"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Set common styles for charts
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748B';
    Chart.defaults.scale.grid.color = '#F1F5F9';
    
    // 1. Messages Overview (Line Chart)
    const ctxMessages = document.getElementById('messagesChart').getContext('2d');
    
    // Gradient for AI Replies
    let gradientAI = ctxMessages.createLinearGradient(0, 0, 0, 400);
    gradientAI.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradientAI.addColorStop(1, 'rgba(16, 185, 129, 0)');
    
    new Chart(ctxMessages, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [
                {
                    label: 'AI Replies',
                    data: [150, 230, 224, 218, 335, 247, 260],
                    borderColor: '#10B981',
                    backgroundColor: gradientAI,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Human Replies',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: '#4F46E5',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', align: 'end', labels: { boxWidth: 10, usePointStyle: true } }
            },
            scales: {
                y: { beginAtZero: true, border: { display: false } },
                x: { border: { display: false }, grid: { display: false } }
            }
        }
    });

    // 2. AI Performance (Donut Chart)
    const ctxAI = document.getElementById('aiPerformanceChart').getContext('2d');
    new Chart(ctxAI, {
        type: 'doughnut',
        data: {
            labels: ['AI Resolved', 'Human Handover', 'Unanswered'],
            datasets: [{
                data: [75, 20, 5],
                backgroundColor: ['#10B981', '#4F46E5', '#CBD5E1'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 20 } }
            }
        }
    });

    // 3. Lead Overview (Bar Chart)
    const ctxLeads = document.getElementById('leadsChart').getContext('2d');
    new Chart(ctxLeads, {
        type: 'bar',
        data: {
            labels: ['New', 'Contacted', 'Interested', 'Negotiation', 'Won', 'Lost'],
            datasets: [{
                label: 'Leads',
                data: [65, 45, 30, 20, 15, 5],
                backgroundColor: '#3B82F6',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, border: { display: false } },
                x: { border: { display: false }, grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
