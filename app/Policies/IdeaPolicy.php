<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdeaPolicy
{
    public function workWith(User $user, Idea $idea): bool
    {
        // make sure that the current create data is the current user authenticated
        return $idea->user->is($user);
    }
}
