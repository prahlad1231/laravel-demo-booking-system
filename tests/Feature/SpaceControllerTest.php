<?php

use App\Models\Space;

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
