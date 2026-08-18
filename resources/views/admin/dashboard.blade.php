@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container dashboard-page">
        <div class="dashboard-hero">
            <div>
                <p class="dashboard-kicker">Admin overview</p>
                <h1>Welcome back, {{ auth()->user()->name }}</h1>
                <p class="text-muted mb-0">Track invitations, check-ins, and scanner activity from one place.</p>
            </div>
            <a href="{{ url('/admin/invitations/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                New invitation
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon bg-primary">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <div>
                        <span>Invitations</span>
                        <strong>{{ $stats['invitations'] }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon bg-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <span>Checked in</span>
                        <strong>{{ $stats['checked_in'] }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon bg-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <span>Pending</span>
                        <strong>{{ $stats['pending'] }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon bg-info">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <span>Today's scans</span>
                        <strong>{{ $stats['today'] }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="{{ url('/admin/invitations') }}" class="action-card">
                    <i class="bi bi-people"></i>
                    <strong>Invitations</strong>
                    <span>Create and manage guest QR codes</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ url('/admin/attendance') }}" class="action-card">
                    <i class="bi bi-clipboard-check"></i>
                    <strong>Attendance</strong>
                    <span>Review who has already checked in</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ url('/scanner') }}" class="action-card">
                    <i class="bi bi-qr-code-scan"></i>
                    <strong>Scanner</strong>
                    <span>Open the camera and mark attendance</span>
                </a>
            </div>
        </div>

        <div class="card dashboard-table-card">
            <div class="card-header">
                <div>
                    <h2>Recent check-ins</h2>
                    <p>Latest guests scanned at the door</p>
                </div>
                <a href="{{ url('/admin/attendance') }}" class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Scanned at</th>
                            <th>Scanner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentAttendances as $attendance)
                            <tr>
                                <td>
                                    <div class="guest-cell">
                                        <span class="guest-avatar">{{ strtoupper(substr($attendance->invitation->name ?? 'G', 0, 1)) }}</span>
                                        <div>
                                            <strong>{{ $attendance->invitation->name ?? 'Unknown guest' }}</strong>
                                            <small>{{ $attendance->invitation->email ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($attendance->scanned_at)->format('d M Y, h:i A') ?? '—' }}</td>
                                <td>{{ $attendance->scanner->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-inboxes"></i>
                                        <p>No check-ins yet</p>
                                        <small>Scanned guests will show up here.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
