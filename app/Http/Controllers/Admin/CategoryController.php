<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('DISPLAY_ORDER')->get();
        $books = Book::orderBy('title')->get();

        return view('admin.categories.index', compact('categories', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:20',
        ]);

        try {
            DB::statement('BEGIN ADD_CATEGORY(?, ?, ?); END;', [
                $request->category_name,
                $request->description ?? '',
                $request->icon ?? '📚',
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category added successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not add category: ' . $e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'book_id' => 'required|integer',
        ]);

        try {
            DB::statement('BEGIN ASSIGN_BOOK_TO_CATEGORY(?, ?); END;', [
                $request->book_id,
                $request->category_id,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Book assigned to category.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not assign book: ' . $e->getMessage());
        }
    }
}
