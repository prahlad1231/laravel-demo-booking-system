<?php

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

\beforeEach(function () {
    Sanctum::actingAs(User::factory()->admin()->create());
});

\it('returns space reservation', function () {
    $space = Space::factory()->create();
    Reservation::factory(3)->for($space)->create();

    $this->getJson(\route('v1.spaces.reservations.index', $space))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

\it('does not return reservation from other spaces', function () {
    $space1 = Space::factory()->create();
    Reservation::factory(2)->for($space1)->create();

    $space2 = Space::factory()->create();
    Reservation::factory(3)->for($space2)->create();

    $this->getJson(\route('v1.spaces.reservations.index', $space1))
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson(\route('v1.spaces.reservations.index', $space2))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

\it('return 404 for an unknown space', function () {
    $this->getJson(\route('v1.spaces.reservations.index', 99))
        ->assertNotFound();
});

\it('return empty data where there is no reservation for a space', function () {
    $space = Space::factory()->create();

    $this->getJson(\route('v1.spaces.reservations.index', $space))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

\it('return reservation between date range', function () {
    $space = Space::factory()->create();
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-10 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-11 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-12 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-13 10:00']);

    $this->getJson(\route('v1.spaces.reservations.index', ['space' => $space, 'from' => '2026-10-12']))
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson(\route('v1.spaces.reservations.index', ['space' => $space, 'from' => '2026-10-11', 'to' => '2026-10-12']))
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

\it('rejects to date before from', function () {
    $space = Space::factory()->create();
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-10 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-11 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-12 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-13 10:00']);

    $this->getJson(\route('v1.spaces.reservations.index', ['space' => $space, 'from' => '2026-10-11', 'to' => '2026-10-10']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('to');
});

\it('rejects wrong date format', function () {
    $space = Space::factory()->create();
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-10 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-11 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-12 10:00']);
    Reservation::factory()->for($space)->create(['starts_at' => '2026-10-13 10:00']);

    $this->getJson(\route('v1.spaces.reservations.index', ['space' => $space, 'from' => '2026-10-10', 'to' => 'next tuesday']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('to');
});

\it('shows a customer only their own reservations', function () {
    $space = Space::factory()->create();
    $customer = User::factory()->customer()->create();

    Reservation::factory(2)->for($space)->for($customer)->create();
    Reservation::factory(3)->for($space)->create();

    Sanctum::actingAs($customer);

    $this->getJson(\route('v1.spaces.reservations.index', $space))
        ->assertOk()
        ->assertJsonCount(2, 'data');
});
