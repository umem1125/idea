<?php

use Illuminate\Support\Facades\Auth;

test('registers a user', function () {
    visit('/register')
        ->fill('name', 'Umam')
        ->fill('email', 'umam@gmail.com')
        ->fill('password', 'password')
        ->click('Create Account')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'Umam'
    ]);
});

test('requires a valid email', function () {
    visit('/register')
        ->fill('name', 'Umam')
        ->fill('email', 'umam')
        ->fill('password', 'password')
        ->click('Create Account')
        ->assertPathIs('/register');
});
