@extends('layouts.app')

@section('title', 'Register — TurnPage')

@section('styles')
<style>
    .auth-card { max-width: 440px; margin: 60px auto; background: #fff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .auth-title { font-family: 'Merriweather', serif; font-size: 1.6rem; margin-bottom: 8px; }
    .btn-auth { background: #1a1a2e; color: #fff; border: none; padding: 12px; border-radius: 8px; width: 100%; font-size: 1rem; }
    .btn-auth:hover { background: #0f0f1a; color: #fff; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="auth-card reveal-on-scroll">
        <h2 class="auth-title text-center">Create Account</h2>
        <p class="text-muted text-center small mb-4">Join TurnPage today</p>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Phone (optional)</label>
                <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
            </div>
            <button type="submit" class="btn-auth">Create Account</button>
        </form>

        <p class="text-center mt-4 mb-0 small">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign in</a></p>
    </div>
</div>
@endsection