<?php

namespace Jtar\HyperfFollow;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\MorphTo;
use Jtar\HyperfFollow\Events\Followed;
use Jtar\HyperfFollow\Events\Unfollowed;

class Followable extends Model
{
    protected array $guarded = [];

    /**
     * @deprecated
     * @var array|string[]
     */
    protected array $events = [
        'created' => Followed::class,
        'deleted' => Unfollowed::class,
    ];

    protected array $dates = ['accepted_at'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('follow.followables_table', 'followables');

        parent::__construct($attributes);
    }

    public function saving(Saving $event)
    {
        $userForeignKey = config('follow.user_foreign_key', 'user_id');
        $follower->setAttribute($userForeignKey, $follower->{$userForeignKey} ?: auth()->id());

    }

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), config('follow.user_foreign_key', 'user_id'));
    }

    public function follower(): BelongsTo
    {
        return $this->user();
    }

    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->where('followable_type', app($type)->getMorphClass());
    }

    public function scopeOf(Builder $query, Model $model): Builder
    {
        return $query->where('followable_type', $model->getMorphClass())
            ->where('followable_id', $model->getKey());
    }

    public function scopeFollowedBy(Builder $query, Model $follower): Builder
    {
        return $query->where(config('follow.user_foreign_key', 'user_id'), $follower->getKey());
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->whereNotNull('accepted_at');
    }

    public function scopeNotAccepted(Builder $query): Builder
    {
        return $query->whereNull('accepted_at');
    }

}