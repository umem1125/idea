<?php

namespace App\Actions;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea)
    {
        $data = collect($attributes)->only([
            'title',
            'description',
            'status',
            'links'
        ])->toArray();

        // save to storage/public/ideas
        if ($attributes['image'] ?? false) {
            // Delete old image if exists
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        // to make sure consistency data. if have an error, all of data edited will be rollback
        DB::transaction(function () use ($idea, $data, $attributes) {
            $idea->update($data);

            $idea->steps()->delete();

            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
