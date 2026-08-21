@extends('layouts.app')

@section('title', 'Create Invitation')

@section('content')
    <div class="container">
        <div class="invitation-form-wrap">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <div>
                    <h1 class="h3 mb-1">Create invitation</h1>
                    <p class="text-muted mb-0">Fill in guest details. Invite ID and sent time are set automatically.</p>
                </div>
                <a href="{{ route('invitation') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>

            <div class="card invitation-form-card overflow-hidden">
                <div class="invitation-form-header">
                    <div class="invitation-form-header-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div>
                        <h2>Guest invitation</h2>
                        <p>Create a QR invitation for a guest. Event is optional.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('invitation.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="event_id" class="form-label">
                                    Event
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                    <select
                                        class="form-select @error('event_id') is-invalid @enderror"
                                        id="event_id"
                                        name="event_id"
                                    >
                                        <option value="">No event selected</option>
                                        @foreach ($events as $event)
                                            <option value="{{ $event->id }}" @selected((string) old('event_id') === (string) $event->id)>
                                                {{ $event->name }}
                                                @if ($event->location)
                                                    — {{ $event->location }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('event_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if ($events->isEmpty())
                                    <div class="form-text">No events yet. You can still create the invitation without one.</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
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
                                        placeholder="Guest full name"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
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
                                        placeholder="guest@example.com"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
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

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-flag"></i>
                                    </span>
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
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="expires_at" class="form-label">
                                    Expires at
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                    <input
                                        type="datetime-local"
                                        class="form-control @error('expires_at') is-invalid @enderror"
                                        id="expires_at"
                                        name="expires_at"
                                        value="{{ old('expires_at') }}"
                                    >
                                </div>
                                @error('expires_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg"></i>
                                Save invitation
                            </button>
                            <a href="{{ route('invitation') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
