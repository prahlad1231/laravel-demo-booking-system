<?php

use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\SpaceController;
use App\Http\Controllers\Api\V1\SpaceReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware('auth:sanctum')->group(function () {
    Route::get('spaces', [SpaceController::class, 'index'])->name('spaces.index');
    Route::get('spaces/{space}/reservations', [SpaceReservationController::class, 'index'])->name('spaces.reservations.index');

    Route::post('spaces/{space}/reservations', [SpaceReservationController::class, 'store'])->name('spaces.reservations.store');
    Route::patch('reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update')
        ->can('update', 'reservation');
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy')
        ->can('delete', 'reservation');
});
