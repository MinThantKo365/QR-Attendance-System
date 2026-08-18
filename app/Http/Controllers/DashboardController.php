<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'invitations' => Invitation::count(),
            'checked_in' => Invitation::where('status', 'used')->count(),
            'pending' => Invitation::where('status', 'pending')->count(),
            'today' => Attendance::whereDate('scanned_at', today())->count(),
        ];

        $recentAttendances = Attendance::with(['invitation', 'scanner'])
            ->latest('scanned_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttendances'));
    }
}
