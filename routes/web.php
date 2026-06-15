<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;

// ==========================================
// 1. AUTHENTICATION ROUTES (Public)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 2. PROTECTED ROUTES (Butuh Login)
// ==========================================
// Middleware di-comment sementara sampai halaman login selesai dibuat.
Route::group([] /* ->middleware(['auth']) */, function () {
    
    // Dashboard (Overview Acara)
    Route::get('/', [EventController::class, 'index'])->name('dashboard');
    
    // Communities Management
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    
    // Event Management
    Route::get('/events', [EventController::class, 'manage'])->name('events');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('event-detail');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    
    // Participants & Attendance
    Route::get('/events/{eventId}/participants', [ParticipantController::class, 'index'])->name('participants');
    Route::get('/events/{eventId}/attendance', [AttendanceController::class, 'index'])->name('attendance');
    
    // Certificates
    Route::get('/events/{eventId}/certificates', [CertificateController::class, 'index'])->name('certificates');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Platform Settings (Profile)
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('settings.update');

});
