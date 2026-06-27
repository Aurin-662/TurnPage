<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('login')->with('error', 'You must be an admin to access this page.');
        }

        return $next($request);
    }
}