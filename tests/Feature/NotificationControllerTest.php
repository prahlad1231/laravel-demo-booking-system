<?php

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationCreatedNotification;
use Laravel\Sanctum\Sanctum;

\it('returns only the unread notification of a user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->notify(new ReservationCreatedNotification(Reservation::factory()->create()));
    $otherUser->notify(new ReservationCreatedNotification(Reservation::factory()->create()));

    Sanctum::actingAs($user);

    $this->getJson(\route('v1.notifications.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
