<?php

use App\Models\Idea;
use App\Models\User;

it('edits an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->for($user)->create();

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->fill('title', 'Edit Title Test')
        ->click('@button-status-completed')
        ->fill('description', 'Description Test')
        ->fill('@new-link', 'https://google.com')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'A')
        ->click('@submit-new-step-button')
        ->click('Update')
        ->assertRoute('idea.show', [$idea]);

    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'Edit Title Test',
        'status' => 'completed',
        'description' => 'Description Test',
        'links' => [$idea->links[0], 'https://google.com']
    ]);

    expect($idea->steps)->toHaveCount(1);
});
