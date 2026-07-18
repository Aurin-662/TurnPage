@extends('layouts.admin')

@section('title', 'Categories — Admin')

@section('content')
<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-weight:700;">Category Management</h2>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Add New Category</h5>
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="category_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon</label>
                            <input type="text" name="icon" class="form-control" placeholder="📚">
                        </div>
                        <button type="submit" class="btn btn-dark">Save Category</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Assign Book to Category</h5>
                    <form action="{{ route('admin.categories.assign') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Book</label>
                            <select name="book_id" class="form-select" required>
                                @foreach($books as $book)
                                    <option value="{{ $book->book_id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-dark">Assign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Existing Categories</h5>
            <div class="row g-3">
                @foreach($categories as $category)
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fw-bold">{{ $category->icon ?? '📚' }} {{ $category->category_name }}</div>
                        <div class="text-muted small mt-1">{{ $category->description ?? 'No description' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
