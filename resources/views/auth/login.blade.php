@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container login-page">
        <div class="login-wrap">
            <div class="login-card">
                <div class="login-brand">
                    <div class="login-brand-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h1>QR Attendance</h1>
                    <p>Sign in to manage invitations or scan guest codes.</p>
                </div>

                <div class="login-body">
                    <h2>Welcome back</h2>
                    <p class="login-subtitle">Enter your staff account to continue.</p>

                    <form action="{{ route('login') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="you@example.com"
                                    autocomplete="email"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required
                                >
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 login-submit">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
