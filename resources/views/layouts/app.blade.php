<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TurnPage — Your Online Bookstore')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @yield('styles')
    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #f8f5f0;
            font-family: 'Inter', sans-serif;
            color: #2c2c2c;
        }

        /* ── Navbar ── */
        .navbar {
            background-color: #1a1a2e;
            padding: 0.8rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-family: 'Merriweather', serif;
            font-size: 1.4rem;
            color: #fff !important;
            letter-spacing: -0.5px;
        }
        .navbar-brand span { color: #e8a045; }
        .nav-link {
            color: #ccc !important;
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem !important;
            transition: color 0.2s;
            position: relative;
        }
        .nav-link:hover { color: #fff !important; }
        .nav-link.active { color: #fff !important; }
        .navbar .btn-outline-light {
            font-size: 0.85rem;
            padding: 0.3rem 0.9rem;
            border-radius: 20px;
        }
        .navbar-cart-badge {
            background: #e8a045;
            color: #1a1a2e;
            border-radius: 50%;
            font-size: 0.65rem;
            padding: 2px 5px;
            position: absolute;
            top: -6px;
            right: -6px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(232,160,69,0.4);
            line-height: 1;
        }

        /* ── Page wrapper ── */
        .page-wrapper {
            min-height: calc(100vh - 130px);
        }

        /* ── Footer ── */
        footer {
            background-color: #1a1a2e;
            color: #aaa;
            padding: 20px 0;
            margin-top: 60px;
            font-size: 0.85rem;
        }
        footer a { color: #e8a045; text-decoration: none; }

        /* ── Flash messages ── */
        .flash-container {
            position: fixed;
            top: 75px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
        }
    </style>
</head>
<body>

    <!-- ── NAVBAR ── -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📖 Turn<span>Page</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">Authors</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    @if(session('user_id'))
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('wishlist.view') }}">
                                ❤️
                                @php
                                    $wishlistCount = 0;
                                    if(session('user_id')) {
                                        $wishlistCount = \App\Models\Wishlist::where('user_id', session('user_id'))->count();
                                    }
                                @endphp
                                @if($wishlistCount > 0)
                                    <sup class="navbar-cart-badge">{{ $wishlistCount }}</sup>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.view') }}">
                                🛒
                                @php
                                    $cartCount = 0;
                                    if(session('user_id')) {
                                        $cartCount = \App\Models\CartItem::whereIn('cart_id', 
                                            \App\Models\Cart::where('user_id', session('user_id'))->pluck('cart_id')
                                        )->sum('quantity');
                                    }
                                @endphp
                                @if($cartCount > 0)
                                    <sup class="navbar-cart-badge">{{ $cartCount }}</sup>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.history') }}">📦 Orders</a>
                        </li>

                        @if(session('user_role') === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('admin.dashboard') }}">⚙️ Admin</a>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                👤 {{ session('user_name') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('orders.history') }}">My Orders</a></li>
                                <li><a class="dropdown-item" href="{{ route('wishlist.view') }}">My Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- ── FLASH MESSAGES ── -->
    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- ── PAGE CONTENT ── -->
    <div class="page-wrapper">
        @yield('content')
    </div>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">© 2026 <strong>TurnPage</strong> — Online Bookstore Management System</p>
            <p class="mb-0 mt-1">CSE 3109 / CSE 3110 · Database Systems Lab Project</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

    <!-- Auto-dismiss flash messages -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-container .alert').forEach(el => {
                let alert = bootstrap.Alert.getOrCreateInstance(el);
                alert.close();
            });
        }, 3000);
    </script>
</body>
</html>