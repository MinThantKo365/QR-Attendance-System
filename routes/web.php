<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/invitation-request', [InvitationController::class, 'request'])->name('invitation.request');
Route::post('/invitation-request', [InvitationController::class, 'submitRequest'])->name('invitation.request.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return view('scanner/index');
});

// QR scanning should work without login.
Route::post('/scanner/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/invitations', [InvitationController::class, 'index'])->name('invitation');
    Route::get('/admin/invitations/create', [InvitationController::class, 'create'])->name('invitation.create');
    Route::get('/admin/invitations/{id}', [InvitationController::class, 'detail'])->name('invitation.detail');
    Route::post('/admin/invitations/{id}/send', [InvitationController::class, 'sendEmail'])->name('invitation.send');
    Route::post('/admin/invitations', [InvitationController::class, 'store'])->name('invitation.store');
    Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/admin/events', [EventController::class, 'index'])->name('events');
    Route::get('/admin/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/admin/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('events.update');
});
