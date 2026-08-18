@extends('layouts.app')

@section('title', 'Invitation Request')

@section('content')
    <div class="container login-page">
        <div class="login-wrap">
            <div class="login-card">
                <div class="login-brand">
                    <div class="login-brand-icon">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <h1>Invitation Request</h1>
                    <p>Submit your details to request an event invitation.</p>
                </div>

                <div class="login-body">
                    <h2>Guest information</h2>
                    <p class="login-subtitle">We will review your request and send your QR invitation.</p>

                    <form action="{{ route('invitation.request.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Your full name"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

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
                                    required
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Phone <span class="text-muted">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="Contact number"
                                >
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 login-submit">
                            <i class="bi bi-send"></i>
                            Submit request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
