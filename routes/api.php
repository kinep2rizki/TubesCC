<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function() {
    // Communities
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{id}/feed', [CommunityController::class, 'feed']);
    Route::post('/communities/{id}/feed', [CommunityController::class, 'storeFeed']);
    Route::post('/communities/{id}/roles', [CommunityController::class, 'updateRoles']);

    // Events
    Route::apiResource('events', EventController::class);

    // Participants
    Route::get('/events/{id}/participants', [ParticipantController::class, 'index']);
    Route::post('/events/{id}/participants', [ParticipantController::class, 'store']);
    Route::get('/events/{id}/participants/export', [ParticipantController::class, 'export']);

    // Attendance
    Route::get('/events/{id}/attendance', [AttendanceController::class, 'index']);
    Route::post('/events/{id}/attendance/check-in', [AttendanceController::class, 'checkIn']);

    // Analytics
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    Route::post('/analytics/export', [AnalyticsController::class, 'export']);

    // Profile
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::put('/profile/security', [ProfileController::class, 'security']);
    Route::put('/profile/notifications', [ProfileController::class, 'notifications']);
});

// Certificates (Public for Web Frontend Access)
Route::get('/certificates/templates', [CertificateController::class, 'templates']);
Route::post('/certificates/generate', [CertificateController::class, 'generate']);
Route::get('/certificates/{id}/download', [CertificateController::class, 'download']);
