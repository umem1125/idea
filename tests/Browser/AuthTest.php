<?php

use App\Models\User;

test('log in a user', function () {
    // create dummy user
    $user = User::factory()->create(['password' => 'password123']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password123')
        ->click('@button-login')
        ->assertRoute('idea.index');

    $this->assertAuthenticated();
});

test('requires a valid password', function () {
    // create dummy user
    $user = User::factory()->create(['password' => 'password123']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'qwertyuiopap123')
        ->click('@button-login')
        ->assertPathIs('/login');
});

test('log out a user', function () {
    // create dummy user
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')->click('Log out');

    $this->assertGuest();
});
