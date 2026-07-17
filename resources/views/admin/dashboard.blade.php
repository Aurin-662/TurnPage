@extends('layouts.admin')

@section('title', 'Dashboard — Admin')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stat-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .stat-number { font-size: 2rem; font-weight: 700; }
    .stat-label { color: #888; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px; }
    .chart-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .table-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .status-Pending { background:#fff3cd; color:#856404; }
    .status-Processing { background:#cfe2ff; color:#084298; }
    .status-Shipped { background:#e2d9f3; color:#432874; }
    .status-Delivered { background:#d1e7dd; color:#0a3622; }
    .status-Cancelled { background:#f8d7da; color:#58151c; }
    .status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="mb-4" style="font-weight:700;">Dashboard</h2>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card border-start border-success border-4">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-number text-success">Tk. {{ number_format($totalRevenue, 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-primary border-4">
                <div class="stat-label">Total Orders</div>
                <div class="stat-number text-primary">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-warning border-4">
                <div class="stat-label">Total Customers</div>
                <div class="stat-number text-warning">{{ $totalCustomers }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-danger border-4">
                <div class="stat-label">Total Books</div>
                <div class="stat-number text-danger">{{ $totalBooks }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="chart-card">
                <h6 class="mb-3 fw-semibold">Orders by Status</h6>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-card">
                <h6 class="mb-3 fw-semibold">Revenue by Payment Method</h6>
                <canvas id="paymentChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Books + Recent Orders -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="table-card">
                <h6 class="mb-3 fw-semibold">🏆 Top 5 Bestselling Books</h6>
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topBooks as $i => $book)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ Str::limit($book->title, 25) }}</td>
                            <td><span class="badge bg-dark">{{ $book->total_sold }}</span></td>
                            <td>Tk. {{ number_format($book->total_revenue, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="table-card">
                <h6 class="mb-3 fw-semibold">🕐 Recent Orders</h6>
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Tk. {{ number_format($order->total_amount, 0) }}</td>
                            <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [@foreach($ordersByStatus as $s) '{{ $s->status }}', @endforeach],
        datasets: [{
            data: [@foreach($ordersByStatus as $s) {{ $s->total }}, @endforeach],
            backgroundColor: ['#f39c12','#3498db','#9b59b6','#27ae60','#e74c3c'],
            borderWidth: 0,
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%'
    }
});

const paymentCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'bar',
    data: {
        labels: [@foreach($revenueByMethod as $r) '{{ $r->payment_method }}', @endforeach],
        datasets: [{
            label: 'Revenue (Tk.)',
            data: [@foreach($revenueByMethod as $r) {{ $r->total }}, @endforeach],
            backgroundColor: ['#1a1a2e','#c0392b','#27ae60'],
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: '#f0f0f0' } }, x: { grid: { display: false } } }
    }
});
</script>
@endsection