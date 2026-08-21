<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        $scannedDate = $request->query('scanned_date');

        $attendances = Attendance::with(['invitation.event', 'scanner'])
            ->when($eventId, function ($query) use ($eventId) {
                $query->whereHas('invitation', function ($invitationQuery) use ($eventId) {
                    $invitationQuery->where('event_id', $eventId);
                });
            })
            ->when($scannedDate, function ($query) use ($scannedDate) {
                $query->whereDate('scanned_at', $scannedDate);
            })
            ->latest('scanned_at')
            ->get();

        $events = Event::query()
            ->orderBy('name')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'events', 'eventId', 'scannedDate'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'invite_id' => 'required|uuid',
        ]);

        $invitation = Invitation::where('invite_id', $request->invite_id)->first();

        if (! $invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 404);
        }

        if ($invitation->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This invitation has been cancelled.',
            ], 422);
        }

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'This invitation has expired.',
            ], 422);
        }

        if ($invitation->attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in.',
                'name' => $invitation->name,
            ], 422);
        }

        $scannerUserId = null;

        // If logged in, use the current scanner/admin user.
        if (Auth::check()) {
            $scannerUserId = Auth::id();
        }

        // If nobody is logged in, assign to the first available scanner user (or create one).
        if (! $scannerUserId) {
            $scannerUserId = User::query()->where('role', 'scanner')->value('id');
        }

        if (! $scannerUserId) {
            $scannerUser = User::create([
                'name' => 'Auto Scanner',
                'email' => 'auto-scanner-'.Str::uuid().'@local',
                'password' => Hash::make(Str::random(32)),
                'role' => 'scanner',
            ]);

            $scannerUserId = $scannerUser->id;
        }

        Attendance::create([
            'invitation_id' => $invitation->id,
            'scanner_user_id' => $scannerUserId,
            'scanned_at' => now(),
        ]);

        $invitation->update(['status' => 'used']);

        return response()->json([
            'success' => true,
            'message' => 'Welcome, '.$invitation->name.'!',
            'name' => $invitation->name,
        ]);
    }
}
