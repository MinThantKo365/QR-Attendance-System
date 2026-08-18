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

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Guest</th>
                                <th>Email</th>
                                <th>Phone</th>
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
                                    <td>{{ optional($attendance->scanned_at)->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>{{ $attendance->scanner->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="bi bi-clipboard-check"></i>
                                            <p>No attendance records yet</p>
                                            <small>Check-ins will appear here after guests are scanned.</small>
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
