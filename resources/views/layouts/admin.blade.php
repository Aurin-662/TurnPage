<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TurnPage Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @yield('styles')
    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .navbar { background-color: #1a1a2e; padding: 0.8rem 0; }
        .navbar-brand { color: #fff !important; font-weight: 600; font-size: 1.1rem; }
        .navbar-brand span { color: #e8a045; }
        .nav-link { color: #ccc !important; font-size: 0.9rem; padding: 0.5rem 0.8rem !important; }
        .nav-link:hover, .nav-link.active { color: #fff !important; }
        .flash-container { position: fixed; top: 75px; right: 20px; z-index: 9999; min-width: 280px; }
        .page-wrapper { min-height: calc(100vh - 130px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">⚙️ TurnPage <span>Admin</span></a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">Books</a>
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}">Vouchers</a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light ms-2">← Back to Site</a>
            </div>
        </div>
    </nav>

    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="page-wrapper">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-container .alert').forEach(el => {
                bootstrap.Alert.getOrCreateInstance(el).close();
            });
        }, 3000);
    </script>
</body>
</html>