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

                    <form action="{{ route('invitation.request.store') }}" method="POST" id="invitationRequestForm">
                        @csrf

                        <div class="mb-3">
                            <label for="event_id" class="form-label">Event</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                                <select
                                    class="form-select @error('event_id') is-invalid @enderror"
                                    id="event_id"
                                    name="event_id"
                                    required
                                    autofocus
                                    @disabled($events->isEmpty())
                                >
                                    <option value="">Select an event</option>
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
                                <div class="form-text">No published events are available right now.</div>
                            @else
                                <div class="form-text">Select an event first to fill in your details.</div>
                            @endif
                        </div>

                        @php
                            $guestFieldsEnabled = filled(old('event_id'));
                        @endphp

                        <fieldset id="guestFields" @disabled(! $guestFieldsEnabled)>
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

                            <button type="submit" class="btn btn-primary w-100 login-submit" id="submitRequestBtn" @disabled(! $guestFieldsEnabled || $events->isEmpty())>
                                <i class="bi bi-send"></i>
                                Submit request
                            </button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const eventSelect = document.getElementById('event_id');
            const guestFields = document.getElementById('guestFields');
            const submitBtn = document.getElementById('submitRequestBtn');
            const nameInput = document.getElementById('name');

            if (!eventSelect || !guestFields || !submitBtn) return;

            function syncGuestFields() {
                const hasEvent = Boolean(eventSelect.value);
                guestFields.disabled = !hasEvent;
                submitBtn.disabled = !hasEvent;

                if (hasEvent) {
                    nameInput?.focus();
                }
            }

            eventSelect.addEventListener('change', syncGuestFields);
            syncGuestFields();
        })();
    </script>
@endpush
