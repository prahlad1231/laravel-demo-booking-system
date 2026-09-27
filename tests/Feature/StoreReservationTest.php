<?php

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;

\it('rejects a booking that exceeds the capacity', function () {
    $space = Space::factory()->create(['capacity' => 10]);
    Reservation::factory()->for($space)->create([
        'starts_at' => '2026-10-02 10:00',
        'ends_at' => '2026-10-02 13:00',
        'capacity_used' => 8,
        'status' => ReservationStatus::Confirmed,
    ]);

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => '2026-10-02 11:00',
        'ends_at' => '2026-10-02 13:00',
        'capacity_used' => 3,
        'user_id' => User::factory()->create()->id,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('capacity_used');
});
