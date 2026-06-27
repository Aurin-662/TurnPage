<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function quickAdd(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $newId = (Author::max('author_id') ?? 0) + 1;

        $author = Author::create([
            'author_id' => $newId,
            'author_name' => $request->author_name,
            'country' => $request->country,
        ]);

        return response()->json([
            'success' => true,
            'author_id' => $author->author_id,
            'author_name' => $author->author_name,
        ]);
    }
}