<?php

use App\Http\Controllers\Web\AuthenticatedSessionController;
use App\Http\Controllers\Web\SpaceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

if (\app()->isLocal()) {
    Route::get('dev-login/{id}', function (int $id) {
        Auth::loginUsingId($id);

        return \redirect()->to('/spaces');
    })->name('dev-login');
}

Route::middleware('auth')->group(function () {
    Route::resource('spaces', SpaceController::class)->only(['index']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});
