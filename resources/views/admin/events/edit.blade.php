@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="container">
        <div class="invitation-form-wrap">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <div>
                    <h1 class="h3 mb-1">Edit event</h1>
                    <p class="text-muted mb-0">Update the event details, schedule, location, and status.</p>
                </div>
                <a href="{{ route('events') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>

            <div class="card invitation-form-card overflow-hidden">
                <div class="invitation-form-header">
                    <div class="invitation-form-header-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <h2>Event details</h2>
                        <p>Update this event’s information.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('events.update', $event) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $event->name) }}"
                                        placeholder="Event name"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">
                                    Description
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <textarea
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="What is this event about?"
                                >{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="event_start" class="form-label">Event start</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                    <input
                                        type="datetime-local"
                                        class="form-control @error('event_start') is-invalid @enderror"
                                        id="event_start"
                                        name="event_start"
                                        value="{{ old('event_start', $event->event_start ? \Carbon\Carbon::parse($event->event_start)->format('Y-m-d\TH:i') : '') }}"
                                        required
                                    >
                                </div>
                                @error('event_start')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="event_end" class="form-label">
                                    Event end
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                    <input
                                        type="datetime-local"
                                        class="form-control @error('event_end') is-invalid @enderror"
                                        id="event_end"
                                        name="event_end"
                                        value="{{ old('event_end', $event->event_end ? \Carbon\Carbon::parse($event->event_end)->format('Y-m-d\TH:i') : '') }}"
                                    >
                                </div>
                                @error('event_end')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="location" class="form-label">
                                    Location
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('location') is-invalid @enderror"
                                        id="location"
                                        name="location"
                                        value="{{ old('location', $event->location) }}"
                                        placeholder="Venue or address"
                                    >
                                </div>
                                @error('location')
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
                                        @foreach (['draft', 'published', 'cancelled'] as $status)
                                            <option value="{{ $status }}" @selected(old('status', $event->status) === $status)>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg"></i>
                                Update event
                            </button>
                            <a href="{{ route('events') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
