<?php

namespace App\Models;

use App\IdeaStatus;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING->value
    ];

    public static function statusCounts(User $user): Collection
    {
        // count the status then group by status
        $counts = $user->ideas()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Ensure all enum statuses are included and default missing ones to 0,
        // then add the total count of all ideas
        return collect(IdeaStatus::cases())
            ->mapWithKeys(fn($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $user->ideas()->count());
    }

    // relations belongs to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // relations the ideas has many steps
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function formattedDescription(): Attribute
    {
        return Attribute::get(fn($value, $attributes) => str($attributes['description'])->markdown());
    }
}
