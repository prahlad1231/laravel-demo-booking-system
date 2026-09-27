<?php

use App\Models\Space;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

\beforeEach(function () {
    Sanctum::actingAs(User::factory()->create());
});

\it('returns all spaces', function () {
    Space::factory(3)->create();

    $this->getJson(\route('v1.spaces.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

\it('returns an empty list when there are none', function () {
    $this->getJson(\route('v1.spaces.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
