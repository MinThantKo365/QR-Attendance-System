@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
            <div>
                <h1 class="h3 mb-1">Attendance</h1>
                <p class="text-muted mb-0">Guests who checked in via QR scan.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('attendance') }}" class="row g-2 align-items-end">
                    <div class="col-md-5 col-lg-4">
                        <label for="event_id" class="form-label mb-1">Filter by event</label>
                        <select class="form-select" id="event_id" name="event_id">
                            <option value="">All events</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected((string) $eventId === (string) $event->id)>
                                    {{ $event->name }}
                                    @if ($event->location)
                                        — {{ $event->location }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label for="scanned_date" class="form-label mb-1">Scanned at</label>
                        <input
                            type="date"
                            class="form-control"
                            id="scanned_date"
                            name="scanned_date"
                            value="{{ $scannedDate }}"
                        >
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <!-- <i class="bi bi-funnel"></i> -->
                           Apply Filter
                        </button>
                    </div>
                    @if ($eventId || $scannedDate)
                        <div class="col-auto">
                            <a href="{{ route('attendance') }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Guest</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event</th>
                                <th>Scanned at</th>
                                <th>Scanner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="guest-cell">
                                            <span class="guest-avatar">{{ strtoupper(substr($attendance->invitation->name ?? 'G', 0, 1)) }}</span>
                                            <div>
                                                <strong>{{ $attendance->invitation->name ?? 'Unknown guest' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->invitation->email ?? '—' }}</td>
                                    <td>{{ $attendance->invitation->phone ?? '—' }}</td>
                                    <td>{{ $attendance->invitation->event->name ?? '—' }}</td>
                                    <td>{{ optional($attendance->scanned_at)->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>{{ $attendance->scanner->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-clipboard-check"></i>
                                            <p>No attendance records found</p>
                                            <small>
                                                @if ($eventId || $scannedDate)
                                                    No check-ins match the selected filters.
                                                @else
                                                    Check-ins will appear here after guests are scanned.
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
