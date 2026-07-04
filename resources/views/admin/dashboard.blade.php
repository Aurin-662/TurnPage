<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .stat-card .stat-number { font-size: 2rem; font-weight: bold; }
        .stat-card .stat-label { color: #888; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .chart-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .table-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .badge-Pending { background-color: #f39c12; }
        .badge-Processing { background-color: #3498db; }
        .badge-Shipped { background-color: #9b59b6; }
        .badge-Delivered { background-color: #27ae60; }
        .badge-Cancelled { background-color: #e74c3c; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">⚙️ TurnPage Admin</a>
        <div class="ms-auto">
            <a class="nav-link d-inline" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-link d-inline" href="{{ route('admin.books.index') }}">Books</a>
            <a class="nav-link d-inline" href="{{ route('admin.orders.index') }}">Orders</a>
            <a class="nav-link d-inline" href="{{ route('admin.vouchers.index') }}">Vouchers</a>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light ms-2">Back to Site</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Dashboard</h2>

    <!-- ── Stats Row ── -->
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

    <!-- ── Charts Row ── -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="chart-card">
                <h6 class="mb-3">Orders by Status</h6>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-card">
                <h6 class="mb-3">Revenue by Payment Method</h6>
                <canvas id="paymentChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- ── Top Books + Recent Orders ── -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="table-card">
                <h6 class="mb-3">🏆 Top 5 Bestselling Books</h6>
                <table class="table table-sm">
                    <thead>
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
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->total_sold }}</td>
                            <td>Tk. {{ number_format($book->total_revenue, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="table-card">
                <h6 class="mb-3">🕐 Recent Orders</h6>
                <table class="table table-sm">
                    <thead>
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
                            <td>
                                <span class="badge badge-{{ $order->status }} text-white">{{ $order->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Orders by Status — Doughnut Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [@foreach($ordersByStatus as $s) '{{ $s->status }}', @endforeach],
        datasets: [{
            data: [@foreach($ordersByStatus as $s) {{ $s->total }}, @endforeach],
            backgroundColor: ['#f39c12','#3498db','#9b59b6','#27ae60','#e74c3c'],
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

// Revenue by Payment Method — Bar Chart
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'bar',
    data: {
        labels: [@foreach($revenueByMethod as $r) '{{ $r->payment_method }}', @endforeach],
        datasets: [{
            label: 'Revenue (Tk.)',
            data: [@foreach($revenueByMethod as $r) {{ $r->total }}, @endforeach],
            backgroundColor: ['#2c3e50','#e74c3c','#27ae60'],
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
</body>
</html>