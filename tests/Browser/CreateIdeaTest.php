<?php

use App\Models\User;

it('create the new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Example Title')
        ->click('@button-status-completed')
        ->fill('description', 'Example Test Description')
        ->click('Create')
        ->assertPathIs('/ideas');


    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Example Title',
        'status' => 'completed',
        'description' => 'Example Test Description'
    ]);
});
