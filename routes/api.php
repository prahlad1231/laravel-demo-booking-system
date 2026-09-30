<?php

use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\SpaceController;
use App\Http\Controllers\Api\V1\SpaceReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('spaces', SpaceController::class)->only(['index']);
    Route::apiResource('spaces.reservations', SpaceReservationController::class)->only(['index', 'store']);
    Route::apiResource('reservations', ReservationController::class)->only(['update', 'destroy']);

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
});
