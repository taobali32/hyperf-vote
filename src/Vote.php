<?php

namespace Jtar\HyperfVote;

use Hyperf\Database\Model\Events\Saving;
use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\MorphTo;
use Hyperf\Database\Model\Builder;

class Vote extends Model
{
    protected array $guarded = [];


    protected array $appends = [
        'is_up_vote',
        'is_down_vote',
    ];

    protected array $casts = [
        'votes' => 'int',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = \config('vote.votes_table');

        parent::__construct($attributes);
    }

    public function saving(Saving $vote)
    {
        $userForeignKey = \config('vote.user_foreign_key');
        $vote->{$userForeignKey} = $vote->{$userForeignKey} ?: auth()->id();
    }

    public function votable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\config('auth.providers.users.model'), \config('vote.user_foreign_key'));
    }

    public function voter(): BelongsTo
    {
        return $this->user();
    }

    public function isUpVote(): bool
    {
        return $this->votes > 0;
    }

    public function isDownVote(): bool
    {
        return $this->votes < 0;
    }

    public function getIsUpVoteAttribute(): bool
    {
        return $this->isUpVote();
    }

    public function getIsDownVoteAttribute(): bool
    {
        return $this->isDownVote();
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('votable_type', ( new $type)->getMorphClass());
    }

    public function scopeOfVotable(Builder $query, string $type): Builder
    {
        return $this->scopeOfType(...\func_get_args());
    }
}