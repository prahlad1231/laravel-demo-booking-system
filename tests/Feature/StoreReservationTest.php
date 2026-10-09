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
    $startsAt = \now()->addDay()->setTime(10, 0);

    $space = Space::factory()->create(['capacity' => 10]);
    Reservation::factory()->for($space)->create([
        'starts_at' => $startsAt,
        'ends_at' => $startsAt->addHours(3),
        'capacity_used' => 8,
        'status' => ReservationStatus::Confirmed,
    ]);

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => $startsAt->addHour()->format('Y-m-d H:i'),
        'ends_at' => $startsAt->addHours(3)->format('Y-m-d H:i'),
        'capacity_used' => 3,
        'user_id' => User::factory()->create()->id,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('capacity_used');
});

\it('creates a reservation', function () {
    $startsAt = \now()->addDay()->setTime(10, 0);

    $space = Space::factory()->create(['capacity' => 10]);

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => $startsAt->format('Y-m-d H:i'),
        'ends_at' => $startsAt->addHours(2)->format('Y-m-d H:i'),
        'capacity_used' => 5,
        'user_id' => User::factory()->create()->id,
    ])->assertCreated();

    \expect(Reservation::count())->toBe(1);
});

\it('notifies attraction staff when a reservation is created', function () {
    Notification::fake();

    $startsAt = \now()->addDay()->setTime(10, 0);

    $organisation = Organisation::factory()->attraction()->create();
    $space = Space::factory()->for($organisation)->create(['capacity' => 10]);
    $manager = User::factory()->manager()->recycle($organisation)->create();
    $outsider = User::factory()->manager()->create();

    Sanctum::actingAs(User::factory()->customer()->create());

    $this->postJson(\route('v1.spaces.reservations.store', $space), [
        'starts_at' => $startsAt->format('Y-m-d H:i'),
        'ends_at' => $startsAt->addHours(2)->format('Y-m-d H:i'),
        'capacity_used' => 5,
    ])->assertCreated();

    Notification::assertSentTo($manager, App\Notifications\ReservationCreatedNotification::class);
    Notification::assertNotSentTo($outsider, App\Notifications\ReservationCreatedNotification::class);
});
