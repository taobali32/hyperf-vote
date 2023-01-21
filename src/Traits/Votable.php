<?php

namespace Jtar\HyperfVote\Traits;

use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\MorphMany;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;


trait Votable
{
    public function hasBeenVotedBy(Model $user): bool
    {
        if (\is_a($user, \config('vote.user_model'))) {
            if ($this->relationLoaded('voters')) {
                return $this->voters->contains($user);
            }

            return ($this->relationLoaded('votes') ? $this->votes : $this->votes())
                    ->where(config('vote.user_foreign_key'), $user->getKey())->count() > 0;
        }

        return false;
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(config('vote.vote_model'), 'votable');
    }

    public function upvotes(): MorphMany
    {
        return $this->votes()->where('votes', '>', 0);
    }

    public function downvotes(): MorphMany
    {
        return $this->votes()->where('votes', '<', 0);
    }

    public function voters()
    {
        return $this->belongsToMany(
            \config('vote.user_model'),
            config('vote.votes_table'),
            'votable_id',
            config('vote.user_foreign_key')
        )->where('votable_type', $this->getMorphClass())->withPivot(['votes']);
    }

    public function upvoters(): BelongsToMany
    {
        return $this->voters()->where('votes', '>', 0);
    }

    public function downvoters(): BelongsToMany
    {
        return $this->voters()->where('votes', '<', 0);
    }

    public function appendsVotesAttributes($attributes = ['total_votes', 'total_upvotes', 'total_downvotes'])
    {
        $this->append($attributes);

        return $this;
    }

    public function getTotalVotesAttribute()
    {
        return (int) $this->attributes['total_votes'] ?? $this->totalVotes();
    }

    public function getTotalUpvotesAttribute()
    {
        return abs($this->attributes['total_upvotes'] ?? $this->totalUpvotes());
    }

    public function getTotalDownvotesAttribute()
    {
        return abs($this->attributes['total_downvotes'] ?? $this->totalDownvotes());
    }

    public function totalVotes()
    {
        return $this->votes()->sum('votes');
    }

    public function totalUpvotes()
    {
        return $this->votes()->where('votes', '>', 0)->sum('votes');
    }

    public function totalDownvotes()
    {
        return $this->votes()->where('votes', '<', 0)->sum('votes');
    }

    public function scopeWithTotalVotes(Builder $builder): Builder
    {
        return $builder->withSum(['votes as total_votes' =>
            fn ($q) => $q->select(DB::raw('COALESCE(SUM(votes), 0)'))
        ], 'votes');
    }

    public function scopeWithTotalUpvotes(Builder $builder): Builder
    {
        return $builder->withSum(['votes as total_upvotes' =>
            fn ($q) => $q->where('votes', '>', 0)->select(DB::raw('COALESCE(SUM(votes), 0)'))
        ], 'votes');
    }

    public function scopeWithTotalDownvotes(Builder $builder): Builder
    {
        return $builder->withSum(['votes as total_downvotes' =>
            fn ($q) => $q->where('votes', '<', 0)->select(DB::raw('COALESCE(SUM(votes), 0)'))
        ], 'votes');
    }

    public function scopeWithVotesAttributes(Builder $builder)
    {
        $this->scopeWithTotalVotes($builder);
        $this->scopeWithTotalUpvotes($builder);
        $this->scopeWithTotalDownvotes($builder);
    }
}