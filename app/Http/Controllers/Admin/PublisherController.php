<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function quickAdd(Request $request)
    {
        $request->validate([
            'publisher_name' => 'required|string|max:100',
        ]);

        $newId = (Publisher::max('publisher_id') ?? 0) + 1;

        $publisher = Publisher::create([
            'publisher_id' => $newId,
            'publisher_name' => $request->publisher_name,
        ]);

        return response()->json([
            'success' => true,
            'publisher_id' => $publisher->publisher_id,
            'publisher_name' => $publisher->publisher_name,
        ]);
    }
}