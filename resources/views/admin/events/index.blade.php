@extends('layouts.app')

@section('title', 'Events')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Events</h5>
                        <a href="{{ route('events.create') }}" class="btn btn-dark">Create Event</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Event Start</th>
                                <th>Event End</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $e)
                                @php
                                    $status = $e->status;
                                    $badgeClass = match ($status) {
                                        'published' => 'bg-success',
                                        'draft' => 'bg-secondary',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    };
                                    $start = $e->event_start ? \Carbon\Carbon::parse($e->event_start)->format('d M Y, h:i A') : '—';
                                    $end = $e->event_end ? \Carbon\Carbon::parse($e->event_end)->format('d M Y, h:i A') : '—';
                                @endphp
                                <tr
                                    class="event-row"
                                    role="button"
                                    data-id="{{ $e->id }}"
                                    data-name="{{ $e->name }}"
                                    data-description="{{ $e->description }}"
                                    data-start="{{ $start }}"
                                    data-end="{{ $end }}"
                                    data-location="{{ $e->location }}"
                                    data-status="{{ $status }}"
                                    data-badge="{{ $badgeClass }}"
                                    data-edit-url="{{ route('events.edit', $e) }}"
                                >
                                    <td>{{ $e->name ?? '—' }}</td>
                                    <td>{{ $e->description ? \Illuminate\Support\Str::limit($e->description, 40) : '—' }}</td>
                                    <td>{{ $start }}</td>
                                    <td>{{ $end }}</td>
                                    <td>{{ $e->location ?? '—' }}</td>
                                    <td>
                                        @if ($status)
                                            <span class="badge rounded-pill {{ $badgeClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('events.edit', $e) }}" class="btn btn-sm btn-dark">
                                            <i class="bi bi-pencil"></i>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="bi bi-clipboard-check"></i>
                                            <p>No event records found</p>
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

    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailModalLabel">Event details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1" id="modalEventName">—</h2>
                            <p class="text-muted mb-0" id="modalEventLocation">—</p>
                        </div>
                        <span class="badge rounded-pill" id="modalEventStatus">—</span>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8" id="modalEventDescription">—</dd>
                        <dt class="col-sm-4">Start</dt>
                        <dd class="col-sm-8" id="modalEventStart">—</dd>
                        <dt class="col-sm-4">End</dt>
                        <dd class="col-sm-8" id="modalEventEnd">—</dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" class="btn btn-dark" id="modalEventEditBtn">
                        <i class="bi bi-pencil"></i>
                        Edit event
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const eventDetailModalEl = document.getElementById('eventDetailModal');
        const eventDetailModal = eventDetailModalEl ? new bootstrap.Modal(eventDetailModalEl) : null;

        function fillEventDetailModal(row) {
            const name = row.dataset.name || '—';
            const description = row.dataset.description || '—';
            const start = row.dataset.start || '—';
            const end = row.dataset.end || '—';
            const location = row.dataset.location || '—';
            const status = row.dataset.status || '';
            const badge = row.dataset.badge || 'bg-light text-dark';
            const editUrl = row.dataset.editUrl || '#';

            document.getElementById('modalEventName').textContent = name;
            document.getElementById('modalEventLocation').textContent = location;
            document.getElementById('modalEventDescription').textContent = description;
            document.getElementById('modalEventStart').textContent = start;
            document.getElementById('modalEventEnd').textContent = end;

            const statusEl = document.getElementById('modalEventStatus');
            statusEl.className = 'badge rounded-pill ' + badge;
            statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : '—';

            document.getElementById('modalEventEditBtn').setAttribute('href', editUrl);
        }

        document.querySelectorAll('.event-row').forEach((row) => {
            row.addEventListener('click', (e) => {
                if (e.target.closest('a, button')) {
                    return;
                }

                fillEventDetailModal(row);
                eventDetailModal?.show();
            });
        });
    </script>
@endpush
