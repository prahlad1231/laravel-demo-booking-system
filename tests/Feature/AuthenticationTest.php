<?php

use App\Models\User;

\it('logs in with valid credentials', function () {
    $user = User::factory()->create(['email' => 'owner@attraction.test']);

    $this->post('/login', [
        'email' => 'owner@attraction.test',
        'password' => 'password',
    ])->assertRedirect(\route('spaces.index'));

    $this->assertAuthenticatedAs($user);
});

\it('rejects a wrong password', function () {
    $user = User::factory()->create(['email' => 'owner@attraction.test']);

    $this->post('/login', [
        'email' => 'owner@attraction.test',
        'password' => 'wrong_password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

\it('logs out a user', function () {
    $user = User::factory()->create(['email' => 'owner@attraction.test']);

    $this->actingAs($user);

    $this->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

\it('redirect a guest to login when trying to see spaces', function () {
    $user = User::factory()->create(['email' => 'owner@attraction.test']);

    $this->actingAsGuest();

    $this->get(\route('spaces.index'))->assertRedirect('/login');
});
