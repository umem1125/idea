<?php

use App\Models\User;

it('creates the new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Example Title')
        ->click('@button-status-completed')
        ->fill('description', 'Example Test Description')
        ->fill('@new-link', 'https://laravel.com')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laracasts.com')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'Do a thing')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do another thing')
        ->click('@submit-new-step-button')
        ->click('Create')
        ->assertPathIs('/ideas');


    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'Example Title',
        'status' => 'completed',
        'description' => 'Example Test Description',
        'links' => ['https://laravel.com', 'https://laracasts.com']
    ]);

    expect($idea->steps)->toHaveCount(2);
});
