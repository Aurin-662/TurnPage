<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Manage Vouchers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .nav-link { color: #ecf0f1 !important; margin-left: 10px; }
        .table-card { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.books.index') }}">⚙️ TurnPage Admin</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Vouchers</h2>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-dark">+ Create Voucher</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Min. Amount</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Usage Limit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vouchers as $v)
                    <tr>
                        <td><strong>{{ $v->voucher_code }}</strong></td>
                        <td>{{ $v->discount_percent }}%</td>
                        <td>Tk. {{ number_format($v->minimum_amount, 2) }}</td>
                        <td>{{ $v->valid_from ? \Carbon\Carbon::parse($v->valid_from)->format('d M Y') : '-' }}</td>
                        <td>{{ $v->valid_to ? \Carbon\Carbon::parse($v->valid_to)->format('d M Y') : '-' }}</td>
                        <td>{{ $v->usage_limit }}</td>
                        <td>
                            @if($v->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.vouchers.toggle', $v->voucher_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $v->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    {{ $v->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.vouchers.destroy', $v->voucher_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this voucher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>