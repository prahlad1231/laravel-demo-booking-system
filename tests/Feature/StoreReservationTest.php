<?php

use App\Enums\ReservationStatus;
use App\Models\Organisation;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

\beforeEach(function () {
    Sanctum::actingAs(User::factory()->create());
});

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

\it('creates a reservation', function () {
    $space = Space::factory()->create(['capacity' => 10]);

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => '2026-10-02 10:00',
        'ends_at' => '2026-10-02 12:00',
        'capacity_used' => 5,
        'user_id' => User::factory()->create()->id,
    ])->assertCreated();

    \expect(Reservation::count())->toBe(1);
});

\it('notifies attraction staff when a reservation is created', function () {
    Notification::fake();

    $organisation = Organisation::factory()->attraction()->create();
    $space = Space::factory()->for($organisation)->create(['capacity' => 10]);
    $manager = User::factory()->manager()->recycle($organisation)->create();
    $outsider = User::factory()->manager()->create();

    Sanctum::actingAs(User::factory()->customer()->create());

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => '2026-10-02 10:00',
        'ends_at' => '2026-10-02 12:00',
        'capacity_used' => 5,
    ])->assertCreated();

    Notification::assertSentTo($manager, App\Notifications\ReservationCreatedNotification::class);
    Notification::assertNotSentTo($outsider, App\Notifications\ReservationCreatedNotification::class);
});
