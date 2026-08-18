@extends('layouts.app')

@section('title', 'Create Invitation')

@section('content')
    <div class="container">
        <div class="invitation-form-wrap">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <div>
                    <h1 class="h3 mb-1">Create invitation</h1>
                    <p class="text-muted mb-0">Fill in guest details. Sent time is saved automatically.</p>
                </div>
                <a href="{{ route('invitation') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>

            <div class="card invitation-form-card">
                <div class="card-body p-4">
                    <form action="{{ route('invitation.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Guest full name"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="guest@example.com"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input
                                    type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="Optional"
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select
                                    class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required
                                >
                                    @foreach (['pending', 'used', 'expired', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="expires_at" class="form-label">Expires at</label>
                                <input
                                    type="datetime-local"
                                    class="form-control @error('expires_at') is-invalid @enderror"
                                    id="expires_at"
                                    name="expires_at"
                                    value="{{ old('expires_at') }}"
                                >
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i>
                                Save invitation
                            </button>
                            <a href="{{ route('invitation.request') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
