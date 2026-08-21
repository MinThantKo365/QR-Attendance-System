<?php

namespace App\Http\Controllers;

use App\Mail\InvitationQrMail;
use App\Models\Event;
use App\Models\Invitation;
use App\Support\QrCodePng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::query()
            ->latest()
            ->get();

        return view('admin.invitations.index', compact('invitations'));
    }

    public function create()
    {
        $events = Event::query()
            ->orderBy('name')
            ->get();

        return view('admin.invitations.create', compact('events'));
    }

    public function detail($id)
    {
        $invitation = Invitation::with('attendance')->findOrFail($id);
        $qrBase64 = base64_encode(QrCodePng::generate($invitation->invite_id));

        return view('admin.invitations.detail', compact('invitation', 'qrBase64'));
    }

    public function sendEmail($id)
    {
        $invitation = Invitation::findOrFail($id);

        try {
            Mail::to($invitation->email)->send(new InvitationQrMail($invitation));
            $invitation->update(['sent_at' => now()]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('invitation.detail', $invitation->id)
                ->with('error', 'Could not send the invitation email. Please try again.');
        }

        return redirect()
            ->route('invitation.detail', $invitation->id)
            ->with('success', 'Invitation email sent to '.$invitation->email.'.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:pending,used,expired,cancelled',
            'expires_at' => 'nullable|date',
        ]);

        $validated['event_id'] = $validated['event_id'] ?? null;
        $validated['invite_id'] = (string) Str::uuid();
        $validated['sent_at'] = now();

        Invitation::create($validated);

        return redirect()
            ->route('invitation')
            ->with('success', 'Invitation created successfully.');
    }

    public function request()
    {
        return view('admin.invitations.request');
    }

    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $invitation = Invitation::create([
            ...$validated,
            'invite_id' => (string) Str::uuid(),
            'status' => 'pending',
            'sent_at' => now(),
        ]);

        try {
            Mail::to($invitation->email)->send(new InvitationQrMail($invitation));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('invitation.request')
                ->with('error', 'Your request was saved, but the invitation email could not be sent. Please contact the event staff.');
        }

        return redirect()
            ->route('invitation.request')
            ->with('success', 'Your invitation request has been submitted. Check your email for the QR code.');
    }
}
