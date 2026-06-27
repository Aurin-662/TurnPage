<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #1a1a2e; }
        .navbar-brand { color: #fff !important; font-weight: bold; }
        .form-card { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
        .ts-control { padding: 0.5rem 0.75rem; }
        .create { padding: 6px 10px; color: #2c3e50; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.books.index') }}">⚙️ TurnPage Admin</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Edit Book</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.books.update', $book->book_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Author</label>
                        <select name="author_id" id="authorSelect" class="form-select" required>
                            @foreach($authors as $author)
                                <option value="{{ $author->author_id }}" {{ $book->author_id == $author->author_id ? 'selected' : '' }}>{{ $author->author_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Publisher</label>
                        <select name="publisher_id" id="publisherSelect" class="form-select" required>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->publisher_id }}" {{ $book->publisher_id == $publisher->publisher_id ? 'selected' : '' }}>{{ $publisher->publisher_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price (Tk.)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $book->price }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="{{ $book->stock_quantity }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Page Count</label>
                        <input type="number" name="page_count" class="form-control" value="{{ $book->page_count }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="{{ $book->isbn }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select" required>
                            <option value="Bangla" {{ $book->language == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                            <option value="English" {{ $book->language == 'English' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100">Update Book</button>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
// AUTHOR — Searchable Select with "Add New"
new TomSelect('#authorSelect', {
    create: function(input, callback) {
        fetch('{{ route("admin.authors.quickadd") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ author_name: input })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                callback({ value: data.author_id, text: data.author_name });
            } else {
                callback();
            }
        })
        .catch(() => callback());
    },
    createFilter: function(input) {
        return input.trim().length > 0;
    },
    render: {
        option_create: function(data, escape) {
            return '<div class="create">Add new author: <strong>"' + escape(data.input) + '"</strong></div>';
        }
    }
});

// PUBLISHER — Searchable Select with "Add New"
new TomSelect('#publisherSelect', {
    create: function(input, callback) {
        fetch('{{ route("admin.publishers.quickadd") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ publisher_name: input })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                callback({ value: data.publisher_id, text: data.publisher_name });
            } else {
                callback();
            }
        })
        .catch(() => callback());
    },
    createFilter: function(input) {
        return input.trim().length > 0;
    },
    render: {
        option_create: function(data, escape) {
            return '<div class="create">Add new publisher: <strong>"' + escape(data.input) + '"</strong></div>';
        }
    }
});
</script>
</body>
</html>