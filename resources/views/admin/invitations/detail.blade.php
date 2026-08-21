@extends('layouts.app')

@section('title', 'Invitation Detail')

@push('styles')
    <style>
        .invite-detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .invite-info-card,
        .invite-print-card {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .invite-info-list {
            margin: 0;
        }

        .invite-info-list dt {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c757d;
            margin-bottom: 0.15rem;
        }

        .invite-info-list dd {
            margin: 0 0 1rem;
            font-weight: 600;
            word-break: break-word;
        }

        .invite-print-card {
            max-width: 380px;
            margin: 0 auto;
            overflow: hidden;
        }

        .invite-print-card__header {
            background: #1a1d21;
            color: #fff;
            text-align: center;
            padding: 1.25rem 1rem;
        }

        .invite-print-card__header img {
            height: 42px;
            margin-bottom: 0.6rem;
        }

        .invite-print-card__header h2 {
            font-size: 1.1rem;
            margin: 0 0 0.25rem;
            font-weight: 700;
        }

        .invite-print-card__header p {
            margin: 0;
            color: #adb5bd;
            font-size: 0.85rem;
        }

        .invite-print-card__body {
            padding: 1.5rem 1.25rem;
            text-align: center;
        }

        .invite-print-card__body h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .invite-print-card__body .guest-email {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .invite-qr {
            width: 220px;
            height: 220px;
            object-fit: contain;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 0.75rem;
            background: #fff;
            margin: 0 auto 1rem;
            display: block;
        }

        .invite-id {
            font-family: Consolas, Monaco, monospace;
            font-size: 0.75rem;
            color: #495057;
            word-break: break-all;
            margin-bottom: 0.75rem;
        }

        .invite-print-card__footer {
            border-top: 1px solid #e9ecef;
            padding: 0.85rem 1rem;
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
        }

        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            body * {
                visibility: hidden;
            }

            .invite-print-area,
            .invite-print-area * {
                visibility: visible;
            }

            .invite-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 12mm;
            }

            .navbar,
            main > .container.mt-3,
            .container.mt-3,
            .no-print {
                display: none !important;
            }

            .invite-print-card {
                box-shadow: none;
                max-width: 100%;
                border: 1px solid #000;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $badgeClass = match ($invitation->status) {
            'used' => 'bg-success',
            'pending' => 'bg-warning text-dark',
            'expired' => 'bg-secondary',
            'cancelled' => 'bg-danger',
            default => 'bg-light text-dark',
        };
    @endphp

    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3 no-print">
            <div>
                <h1 class="h3 mb-1">Invitation detail</h1>
                <p class="text-muted mb-0">Guest info, printable QR card, and email delivery.</p>
            </div>
            <div class="invite-detail-actions">
                <a href="{{ route('invitation') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
                <button type="button" class="btn btn-outline-dark" id="printInviteCardBtn">
                    <i class="bi bi-printer"></i>
                    Print card
                </button>
                <form action="{{ route('invitation.send', $invitation->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope"></i>
                        Send email
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5 no-print">
                <div class="invite-info-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h5 mb-0">Guest information</h2>
                        <span class="badge rounded-pill {{ $badgeClass }}">{{ ucfirst($invitation->status) }}</span>
                    </div>

                    <dl class="invite-info-list">
                        <dt>Name</dt>
                        <dd>{{ $invitation->name }}</dd>

                        <dt>Email</dt>
                        <dd>{{ $invitation->email }}</dd>

                        <dt>Phone</dt>
                        <dd>{{ $invitation->phone ?? '—' }}</dd>

                        <dt>Invite ID</dt>
                        <dd class="invite-id mb-3">{{ $invitation->invite_id }}</dd>

                        <dt>Sent at</dt>
                        <dd>{{ optional($invitation->sent_at)->format('d M Y, h:i A') ?? '—' }}</dd>

                        <dt>Expires at</dt>
                        <dd>{{ optional($invitation->expires_at)->format('d M Y, h:i A') ?? '—' }}</dd>

                        <dt>Created</dt>
                        <dd>{{ optional($invitation->created_at)->format('d M Y, h:i A') ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="invite-print-area">
                    <div class="invite-print-card">
                        <div class="invite-print-card__header">
                            <img src="{{ asset('images/logo.png') }}" alt="QR Attendance">
                            <h2>QR Attendance</h2>
                            <p>Event invitation card</p>
                        </div>
                        <div class="invite-print-card__body">
                            <h3>{{ $invitation->name }}</h3>
                            <div class="guest-email">{{ $invitation->email }}</div>
                            <img
                                class="invite-qr"
                                src="data:image/png;base64,{{ $qrBase64 }}"
                                alt="Invitation QR Code"
                            >
                            <div class="invite-id">{{ $invitation->invite_id }}</div>
                            @if ($invitation->expires_at)
                                <div class="text-muted small">
                                    Valid until {{ $invitation->expires_at->format('d M Y, h:i A') }}
                                </div>
                            @endif
                        </div>
                        <div class="invite-print-card__footer">
                            Present this QR code at the entrance for check-in.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('printInviteCardBtn')?.addEventListener('click', function () {
            const originalTitle = document.title;
            document.title = ' ';

            const restoreTitle = function () {
                document.title = originalTitle;
                window.removeEventListener('afterprint', restoreTitle);
            };

            window.addEventListener('afterprint', restoreTitle);
            window.print();

            // Fallback for browsers that do not fire afterprint reliably.
            setTimeout(restoreTitle, 1000);
        });
    </script>
@endpush
