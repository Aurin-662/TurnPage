<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        // Check if email already exists
        $exists = User::where('email', $request->email)->first();
        if ($exists) {
            return back()->withErrors(['email' => 'This email is already registered.']);
        }

        // Get next user_id manually (since no sequence yet)
        $maxId = User::max('user_id') ?? 0;
        $newId = $maxId + 1;

        User::create([
            'user_id' => $newId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'customer',
            'created_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }

        // Store user info in session
        Session::put('user_id', $user->user_id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        if ($user->role === 'admin') {
            return redirect()->route('home')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}