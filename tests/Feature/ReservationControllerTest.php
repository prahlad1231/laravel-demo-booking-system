<?php

use App\Models\Reservation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

\it('booker can update the reservation', function () {
    $booker = User::factory()->create();
    $reservation = Reservation::factory()->for($booker)->create();
    Sanctum::actingAs($booker);

    $this->patchJson(\route('v1.reservations.update', $reservation), [
        'capacity_used' => 3,
    ])->assertOk();
});

\it('another customer cannot update the reservation', function () {
    $reservation = Reservation::factory()->create();
    Sanctum::actingAs(User::factory()->customer()->create());

    $this->patchJson(\route('v1.reservations.update', $reservation), [
        'capacity_used' => 3,
    ])->assertForbidden();
});

\it('receptionist cannot update the reservation', function () {
    $reservation = Reservation::factory()->create();
    Sanctum::actingAs(User::factory()->receptionist()->create());

    $this->patchJson(\route('v1.reservations.update', $reservation), [
        'capacity_used' => 5,
    ])->assertForbidden();
});

\it('booker can cancel the reservation', function () {
    $booker = User::factory()->create();
    $reservation = Reservation::factory()->for($booker)->create();
    Sanctum::actingAs($booker);

    $this->delete(\route('v1.reservations.destroy', $reservation))
        ->assertNoContent();
});
