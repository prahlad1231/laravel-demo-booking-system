<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\SpaceController;
use \App\Http\Controllers\Api\V1\SpaceReservationController;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::get('spaces', [SpaceController::class, 'index'])->name('spaces.index');
    Route::get('spaces/{space}/reservations', [SpaceReservationController::class, 'index'])->name('spaces.reservations.index');
});
