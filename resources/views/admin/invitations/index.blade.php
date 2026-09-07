@extends('layouts.app')

@section('title', 'Invitations')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
            <div>
                <h1 class="h3 mb-1">Invitations</h1>
                <p class="text-muted mb-0">Manage guest QR invitations.</p>
            </div>
            <a href="{{ route('invitation.create') }}" class="btn btn-dark">Create Invitation</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>Sent at</th>
                                <th>Expires at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invitations as $invitation)
                                <tr>
                                    <td class="fw-semibold">{{ $invitation->name }}</td>
                                    <td>{{ $invitation->email }}</td>
                                    <td>{{ $invitation->phone ?? '—' }}</td>
                                    <td>
                                        @if ($invitation->event)
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span>{{ $invitation->event->name }}</span>
                                                @if ($invitation->event->status === 'cancelled')
                                                    <span class="badge rounded-pill bg-danger">Cancelled</span>
                                                @endif
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $status = $invitation->status;
                                            $badgeClass = match ($status) {
                                                'used' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'expired' => 'bg-secondary',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($invitation->sent_at)->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>{{ optional($invitation->expires_at)->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('invitation.detail', $invitation->id) }}" class="btn btn-sm btn-dark">
                                            <i class="bi bi-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="p-4 text-center text-muted">
                                            <i class="bi bi-inbox text-secondary" style="font-size: 1.8rem;"></i>
                                            <div class="mt-2 fw-semibold">No invitations found</div>
                                            <div style="font-size: 0.9rem;">Create invitations to start generating QR codes.</div>
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