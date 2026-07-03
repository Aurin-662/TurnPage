<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Create Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .form-card { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.books.index') }}">⚙️ TurnPage Admin</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Create New Voucher</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Voucher Code</label>
                    <input type="text" name="voucher_code" class="form-control" placeholder="e.g. SUMMER25" required>
                    <small class="text-muted">Will be automatically converted to uppercase.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount_percent" class="form-control" min="1" max="100" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Minimum Order Amount (Tk.)</label>
                        <input type="number" name="minimum_amount" class="form-control" min="0" value="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valid From</label>
                        <input type="date" name="valid_from" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valid To</label>
                        <input type="date" name="valid_to" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control" min="1" value="100" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Create Voucher</button>
            </form>
        </div>
    </div>

</body>
</html>